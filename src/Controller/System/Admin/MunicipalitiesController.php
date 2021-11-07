<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Municipalities Controller
 *
 * @property \App\Model\Table\MunicipalitiesTable $Municipalities
 * @method \App\Model\Entity\Municipality[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MunicipalitiesController extends SystemAdminController
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
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Provinces'],
        ];
        $municipalities = $this->paginate($this->Municipalities);

        $this->set(compact('municipalities'));
    }

    /**
     * View method
     *
     * @param string|null $id Municipality id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $municipality = $this->Municipalities->get($id, [
            'contain' => ['Provinces', 'Areas'],
        ]);

        $this->set(compact('municipality'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $municipality = $this->Municipalities->newEmptyEntity();
        if ($this->request->is('post')) {
            $municipality = $this->Municipalities->patchEntity($municipality, $this->request->getData());
            if ($this->Municipalities->save($municipality)) {
                $this->Flash->success(__('The municipality has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The municipality could not be saved. Please, try again.'));
        }
        $provinces = $this->Municipalities->Provinces->find('list', ['limit' => 200]);
        $this->set(compact('municipality', 'provinces'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Municipality id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $municipality = $this->Municipalities->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $municipality = $this->Municipalities->patchEntity($municipality, $this->request->getData());
            if ($this->Municipalities->save($municipality)) {
                $this->Flash->success(__('The municipality has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The municipality could not be saved. Please, try again.'));
        }
        $provinces = $this->Municipalities->Provinces->find('list', ['limit' => 200]);
        $this->set(compact('municipality', 'provinces'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Municipality id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!in_array($this->Authentication->getIdentity()->get('role_id'), [1, 2]) ){
            $this->Flash->error('You are not authorised to access that resource.');

            return $this->redirect(['controller'=>'Dashboard', 'action'=>'index']);
        }

        $municipality = $this->Municipalities->get($id);

        if ($this->Municipalities->delete($municipality)) {
            $this->Flash->success(__('The municipality has been deleted.'));
        } else {
            $this->Flash->error(__('The municipality could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function autocomplete($term)
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

        $conditions = ['Municipalities.name LIKE' => "%{$term}%"];

        if ($this->getRequest()->getQuery('p')){
            $conditions['province_id'] = $this->getRequest()->getQuery('p');
        }

        $municipalities = $this->Municipalities->find('all', [
            'fields' => [
                'Municipalities.id',
                'Municipalities.name',
            ],
            'conditions' => $conditions,
            'order' => ['Municipalities.name ASC']
        ]);

        $this->set(compact('municipalities', 'term'));
    }

}
