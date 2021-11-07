<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

/**
 * Branches Controller
 *
 * @property \App\Model\Table\BranchesTable $Branches
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\VotingStationsTable $VotingStations
 **/
class DashboardController extends SystemAdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        if ( 4 === $this->Authentication->getIdentity()->get('role_id') && !(2 & $this->Authentication->getIdentity()->get('user_status'))){
            return $this->redirect(['controller'=>'Users', 'action'=>'view', $this->Authentication->getIdentity()->get('id')]);
        }

        $this->loadModel('Users');
        $this->loadModel('Branches');
        $this->loadModel('VotingStations');

        $monthlySelf = [];
        $user_conditions = [];
        $branches_conditions = [];
        $provinces_conditions = [];
        $monthly_self_conditions = ['Users.parent_id IS' => null];
        $monthly_volunteers_conditions = ['Users.parent_id IS NOT' => null];

        // Admin type stats
        $volunteers_conditions = ['conditions' => ['Users.user_status & 2']];
        $supporters_conditions = ['conditions' => ['Users.user_status & 4']];
        $volunteers_review_conditions = ['conditions' => ['Users.user_status & ' . MEMBER_VOLUNTEER_REVIEW]];

        if ( 4 === $this->Authentication->getIdentity()->get('role_id') ){
            $user_conditions = [
                'conditions' => ['Users.parent_id' => $this->Authentication->getIdentity()->get('id')]
            ];

            $branches_conditions = [
                'conditions' => ['Branches.id' => 0]
            ];

            $monthly_self_conditions = [
                'conditions' => ['Users.id' => 0]
            ];

            $monthly_volunteers_conditions = ['Users.parent_id' => $this->Authentication->getIdentity()->get('id')];

            $provinces_conditions = ['Users.parent_id' => $this->Authentication->getIdentity()->get('id')];
        }

        $members = $this->Users->find('all', $user_conditions)->count();
        $branches = $this->Branches->find('all', $branches_conditions)->count();
        $stations = $this->VotingStations->find('all')->count();

        $volunteers = $this->Users->find('all', $volunteers_conditions)->count();
        $supporters = $this->Users->find('all', $supporters_conditions)->count();
        $volunteers_review = $this->Users->find('all', $volunteers_review_conditions)->count();

        if ( 4 !== $this->Authentication->getIdentity()->get('role_id') ) {
            $query       = $this->Users->find();
            $monthlySelf = $query
                ->select([
                    'displayMonth' => $query->func()->date_format([
                        'created_at' => 'identifier',
                        "'%b-%y'"    => 'literal'
                    ]),
                    'total'        => $query->func()->count('Users.id')
                ])
                ->where($monthly_self_conditions)
                ->group(['displayMonth'])
                ->order(['displayMonth' => 'DESC'])
                ->limit(12)
                ->toArray();
        }

        $query = $this->Users->find();
        $monthlyVolunteers = $query
            ->select([
                'displayMonth' => $query->func()->date_format([
                    'created_at' => 'identifier',
                    "'%b-%y'" => 'literal'
                ]),
                'total' => $query->func()->count('Users.id')])
            ->where($monthly_volunteers_conditions)
            ->group(['displayMonth'])
            ->order(['displayMonth' => 'DESC'])
            ->limit(12);

        $monthlySelfRegistered = [];
        foreach ($monthlySelf as $item) {
            $monthlySelfRegistered[$item->displayMonth] = $item->total;
        }

        $monthlyVolunteerRegistered = [];
        foreach ($monthlyVolunteers as $item) {
            $monthlyVolunteerRegistered[$item->displayMonth] = $item->total;
        }

        $months = array_merge(array_keys($monthlySelfRegistered), array_keys($monthlyVolunteerRegistered));
        sort($months);

        $monthly_self = [];
        $monthly_referred = [];
        foreach ($months as $month){
            $monthly_referred[$month] = (!array_key_exists($month, $monthlyVolunteerRegistered)) ? 0 : $monthlyVolunteerRegistered[$month];
            $monthly_self[$month] = (!array_key_exists($month, $monthlySelfRegistered)) ? 0 : $monthlySelfRegistered[$month];
        }

        $months = null;
        $monthlySelfRegistered = null;
        $monthlyVolunteerRegistered = null;

        $maxSelf = (empty($monthly_self)) ? 0 : max($monthly_self);
        $maxRef = (empty($monthly_referred)) ? 0 : max($monthly_referred);
        $max = (int) (500 * ceil(max($maxSelf, $maxRef) / 500));

        $query = $this->Users->find();
        $provinces = $query
            ->select(['Provinces.id', 'Provinces.name', 'total' => $query->func()->count('Provinces.id')])
            ->join([
                [
                    'table' => 'voting_stations',
                    'alias' => 'VotingStations',
                    'type'  => 'INNER',
                    'conditions' => 'Users.voting_station_id=VotingStations.id'
                ],
                [
                    'table' => 'wards',
                    'alias' => 'Wards',
                    'type'  => 'INNER',
                    'conditions' => 'VotingStations.ward_id=Wards.id'
                ],
                [
                    'table' => 'areas',
                    'alias' => 'Areas',
                    'type'  => 'INNER',
                    'conditions' => 'Wards.area_id=Areas.id'
                ],
                [
                    'table' => 'municipalities',
                    'alias' => 'Municipalities',
                    'type'  => 'INNER',
                    'conditions' => 'Municipalities.id=Areas.municipality_id'
                ],
                [
                    'table' => 'provinces',
                    'alias' => 'Provinces',
                    'type'  => 'INNER',
                    'conditions' => 'Municipalities.province_id=Provinces.id'
                ]
            ])
            ->where($provinces_conditions)
            ->group(['Provinces.id']);

        $provinceColours = [
            1 => '#7afa4b',
            2 => '#669933',
            3 => '#4a9d19',
            4 => '#6adc22',
            5 => '#99cc66',
            6 => '#336633',
            7 => '#3faf00',
            8 => '#ccff99',
            9 => '#66cc66',
        ];
        $membersByProvince = [];
        $membersByProvinceColours = [];
        foreach ($provinces as $province) {
            $membersByProvinceColours[] = $provinceColours[$province->Provinces['id']];
            $membersByProvince[] = [
                'value' => $province->total,
                'name' => $province->Provinces['name']
            ];
        }

        $this->set(compact('members', 'branches', 'stations', 'membersByProvince', 'membersByProvinceColours', 'monthly_referred', 'monthly_self', 'max', 'volunteers', 'volunteers_review', 'supporters'));
    }
}
