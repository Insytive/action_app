<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);

        $this->FormProtection->setConfig('unlockedFields', ['station_id']);

        if (in_array($this->getAction(), ['register'])){
            $this->getEventManager()->off($this->Csrf);
        }
    }


    /**
     * Member/Volunteer membership number retrieval method
     *
     * @return void Renders view.
     */
    public function myaction()
    {
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setTemplate('take_action');
    }

    /**
     * Member/Volunteer membership card retrieval method
     *
     * @return Response|void Renders view.
     */
    public function myMembershipCard()
    {
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setTemplate('membership_card');

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();

            if (false === $this->Users->validateIdNumber($data['id_number'])) {
                $this->Flash->error(__('Invalid ID number. Please, try again.'));
                return;
            }

            $data['phone'] = trim($data['phone']);
            $data['phone'] = preg_replace('/\D/', '', $data['phone']);

            $user = $this->Users->find('all', [
                'contain'    => [],
                'fields'     => ['id', 'first_name', 'last_name', 'membership_number', 'created_at', 'user_status'],
                'conditions' => [
                    'Users.id_number'         => $data['id_number'],
                    'Users.membership_number' => $data['membership_number'],
                    'Users.phone'             => $data['phone'],
                ],
                'limit' => 1
            ]);

            if (1 === $user->count()){
                $user = $user->first();

                if (1 & $user->user_status){
                    $this->set(compact('user'));
                    $this->viewBuilder()->setTemplate('my_membership_card');
                    return;
                }

                $this->Flash->error(__('Your membership is currently not active. You cannot access the card.'));
                return;
            }

            $this->Flash->error(__('Invalid membership credentials. Please, try again or email updateinfo@actionsa.app for support.'));
        }
    }

    /**
     * Member registration method
     *
     * @param string $type Membership type
     * @param string|null $token Referer code
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function register($type='member', $token=null)
    {
        if ($this->getRequest()->getParam('token')){
            $token = $this->getRequest()->getParam('token');
        }

        if ($this->getRequest()->getQuery('type')){
            $type = $this->getRequest()->getQuery('type');
        }

        $types = [
            'supporter',
            'member',
            'volunteer'
        ];

        if ( !in_array($type, $types) ){
            $this->Flash->error(__('Unrecognized registration type. Please, try again.'));
            return $this->redirect('/');
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

                // Default Password = Member's ID Number
                // $data['id_number'] = $this->getRequest()->getData('id_number');
            }

            if (1 ==  $this->getRequest()->getData('volunteer')){
                $data['user_status'] += MEMBER_VOLUNTEER_REVIEW;
                unset($data['volunteer']);
            }

            if ('supporter' == $type){
                $data['user_status'] += MEMBER_SUPPORTER;
            }

            if ($this->getRequest()->getData('address')){
                $data['street_address'] = $this->getRequest()->getData('address');
                unset($data['address']);
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

            // Get Parent User ID
            if ($token){
                $data['parent_id'] = $this->Users->userIdByToken($token);
            }

            $user = $this->Users->patchEntity($user, $data);

            if ( 'supporter' != $type && ! $this->getRequest()->getData('constitution')){
                $user->setError('constitution', 'You must accept ActionSA policies and constitution.');
                $this->Flash->error('You must read/download ActionSA policies and constitution to be registered.');

                $valid = false;
            }

            if (! $user->hasErrors() && $valid ) {

                if ( ! $user->voting_station_id && !empty($voting_station)){
                    $user->voting_station_id = $this->Users->VotingStations->newUserStation($voting_station);
                }

                if ($this->Users->save($user)){
                    $this->Users->updateMembershipNumber($user);

                    $this->Flash->success(__('You have been successfully registered.'));

                    return $this->redirect('/');
                }
            }

            $this->Flash->error(__('The details could not be saved. Please, look at errors below and try again.'));
        }

        $provinces = $this->Users->Provinces->find('list', [
            'keyField' => 'code',
            'valueField' => 'name'
        ]);

        $this->set(compact('user', 'provinces', 'type'));
    }

    public function thankYou(){
        $this->viewBuilder()->enableAutoLayout(false);
    }


    public function validateSaIdNumber($identity_number, $volunteer = false){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->enableAutoLayout(false);
        $this->disableAutoRender();

        $volunteer = ('true' === (string)($volunteer)) ? true : false;

        $result = [
            'status' => -1,
            'redirect' => -1,
            'message' => 'Unknown error has occurred',
            'data' => []
        ];

        if (empty($this->getRequest()->referer(false)) ||
            parse_url($this->getRequest()->referer(false), PHP_URL_HOST) !== $this->getRequest()->host()){

            $this->disableAutoRender();

            $result['message'] = 'Your host/IP address is not permitted to access this resource.';
            $result['data'] = ['warning' => 'Continued attempts to access this resource will have your IP address blocked at the firewall level.'];

            $this->setResponse(new Response([
                'type'=>'json',
                'body' => json_encode($result)
            ]));

            return null;
        }

        $validation = $this->Users->validateIdNumber($identity_number);

        if (true === $validation){
            $status   = 1;
            $message  = 'Valid SA Identity number.';
            $redirect = -1;

            // Does the user already exist
            $user = $this->Users->find('all', [
                'fields' => ['Users.id', 'Users.user_status'],
                'conditions' => ['Users.id_number' => $identity_number],
                'limit' => 1
            ]);

            if (1 === $user->count()){
                $member = $user->first();

                if ($volunteer){
                    if (MEMBER_VOLUNTEER_REVIEW & $member->user_status || MEMBER_VOLUNTEER & $member->user_status){
                        $message .= ' You are already registered as a volunteer or your application is being considered.';
                    } else {
                        $data = [
                            'user_status' => $member->user_status + MEMBER_VOLUNTEER_REVIEW
                        ];

                        $user = $this->Users->patchEntity($member, $data);

                        if ($this->Users->save($user)) {
                            $message .= ' Your account is in review for volunteer opportunities.';
                        }
                    }
                } else {
                    $status = -1;
                    $message .= ' Already registered.';
                }

                $redirect = 1;
            }

            $result = [
                'status'   => $status,
                'message'  => $message,
                'redirect' => $redirect,
                'data'     => $this->Users->idParts
            ];
        } else {
            $result['message'] = 'Invalid South African ID number.';
        }

        $this->setResponse(new Response([
            'type'=>'json',
            'body' => json_encode($result)
        ]));

        return null;
    }


    public function votingStations($term)
    {
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->enableAutoLayout(false);

        if (empty($this->getRequest()->referer(false)) ||
            parse_url($this->getRequest()->referer(false), PHP_URL_HOST) !== $this->getRequest()->host()){

            $this->disableAutoRender();

            $result = [
                'status' => -1,
                'message' => 'Your host/IP address is not permitted to access this resource.',
                'data' => [
                    'warning' => 'Continued attempts to access this resource will have your IP address blocked at the firewall level.'
                ]
            ];

            $this->setResponse(new Response([
                'type'=>'json',
                'body' => json_encode($result)
            ]));

            return null;
        }

        $stations = $this->Users->VotingStations->searchStationByTerm([
            'approved' => 1,
            'VotingStations.name LIKE' => "%{$term}%"
        ]);

        $this->set(compact('stations', 'term'));
    }


    /**
     * @param mixed $id_number South African ID number
     *
     * @return \Cake\Http\Response|null|void Generally returns a JSON string but can also return other types
     */
    public function myMembershipNumber($id_number){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->enableAutoLayout(false);
        $this->disableAutoRender();

        if (empty($this->getRequest()->referer(false)) ||
            parse_url($this->getRequest()->referer(false), PHP_URL_HOST) !== $this->getRequest()->host()){

            $result = [
                'status' => -1,
                'message' => 'Your host/IP address is not permitted to access this resource.',
                'data' => [
                    'notice' => 'Continued attempts to access this resource will have your IP address blocked at the firewall level.'
                ]
            ];

            $this->setResponse(new Response([
                'type'=>'json',
                'body' => json_encode($result)
            ]));

            return null;
        }


        $id_number = $id_number;
        if (false === $this->Users->validateIdNumber($id_number)){
            $result = [
                'status' => -1,
                'message' => 'The provided identifier is not a South African ID number.',
                'data' => [
                    'notice' => 'Be advised that successive failed attempts to access this resource will have your IP address blocked at the firewall level.'
                ]
            ];

            $this->setResponse(new Response([
                'type'=>'json',
                'body' => json_encode($result)
            ]));

            return null;
        }

        $user = $this->Users->find('all', [
            'fields' => ['membership_number'],
            'conditions' => [
                'Users.id_number' => $id_number,
                'Users.user_status &' => MEMBER_ACTIVE,
                'NOT' => [
                    'Users.user_status &' => MEMBER_SUPPORTER
                ]
            ],
            'limit' => 1
        ]);

        if (0 === $user->count()){
            $result = [
                'status' => -1,
                'message' => 'Invalid record. The ID Number you provided does not have a membership number associated with it.',
                'data' => [
                    'notice' => 'Be advised that successive failed attempts to access this resource will have your IP address blocked at the firewall level.'
                ]
            ];

            $this->setResponse(new Response([
                'type'=>'json',
                'body' => json_encode($result)
            ]));

            return null;
        }

        $record = $user->first();
        $result = [
            'status' => 1,
            'message' => $record->membership_number,
            'data' => [
                'notice' => 'Successfully retrieved your membership number.'
            ]
        ];

        $this->setResponse(new Response([
            'type'=>'json',
            'body' => json_encode($result)
        ]));

        return null;
    }


    public function membershipCard($id, $membership_number){
        $user = $this->Users->find('all', [
            'conditions' => [
                'id' => $id,
                'membership_number' => $membership_number,
            ],
            'contain' => ['ParentUsers', 'Genders', 'VotingStations', 'Branches', 'Roles']
        ]);

        $this->set(compact('user'));
    }

}
