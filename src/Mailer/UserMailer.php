<?php
declare(strict_types=1);

namespace App\Mailer;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\Client;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

/**
 * User mailer.
 */
class UserMailer extends Mailer implements EventListenerInterface
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static $name = 'User';

    private $notificationType  = '';
    private $entity = '';
    private $method = '';

    public function implementedEvents(): array {
        return [
            'Model.afterSave' => 'notify'
        ];
    }

    public function notify(EventInterface $event, EntityInterface $entity, ArrayObject $options): void {
        if ( empty($entity->membership_number) ){
            $usersTable = TableRegistry::getTableLocator()->get('Users');
            $entity->membership_number = $usersTable->generateMembershipNumberFromId($entity);
        }

        $this->setNotification($entity);

        if ('email' === $this->notificationType && !empty($this->method)){
            $this->send($this->method, [$entity]);

            return;
        }

        if ('sms' === $this->notificationType && !empty($this->method)){
            $this->{$this->method}($entity);

            return;
        }

        return;
    }


    //public function send_notification()
    public function setNotification(EntityInterface $entity): void {
        // Can we send either an email or SMS and do we have a "new/change in" membership status
        if ( ! $this->notifiable($entity) || (! $entity->isDirty('user_status') && ! $entity->isDirty('password_reset'))){
            return;
        }

        $this->entity = $entity;

        // New Registration? Send welcome communication
        if ($entity->isNew()){
            $member_type = ($entity->user_status & MEMBER_SUPPORTER) ? 'supporter' : 'member';

            $this->method = 'welcome_' . $member_type . '_' .  $this->notificationType;

            return;
        }


        if ($entity->isDirty('password_reset')){
            $this->method = 'reset_password_' . $this->notificationType;

            return;
        }

        // Membership type change => compare old user status vs new
        $new_status = bitMaskToOptions($entity->user_status);
        $old_status = bitMaskToOptions($entity->getOriginal('user_status'));

        $removed = array_diff($old_status, $new_status);
        $added = array_diff($new_status, $old_status);

        // Supporter status removed but active status present? Supporter => Member
        if ( in_array(MEMBER_SUPPORTER, $removed)){
            $this->method = 'welcome_member_' . $this->notificationType;

            return;
        }

        // Volunteer status removed but active status present? Volunteer => Member (Volunteer status revoked)
        if ( in_array(MEMBER_VOLUNTEER, $removed)){
            $this->method = 'volunteer_revoked_' . $this->notificationType;

            return;
        }

        // Active status removed?
        if ( in_array(MEMBER_VOLUNTEER, $removed)){
            $this->method = 'membership_revoked_' . $this->notificationType;

            return;
        }

        // Volunteer status added for the first time? See this by checking if password is new
        if ( in_array(MEMBER_VOLUNTEER, $added) && $entity->isDirty('password')){
            $this->method = 'volunteer_accepted_' . $this->notificationType;

            return;
        }

        // Volunteer status restored? See this by checking if password is old/pre-existing password
        if ( in_array(MEMBER_VOLUNTEER, $added) && ! $entity->isDirty('password')){
            $this->method = 'volunteer_restored_' . $this->notificationType;

            return;
        }

        return;
    }


    public function reset_password_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __('Please got to https://actionsa.app/rp and enter {0} to reset your ActionSA password.', $this->password_reset)]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }


    public function reset_password_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('ActionSA Password Reset')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
             ->setEmailFormat('both')
             ->viewBuilder()
             ->setTemplate('reset_password_email')
             ->setLayout('default');
    }


    public function welcome_member_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __('Thank you for taking ACTION! You have officially registered with ActionSA.')]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }


    public function welcome_member_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('Welcome to ActionSA')
             ->setProfile('default')
             ->setFrom('donotreply@actionsa.org.za', 'ActionSA')
             ->setViewVars([
                  'user' => $entity,
             ])
             ->setEmailFormat('both')
             ->viewBuilder()
                ->setTemplate('welcome_member_email')
                ->setLayout('default');
    }

    public function welcome_supporter_sms(EntityInterface $entity, $type){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __('Thank you for taking ACTION! You have officially registered with ActionSA as a {0}', $type)]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }


    public function welcome_supporter_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('Welcome to ActionSA')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
             ->setEmailFormat('both')
            ->send();
    }


    public function pendingVerification(EntityInterface $entity){
    }

    public function resetPassword(EntityInterface $entity){
    }

    public function volunteer_revoked_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __('ACTION has been taken! Your profile status has been changed to review your account. You will no longer be able to login until further notice.')]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }

    public function volunteer_revoked_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('ActionSA Volunteer Status Revoked')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
            ->setEmailFormat('both')
            ->send();
    }


    public function volunteer_accepted_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __("ACTION has been taken! You have officially registered with ActionSA as a volunteer.
Login: https://actionsa.app/login
Username: {$entity->membership_number}
Use your ID number as password.")]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }

    public function volunteer_accepted_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('ActionSA Volunteer Status Accepted')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
             ->setEmailFormat('both')
            ->send();
    }


    public function volunteer_restored_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __("ACTION has been taken! Your profile status has been restored. You can now be able to login and continue to use our system dashboard.")]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }

    public function volunteer_restored_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('ActionSA Volunteer Status Restored')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
             ->setEmailFormat('both')
            ->send();
    }


    public function membership_revoked_sms(EntityInterface $entity){
        $query_strings = array_merge(
            $this->sms_settings($entity->phone),
            ['text' => __('ACTION has been taken! Your membership status revoked.')]
        );

        $httpClient = new Client();

        $httpClient->get(PANACEA_API, $query_strings);
    }

    public function membership_revoked_email(EntityInterface $entity){
        $this->setTo($entity->email, $entity->name)
             ->setSubject('ActionSA Membership Status Revoked')
             ->setProfile('default')
             ->setViewVars([
                 'user' => $entity,
             ])
             ->setEmailFormat('both')
            ->send();
    }


    /**
     * @param EntityInterface $entity has to be User entity
     *
     * @return bool
     */
    private function notifiable(EntityInterface $entity): bool {
        if (filter_var($entity->email, FILTER_VALIDATE_EMAIL)) {
            $this->notificationType = 'email';

            return true;
        }

        if (!empty($entity->phone)){
            $this->notificationType = 'sms';

            return true;
        }

        return false;
    }

    private function sms_settings($phone): array {
        return [
            'action'    => 'message_send',
            'username'  => PANACEA_USERNAME,
            'password'  => PANACEA_API_KEY,
            'to'        => $phone,
            'from'      => 'ActionSA',
        ];
    }
}
