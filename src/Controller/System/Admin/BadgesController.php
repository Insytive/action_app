<?php
declare(strict_types=1);

namespace App\Controller\System\Admin;

use Cake\Event\EventInterface;

/**
 * Badges Controller
 *
 * @property \App\Model\Table\BadgesTable $Badges
 * @method \App\Model\Entity\Badge[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BadgesController extends SystemAdminController
{
    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);

        if ( 4 === $this->Authentication->getIdentity()->get('role_id') ){
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
        $badges = $this->paginate($this->Badges);

        $this->set(compact('badges'));
    }

    /**
     * View method
     *
     * @param string|null $id Badge id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $badge = $this->Badges->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('badge'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $badge = $this->Badges->newEmptyEntity();
        if ($this->request->is('post')) {
            $badge = $this->Badges->patchEntity($badge, $this->request->getData());
            if ($this->Badges->save($badge)) {
                $this->Flash->success(__('The badge has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The badge could not be saved. Please, try again.'));
        }
        $users = $this->Badges->Users->find('list', ['limit' => 200]);
        $this->set(compact('badge', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Badge id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $badge = $this->Badges->get($id, [
            'contain' => ['Users'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $badge = $this->Badges->patchEntity($badge, $this->request->getData());
            if ($this->Badges->save($badge)) {
                $this->Flash->success(__('The badge has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The badge could not be saved. Please, try again.'));
        }
        $users = $this->Badges->Users->find('list', ['limit' => 200]);
        $this->set(compact('badge', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Badge id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $badge = $this->Badges->get($id);
        if ($this->Badges->delete($badge)) {
            $this->Flash->success(__('The badge has been deleted.'));
        } else {
            $this->Flash->error(__('The badge could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
