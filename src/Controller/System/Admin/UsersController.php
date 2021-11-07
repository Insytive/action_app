<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Authentication\AuthenticationService;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 * @property AuthenticationService $Authentication
 * @method User[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends SystemAdminController
{

    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $role = 'Member';

        $this->paginate = [
            'contain' => ['ParentUsers', 'VotingStations', 'Roles'],
        ];

        if ($this->getRequest()->getQuery('role')){
            $this->paginate['conditions'] = ['Roles.id' => $this->getRequest()->getQuery('role')];

            $roles = $this->Users->Roles->find('all', [
                'conditions' => ['Roles.id' => $this->getRequest()->getQuery('role')],
                'field' => ['Roles.id', 'Roles.name']
            ]);

            if (1 === $roles->count()){
                $currentRole = $roles->first();
                $role = $currentRole->name;
            }
        }

        if ($this->getRequest()->getQuery('volunteer')){
            $this->paginate['conditions']['Users.parent_id'] = $this->getRequest()->getQuery('volunteer');
        }

        if ($this->getRequest()->getQuery('q')){
            $this->paginate['conditions']['OR'] = [
                'Users.id_number' => $this->getRequest()->getQuery('q'),
                'Users.first_name LIKE' => '%'. $this->getRequest()->getQuery('q') .'%',
                'Users.last_name LIKE'  => '%'. $this->getRequest()->getQuery('q') .'%',
                'Users.membership_number'  => $this->getRequest()->getQuery('q'),
            ];
        }

        if ( 4 === $this->Authentication->getIdentity()->get('role_id') ){
            $this->paginate['conditions']['Users.parent_id'] = $this->Authentication->getIdentity()->get('id');
        }

        if ( 5 === $this->Authentication->getIdentity()->get('role_id') ){
            $this->paginate['conditions']['Users.role_id'] = 4;
        }

        if ($this->getRequest()->getQuery('status') && array_key_exists($this->getRequest()->getQuery('status'), getUserStatuses())){
            $this->paginate['conditions']['Users.user_status & '] = (int)$this->getRequest()->getQuery('status');
        }

        // Residence province
        if ($this->getRequest()->getQuery('p')){
            $this->paginate['conditions']['Users.province_id'] = (int)$this->getRequest()->getQuery('p');
        }

        // No voting station
        if ($this->getRequest()->getQuery('nvs')){
            $this->paginate['conditions']['Users.voting_station_id IS'] = null;
        }

        // No phone number
        if ($this->getRequest()->getQuery('npn')){
            $this->paginate['conditions']['Users.phone'] = '';
        }

        // No email address
        if ($this->getRequest()->getQuery('nea')){
            $this->paginate['conditions']['Users.email'] = '';
        }

        $users = $this->paginate($this->Users);

        $this->set(compact('users', 'role'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ( 4 === $this->Authentication->getIdentity()->get('role_id') &&
            !$this->Users->canAccess($id, $this->Authentication->getIdentity()->get('id'))){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->get($id, [
            'contain' => ['ParentUsers', 'Genders', 'VotingStations', 'Branches', 'Provinces', 'Roles', 'Badges', 'AuditLogs'], // , 'ChildUsers'
        ]);

        $this->loadModel('Settings');

        $settings = $this->Settings->findByName('display_membership_card');
        $display_membership_card = false;

        if( 1 === $settings->count()){
            $setting = $settings->first();

            if (true === (bool)$setting->value){
                $display_membership_card = true;
            }
        }

        $this->set(compact('user', 'display_membership_card'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!in_array($this->Authentication->getIdentity()->get('role_id'), range(1,6))){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        if (4 === $this->Authentication->getIdentity()->get('role_id') && !(2 & $this->Authentication->getIdentity()->get('user_status'))){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->newEmptyEntity();

        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();

            $valid = true;

            if (true === $this->Users->validateIdNumber($this->getRequest()->getData('id_number'))){
                $data['gender_id'] = $this->Users->idParts['gender_id'];
                $data['birthdate'] = $this->Users->idParts['birthdate'];
                $data['user_status'] = 1;
                //$data['membership_number'] = $this->Users->generateMembershipNumber();
            }

            $voting_station = null;
            if ($this->getRequest()->getData('station_id')){
                $data['voting_station_id'] = $this->getRequest()->getData('station_id');
            } elseif ($this->getRequest()->getData('voting_station')){
                $voting_station = $this->getRequest()->getData('voting_station');
            }
            unset($data['station_id']);
            unset($data['voting_station']);

            // Get Province ID
            if ($this->getRequest()->getData('province')){
                $data['province_id'] = $this->Users->Provinces->provinceByCode($this->getRequest()->getData('province'));
                unset($data['province']);
            }

            if ( 4 === $this->Authentication->getIdentity()->get('role_id') ){
                $data['parent_id'] =$this->Authentication->getIdentity()->get('id');
            }

            $user = $this->Users->patchEntity($user, $data);

            if ( ! $user->hasErrors() && $valid ) {

                if ( ! $user->voting_station_id && !empty($voting_station)){
                    $user->voting_station_id = $this->Users->VotingStations->newUserStation($voting_station);
                }

                if ($this->Users->save($user)){

                    // Store the membership number of this user
                    $this->Users->updateMembershipNumber($user);

                    $this->Flash->success(__('Member successfully registered.'));

                    return $this->redirect(['action' => 'index']);
                }
            }

            $this->Flash->error(__('The member could not be registered. Please, try again.'));
        }

        $this->Users->ParentUsers->setDisplayField('name_id_number');

        $parentUsers = $this->Users->ParentUsers->find('list', [
            'fields' => [
                'ParentUsers.id',
                'ParentUsers.first_name',
                'ParentUsers.last_name',
                'ParentUsers.id_number',
            ],
            'conditions' => [
                'ParentUsers.user_status &' => 2
            ],
            'order' => ['first_name ASC'],
            'limit' => 1000
        ]);

        $branches = $this->Users->Branches->find('list');

        $provinces = $this->Users->Provinces->find('list', [
            'keyField' => 'code',
            'valueField' => 'name'
        ]);

        $this->set(compact('user', 'parentUsers', 'provinces', 'branches'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ( 4 === $this->Authentication->getIdentity()->get('role_id') &&
             !$this->Users->canAccess($id, $this->Authentication->getIdentity()->get('id'))){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->get($id);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();

            $valid = true;

            if (true === $this->Users->validateIdNumber($this->getRequest()->getData('id_number'))){
                $data['gender_id'] = $this->Users->idParts['gender_id'];
                $data['birthdate'] = $this->Users->idParts['birthdate'];
                //$data['user_status'] = 1;
                //$data['membership_number'] = $this->Users->generateMembershipNumber();
            }

            $voting_station = null;

            if ($this->getRequest()->getData('station_id')){
                $data['voting_station_id'] = $this->getRequest()->getData('station_id');
            } elseif ($this->getRequest()->getData('voting_station')){
                $voting_station = $this->getRequest()->getData('voting_station');
            }

            unset($data['station_id']);
            unset($data['voting_station']);

            // Get Province ID
            if ($this->getRequest()->getData('province')){
                $data['province_id'] = $this->Users->Provinces->provinceByCode($this->getRequest()->getData('province'));
                unset($data['province']);
            }

            if ( 4 === $this->Authentication->getIdentity()->get('role_id') ){
                $data['parent_id'] =$this->Authentication->getIdentity()->get('id');
            }

            $user = $this->Users->patchEntity($user, $data);

            if ( ! $user->hasErrors() && $valid ) {

                if ( ! $user->voting_station_id && !empty($voting_station)){
                    $user->voting_station_id = $this->Users->VotingStations->newUserStation($voting_station);
                }

                if ($this->Users->save($user)){
                    $this->Flash->success(__('Member details successfully updated.'));

                    return $this->redirect(['action' => 'view', $user->id]);
                }
            }

            $this->Flash->error(__('The details could not be updated. Please see errors below and try again.'));
        }


        $this->Users->ParentUsers->setDisplayField('name_id_number');

        $parentUsers = $this->Users->ParentUsers->find('list', [
            'fields' => [
                'ParentUsers.id',
                'ParentUsers.first_name',
                'ParentUsers.last_name',
                'ParentUsers.id_number',
            ],
            'conditions' => [
                'ParentUsers.user_status &' => 2
            ],
            'order' => ['first_name ASC'],
            'limit' => 1000
        ]);

        $provinces = $this->Users->Provinces->find('list', [
            'keyField' => 'code',
            'valueField' => 'name'
        ]);

        if (!empty($user->province_id)){
            $province = $this->Users->Provinces->find('all', [
                'fields' => ['code'],
                'conditions' => ['id' => $user->province_id]
            ]);

            if (1 === $province->count()){
                $prov = $province->first();
                $user->province_id = $prov->code;
            }
        }


        if (!empty($user->voting_station_id)){
            $vd = $this->Users->VotingStations->searchStationByTerm(['VotingStations.id' => $user->voting_station_id]);

            if (1 === $vd->count()){
                $station = $vd->first();
                $user->station =  $station->name .', '. $station->Provinces['name'] .', '. $station->Municipality['name'] .', '. $station->Areas['name'];
            }
        }

        $branches = $this->Users->Branches->find('list');

        $this->viewBuilder()->setTemplate('add');

        $this->set(compact('user', 'parentUsers', 'provinces', 'branches'));

    }

    /**
     * Authentication Edit method
     *
     * @param string|null $id User id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function accessDetails($id = null)
    {
        if ( 4 === $this->Authentication->getIdentity()->get('role_id') &&
             !$this->Users->canAccess($id, $this->Authentication->getIdentity()->get('id'))){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->get($id);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $valid = true;

            $fields = $this->getRequest()->getData();

            if( !empty($fields['change_password']) ) {
                $fields['change_password']  = trim($fields['change_password']);
                $fields['confirm_password'] = trim($fields['confirm_password']);

                if ($fields['change_password'] !== $fields['confirm_password']) {
                    $user->setErrors([
                        'change_password'       => ['Password does not match confirmation.'],
                        'confirm_password' => ['Confirmation does not match password.']
                    ]);
                    $valid = false;
                }

                if( true === $valid){
                    $fields['password'] = $fields['change_password'];
                    $fields['password_expiry'] = null;
                }
            }

            if (in_array(MEMBER_VOLUNTEER, $fields['user_status']) &&
                in_array(MEMBER_VOLUNTEER_REVIEW, $fields['user_status'])
            ){
                $this->Flash->error(__('A member cannot be a volunteer and in review at the same time. Please, select one.'));
                $valid = false;
            }

            $fields['user_status'] = multiSelectToBitMask($this->getRequest()->getData('user_status'));


            if (true === $valid &&
                MEMBER_VOLUNTEER & $fields['user_status'] &&
                empty($user->token)
            ){
                $fields['token'] = substr(uniqid(), 0, 8);
            }

            if (true === $valid && // All fields are valid
                MEMBER_ACTIVE & $fields['user_status'] && // Active member
                empty($user->membership_number)
            ){
                $fields['membership_number'] = $this->Users->generateMembershipNumberFromId($user);
            }


            $validate = ['validate'=>true];
            if (true === $valid &&
                MEMBER_VOLUNTEER & $fields['user_status'] &&
                empty($user->password)
            ){
                $fields['password'] = $user->id_number;
                $fields['password_expiry'] = date('Y-m-d H:i:s');

                $validate = ['validate'=>'DisablePasswordCheck'];
            }

            $user  = $this->Users->patchEntity($user, $fields, $validate);

            if (true===$valid && $this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));

        }

        $roles = $this->Users->Roles->find('list');
        $this->set(compact('user', 'roles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        if ( in_array($this->Authentication->getIdentity()->get('role_id'), [4, 6])) {
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function login()
    {
        $this->viewBuilder()->setLayout('gull_signin');
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/system/admin';
            return $this->redirect($target);
        }

        if ($this->getRequest()->is('post') && !$result->isValid()) {
            $this->Flash->error('Invalid username or password');
        }
    }

    public function export($type = 'csv'){
        if ( !in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2, 6]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect($this->referer('/system/admin/'));
        }

        error_reporting(0); // Suppress errors
        ini_set('memory_limit','512M');

        $members = $this->Users->filteredUsers($this->buildFilter());

        if (0 === $members->count()){
            $this->Flash->error('There are no results to export.');
            return $this->redirect($this->referer('/system/admin/'));
        }

        $this->viewBuilder()->enableAutoLayout(false);
        $this->disableAutoRender();

        $this->getResponse()->withDisabledCache();
        $this->getResponse()->withType('csv');

        $handle = fopen("php://output", "a+");

        header_remove();
        header('Content-type: text/csv');
        header('Content-disposition: attachment; filename="actionsa_'. date('Y-m-d') .'_'. uniqid() .'.csv"');
        header('Pragma:no-cache');
        header('Expires:0');

        fputcsv($handle, [
            'ID', 'Registered', 'Membership#', 'Name', 'Surname', 'FTV', 'Phone', 'Email', 'Suburb', 'Town',

            'Role',

            'Referer',
            'Token',

            'Branch',
            'Voting_Station',
            'Ward',
            'Region',
            'Municipality',
            'Province',
        ]);

        // Loop through DB results and stream data onto a file
        foreach ($members as $member) {
            $data = [
                $member->id,
                $member->created_at,
                $member->membership_number,
                $member->first_name,
                $member->last_name,
                $member->first_time_voter,
                $member->phone,
                $member->email,
                $member->suburb,
                $member->town,
                $member->has('role') ? $member->role->name : '',
                $member->has('parent_user') ? $member->parent_user->name : '',
                $member->token,
                $member->has('branch') ? $member->branch->name : '',
                $member->has('VotingStations') ? $member->VotingStations['name'] : '',
                $member->has('Wards') ? $member->Wards['name'] : '',
                $member->has('Areas') ? $member->Areas['name'] : '',
                $member->has('Municipalities') ? $member->Municipalities['name'] : '',
                $member->has('Provinces') ? $member->Provinces['name'] : '',
            ];

            fputcsv($handle, $data);
        }

        fclose($handle);

        return null;
    }

    private function buildFilter(){
        $allowedParams = [
            'q' => '', // string
            'role' => ['type' => 'int', 'field' => 'Users.role_id'], // int
            'volunteer' => ['type' => 'int', 'field' => 'Users.parent_id'], // int
        ];

        $conditions = [];

        if( !empty($this->getRequest()->getQueryParams()) ) {
            foreach ($this->getRequest()->getQueryParams() as $filter => $value) {
                if (array_key_exists($filter, $allowedParams) && '' !== $value) {

                    // General query
                    if ('q' === $filter) {
                        $conditions['OR'] = [
                            'Users.id_number'         => $value,
                            'Users.first_name LIKE'   => '%' . $value . '%',
                            'Users.last_name LIKE'    => '%' . $value . '%',
                            'Users.membership_number' => $value,
                        ];

                        continue;
                    }

                    // Booleans and Foreign Keys
                    if (is_array($allowedParams[$filter]) && 'int' === $allowedParams[$filter]['type']) {
                        $conditions[$allowedParams[$filter]['field']] = (int)$value;

                        continue;
                    }

                    // String literals
                    if (is_array($allowedParams[$filter]) && 'string' === $allowedParams[$filter]['type']) {
                        $conditions[$allowedParams[$filter]['field']] = $value;

                        continue;
                    }

                }
            }
        }

        return $conditions;
    }


    /**
     * Password retrieval method
     *
     * @return Response|null|void Redirects on success, renders view otherwise.
     */
    public function forgot()
    {
        $this->viewBuilder()->setLayout('gull_signin');

        if ($this->getRequest()->is('post')) {
            $valid = true;

            if (!$this->getRequest()->getData('membership_number') || !$this->getRequest()->getData('id_number')){
                $valid = false;
                $this->Flash->error(__('Membership number and ID Number fields are required.'));
            }

            if ($valid && false === $this->Users->validateIdNumber($this->getRequest()->getData('id_number'))){
                $valid = false;
                $this->Flash->error(__('Invalid ID Number entered. Careful now!'));
            }

            if ($valid){
                $record = $this->Users->find('all', [
                    'fields' => ['id', 'user_status'],
                    'conditions' => [
                        'membership_number' => $this->getRequest()->getData('membership_number'),
                        'id_number' => $this->getRequest()->getData('id_number'),
                    ],
                    'limit' => 1
                ]);

                if (1 === $record->count()){
                    // Save a thirteen character code if user account is active
                    $data = $record->first();

                    if ( !($data->user_status & MEMBER_ACTIVE)){
                        $valid = false;

                        $this->Flash->error(__('Your system access is revoked or membership is not active.'));

                        return $this->redirect('/');
                    }

                    $user = $this->Users->get($data->id);
                    $user->password_reset = uniqid();

                    // Let the Model handle the mailer functionality
                    if ($this->Users->save($user)){
                        $this->Flash->success(__('Password reset instructions have been sent to you.'));

                        return $this->redirect(['action' => 'resetPassword']);
                    } else {
                        $this->Flash->error(__('An unidentified error has occurred while attempting to save your reset code. Please, try again.'));
                    }
                }

                $this->Flash->error(__('No matching record found. Careful now!'));
            }
        }
    }


    public function resetPassword($password_reset = ''){
        $this->viewBuilder()->setLayout('gull_signin');

        $code = $password_reset;

        if ($this->getRequest()->is('post') ) {
            $code = $this->getRequest()->getData('password_reset');
        }
        if (!empty($code)){
            $user = $this->Users->find('all', [
                'fields' => 'id',
                'conditions' => ['password_reset' => $code],
                'limit' => 1
            ]);

            if (1 !== $user->count()){
                $this->Flash->error(__('Unknown password reset code.'));

                return $this->redirect('/');
            }
        }

        if ($this->getRequest()->is('post')) {
            $valid = true;

            $fields = $this->getRequest()->getData();

            if( !empty($fields['new_password']) ) {
                $fields['new_password']  = trim($fields['new_password']);
                $fields['confirm_password'] = trim($fields['confirm_password']);

                if ($fields['new_password'] !== $fields['confirm_password']) {
                    $this->Flash->error(__('Passwords do not match.'));
                    $valid = false;
                }

                if( true === $valid){
                    $fields['password'] = $fields['new_password'];
                }
            }

            if ($valid){
                $user = $this->Users->find('all', [
                    'fields' => 'id',
                    'conditions' => [
                        'password_reset' => $this->getRequest()->getData('password_reset'),
                        'membership_number' => $this->getRequest()->getData('membership_number')
                    ],
                    'limit' => 1
                ]);

                if (1 !== $user->count()){
                    $valid = false;

                    $this->Flash->error(__('Unknown record: password reset code and membership number do not match what is on record.'));
                }

                if ($valid){
                    $record = $user->first();
                    $user = $this->Users->get($record->id);

                    unset($fields['membership_number']);
                    unset($fields['password_reset']);

                    $user = $this->Users->patchEntity($user, $fields);
                    $user->password_expiry = null;

                    if ( ! $user->hasErrors() && $this->Users->save($user)) {
                        $this->Flash->success(__('Please login with your new password.'));

                        return $this->redirect(['action' => 'login']);
                    }

                    $this->Flash->error(__('An error has occurred. Ensure that the password confirms to our password policy.'));
                }
            }

        }

        $this->set(compact('password_reset'));
    }


    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }


    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['resetPassword', 'login', 'logout', 'forgot']);
        $this->FormProtection->setConfig('unlockedFields', ['station_id']);

        if (in_array($this->getAction(), ['add'])){
            $this->getEventManager()->off($this->Csrf);
        }
    }
}
