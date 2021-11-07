<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use Cake\Event\EventInterface;

/**
 * VotingStations Controller
 *
 * @property \App\Model\Table\VotingStationsTable $VotingStations
 * @method \App\Model\Entity\VotingStation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VotingStationsController extends SystemAdminController
{
    public function initialize(): void {
        parent::initialize();

        $this->set('breadcrumb', ['Options', 'Locations']);
    }

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);

        if ( $this->Authentication->getIdentity() && !in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2, 3]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['controller'=>'Dashboard', 'action'=>'index']);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'fields' => [
                'VotingStations.id',
                'VotingStations.name',
                'VotingStations.ward_id',
                'VotingStations.approved',

                'Ward.id',
                'Ward.name',

                'Areas.name',

                'Municipality.name',

                'Provinces.code',
            ],

            'join' => [
                [
                    'table' => 'wards',
                    'alias' => 'Ward',
                    'conditions' => 'VotingStations.ward_id = Ward.id',
                ],
                [
                    'table' => 'areas',
                    'alias' => 'Areas',
                    'conditions' => 'Ward.area_id = Areas.id',
                ],
                [
                    'table' => 'municipalities',
                    'alias' => 'Municipality',
                    'conditions' => 'Areas.municipality_id = Municipality.id',
                ],
                [
                    'table' => 'provinces',
                    'alias' => 'Provinces',
                    'conditions' => 'Municipality.province_id = Provinces.id',
                ],
            ],
        ];


        $votingStations = $this->paginate($this->VotingStations);

        $this->set(compact('votingStations'));
    }

    /**
     * View method
     *
     * @param string|null $id Voting Station id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $votingStation = $this->VotingStations->get($id, [
            'contain' => ['Wards', 'Users'],
        ]);

        $this->set(compact('votingStation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($ward_id = null)
    {
        $votingStation = $this->VotingStations->newEmptyEntity();

        if ($this->request->is('post')) {

            $votingStation = $this->VotingStations->patchEntity($votingStation, $this->request->getData());

            if ($this->VotingStations->save($votingStation)) {
                $this->Flash->success(__('The voting station has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The voting station could not be saved. Please, try again.'));
        }

        $conditions = [];

        if ( !empty($ward_id) ){
            $conditions['id'] = $ward_id;
        }

        $wards = $this->VotingStations->Wards->find('list', [
            'conditions' => $conditions
        ]);

        $this->set(compact('votingStation', 'wards'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Voting Station id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $votingStation = $this->VotingStations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $votingStation = $this->VotingStations->patchEntity($votingStation, $this->request->getData());
            if ($this->VotingStations->save($votingStation)) {
                $this->Flash->success(__('The voting station has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The voting station could not be saved. Please, try again.'));
        }
        $wards = $this->VotingStations->Wards->find('list', ['limit' => 200]);
        $this->set(compact('votingStation', 'wards'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Voting Station id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if ( !in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['controller'=>'Dashboard', 'action'=>'index']);
        }

        $votingStation = $this->VotingStations->get($id);

        if ($this->VotingStations->delete($votingStation)) {
            $this->Flash->success(__('The voting station has been deleted.'));
        } else {
            $this->Flash->error(__('The voting station could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
