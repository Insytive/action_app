<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use App\Model\Table\BranchesTable;
use App\Model\Table\UsersTable;
use App\Model\Table\VotingStationsTable;
use Authentication\AuthenticationService;
use Cake\Http\Response;

/**
 * Branches Controller
 *
 * @property BranchesTable $Branches
 * @property UsersTable $Users
 * @property VotingStationsTable $VotingStations
 * @property AuthenticationService $Authentication
 **/
class StatisticsController extends SystemAdminController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function members()
    {
        if (!in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2, 6])) {
            return $this->redirect(['controller' => 'Users', 'action' => 'view', $this->Authentication->getIdentity()->get('id')]);
        }

        $this->loadModel('Users');
        $this->loadModel('Branches');
        $this->loadModel('VotingStations');

        $user_conditions = [];
        $provinces_conditions = [];

        // Admin type stats
        if (4 === $this->Authentication->getIdentity()->get('role_id')) {
            $user_conditions = [
                'conditions' => ['Users.parent_id' => $this->Authentication->getIdentity()->get('id')]
            ];

            $provinces_conditions = ['Users.parent_id' => $this->Authentication->getIdentity()->get('id')];
        }

        $members = $this->Users->find('all', $user_conditions)->count();
        $stations = $this->VotingStations->find()->count();

        $provinces = $this->Users->membersByProvince($provinces_conditions);

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

        $this->set('breadcrumb', [
            'Statistics',
            'Members' => ['link' => '/system/admin/statistics/members'],
            'KZN',
            'eDumbe',
            'Ward 10']);

        $this->set(compact('members', 'stations', 'membersByProvince', 'membersByProvinceColours'));
    }
}
