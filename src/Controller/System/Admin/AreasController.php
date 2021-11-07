<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Areas Controller
 *
 * @property \App\Model\Table\AreasTable $Areas
 * @method \App\Model\Entity\Area[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AreasController extends SystemAdminController
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
            'contain' => ['Municipalities'],
        ];
        $areas = $this->paginate($this->Areas);

        $this->set(compact('areas'));
    }

    /**
     * View method
     *
     * @param string|null $id Area id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $area = $this->Areas->get($id, [
            'contain' => ['Municipalities', 'Branches', 'Wards'],
        ]);

        $this->set(compact('area'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $area = $this->Areas->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $area = $this->Areas->patchEntity($area, $this->getRequest()->getData());
            if ($this->Areas->save($area)) {
                $this->Flash->success(__('The area has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The area could not be saved. Please, try again.'));
        }
        $municipalities = $this->Areas->Municipalities->find('list', ['limit' => 200]);
        $this->set(compact('area', 'municipalities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Area id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $area = $this->Areas->get($id, [
            'contain' => [],
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $area = $this->Areas->patchEntity($area, $this->getRequest()->getData());
            if ($this->Areas->save($area)) {
                $this->Flash->success(__('The area has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The area could not be saved. Please, try again.'));
        }
        $municipalities = $this->Areas->Municipalities->find('list', ['limit' => 200]);
        $this->set(compact('area', 'municipalities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Area id.
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

        $area = $this->Areas->get($id);
        if ($this->Areas->delete($area)) {
            $this->Flash->success(__('The area has been deleted.'));
        } else {
            $this->Flash->error(__('The area could not be deleted. Please, try again.'));
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

        $conditions = [
            'Areas.name LIKE' => "%{$term}%",
            'Municipalities.name LIKE' => "%{$term}%",
        ];

        if ($this->getRequest()->getQuery('m')){
            $conditions['Areas.municipality_id'] = $this->getRequest()->getQuery('m');
        }

        $areas = $this->Areas->find('all', [
            'fields' => [
                'Areas.id',
                'Areas.name',
                'Municipalities.name',
            ],
            'contain' => ['Municipalities'],
            'conditions' => $conditions,
            'order' => ['Municipalities.name ASC', 'Areas.name ASC']
        ]);

        $this->set(compact('areas', 'term'));
    }
}
