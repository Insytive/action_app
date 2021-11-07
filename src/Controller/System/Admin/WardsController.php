<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use Cake\Event\EventInterface;

/**
 * Wards Controller
 *
 * @property \App\Model\Table\WardsTable $Wards
 * @method \App\Model\Entity\Ward[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WardsController extends SystemAdminController
{
    public function initialize(): void {
        parent::initialize();

        $this->set('breadcrumb', ['Options', 'Locations']);
    }

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);

        if ($this->Authentication->getIdentity() && !in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2, 3]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['controller'=>'Dashboard', 'action'=>'index']);
        }

        $this->FormProtection->setConfig('unlockedFields', ['_area_id']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = $this->Wards->setSqlStatement();

        $wards = $this->paginate($this->Wards);

        $this->set(compact('wards'));
    }

    /**
     * View method
     *
     * @param string|null $id Ward id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ward = $this->Wards->get($id, [
            'contain' => ['Areas', 'VotingStations'],
        ]);

        $this->set(compact('ward'));

        $this->set('breadcrumb', ['Options', 'Locations', 'Wards'=>['link' => '/system/admin/wards']]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ward = $this->Wards->newEmptyEntity();

        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();
            $data['area_id'] = $this->getRequest()->getData('_area_id');

            unset($data['_area_id']);

            $ward = $this->Wards->patchEntity($ward, $data);

            if ($this->Wards->save($ward)) {
                $this->Flash->success(__('The ward has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The ward could not be saved. Please, try again.'));
        }

        $this->set(compact('ward'));

        $this->set('breadcrumb', ['Options', 'Locations', 'Wards'=>['link' => '/system/admin/wards']]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ward id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ward = $this->Wards->get($id, [
            'contain' => [],
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $ward = $this->Wards->patchEntity($ward, $this->getRequest()->getData());
            if ($this->Wards->save($ward)) {
                $this->Flash->success(__('The ward has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ward could not be saved. Please, try again.'));
        }

        $this->set(compact('ward'));

        $this->set('breadcrumb', ['Options', 'Locations', 'Wards'=>['link' => '/system/admin/wards']]);

        $this->viewBuilder()->setTemplate('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ward id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        if (!in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['controller'=>'Dashboard', 'action'=>'index']);
        }

        $ward = $this->Wards->get($id);
        if ($this->Wards->delete($ward)) {
            $this->Flash->success(__('The ward has been deleted.'));
        } else {
            $this->Flash->error(__('The ward could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function advancedSearch(){
        $conditions = [];

        if ($this->getRequest()->getQuery('p')){
            $conditions['Provinces.id'] = $this->getRequest()->getQuery('p');
        }

        if ($this->getRequest()->getQuery('m')){
            $conditions['Municipality.id'] = $this->getRequest()->getQuery('m');
        }

        if ($this->getRequest()->getQuery('a')){
            $conditions['Areas.id'] = $this->getRequest()->getQuery('a');
        }

        $this->paginate = $this->Wards->setSqlStatement($conditions);

        $wards = $this->paginate($this->Wards);

        $this->set(compact('wards'));

        $this->viewBuilder()->setTemplate('index');
    }

}
