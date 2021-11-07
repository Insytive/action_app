<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $first_name
 * @property string $last_name
 * @property string $id_number
 * @property \Cake\I18n\FrozenDate|null $birthdate
 * @property string|null $membership_number
 * @property int|null $gender_id
 * @property string|null $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $street_address
 * @property string|null $suburb
 * @property string|null $town
 * @property string|null $municipality
 * @property int|null $postal_address
 * @property bool $first_time_voter
 * @property int $user_status
 * @property int|null $voting_station_id
 * @property int|null $branch_id
 * @property int|null $role_id
 * @property int|null $province_id
 * @property string|null $token
 * @property string|null $password_reset
 * @property int $points
 * @property \Cake\I18n\FrozenTime|null $password_expiry
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\ParentUser $parent_user
 * @property \App\Model\Entity\Gender $gender
 * @property \App\Model\Entity\VotingStation $voting_station
 * @property \App\Model\Entity\Branch $branch
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\AuditLog[] $audit_logs
 * @property \App\Model\Entity\Session[] $sessions
 * @property \App\Model\Entity\ChildUser[] $child_users
 * @property \App\Model\Entity\Badge[] $badges
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'membership_number' => true,
        'first_time_voter' => true,
        'password_expiry' => true,
        'password_reset' => true,
        'street_address' => true,
        'municipality' => true,
        'postal_code' => true,
        'first_name' => true,
        'last_name' => true,
        'id_number' => true,
        'birthdate' => true,
        'password' => true,
        'suburb' => true,
        'points' => true,
        'token' => true,
        'phone' => true,
        'email' => true,
        'town' => true,

        'voting_station_id' => true,
        'province_id' => true,
        'gender_id' => true,
        'branch_id' => true,
        'role_id' => true,

        'created_at' => true,
        'updated_at' => true,

        'user_status' => true,
        'parent_user' => true,
        'child_users' => true,
        'parent_id' => true,

        'voting_station' => true,
        'audit_logs' => true,
        'sessions' => true,
        'province' => true,
        'badges' => true,
        'branch' => true,
        'gender' => true,
        'role' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];


    protected function _setPassword(string $password)
    {
        $passwordHash = new DefaultPasswordHasher();
        return $passwordHash->hash($password);
    }


    protected function _setPhone($phone){
        $phone = trim($phone);
        $phone = preg_replace('/\D/', '', $phone);

        return $phone;
    }


    protected function _getName(){
        if( empty($this->first_name) && empty($this->last_name)){
            return null;
        }

        return $this->first_name .' '. $this->last_name;
    }

    protected function _getNameIdNumber(){
        return $this->first_name .' '. $this->last_name .' - '. $this->id_number;
    }

    protected function _getActiveStatus(){
        return $this->user_status & 1;
    }
}
