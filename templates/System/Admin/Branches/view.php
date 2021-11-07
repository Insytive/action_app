<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Branch $branch
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Branch'), ['action' => 'edit', $branch->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Branch'), ['action' => 'delete', $branch->id], ['confirm' => __('Are you sure you want to delete # {0}?', $branch->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Branches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Branch'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="branches view content">
            <h3><?= h($branch->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($branch->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($branch->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($branch->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($branch->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Building') ?></th>
                    <td><?= h($branch->building) ?></td>
                </tr>
                <tr>
                    <th><?= __('City') ?></th>
                    <td><?= h($branch->city) ?></td>
                </tr>
                <tr>
                    <th><?= __('Area') ?></th>
                    <td><?= $branch->has('area') ? $this->Html->link($branch->area->name, ['controller' => 'Areas', 'action' => 'view', $branch->area->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($branch->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Post Code') ?></th>
                    <td><?= $this->Number->format($branch->post_code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($branch->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Updated At') ?></th>
                    <td><?= h($branch->updated_at) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Users') ?></h4>
                <?php if (!empty($branch->users)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('First Name') ?></th>
                            <th><?= __('Last Name') ?></th>
                            <th><?= __('Id Number') ?></th>
                            <th><?= __('Birthdate') ?></th>
                            <th><?= __('Membership Number') ?></th>
                            <th><?= __('Gender Id') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Password') ?></th>
                            <th><?= __('Phone') ?></th>
                            <th><?= __('Address') ?></th>
                            <th><?= __('Building') ?></th>
                            <th><?= __('Town') ?></th>
                            <th><?= __('City') ?></th>
                            <th><?= __('First Time Voter') ?></th>
                            <th><?= __('User Status') ?></th>
                            <th><?= __('Voting Station Id') ?></th>
                            <th><?= __('Branch Id') ?></th>
                            <th><?= __('Role Id') ?></th>
                            <th><?= __('Token') ?></th>
                            <th><?= __('Points') ?></th>
                            <th><?= __('Created At') ?></th>
                            <th><?= __('Updated At') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($branch->users as $users) : ?>
                        <tr>
                            <td><?= h($users->id) ?></td>
                            <td><?= h($users->parent_id) ?></td>
                            <td><?= h($users->first_name) ?></td>
                            <td><?= h($users->last_name) ?></td>
                            <td><?= h($users->id_number) ?></td>
                            <td><?= h($users->birthdate) ?></td>
                            <td><?= h($users->membership_number) ?></td>
                            <td><?= h($users->gender_id) ?></td>
                            <td><?= h($users->email) ?></td>
                            <td><?= h($users->password) ?></td>
                            <td><?= h($users->phone) ?></td>
                            <td><?= h($users->address) ?></td>
                            <td><?= h($users->building) ?></td>
                            <td><?= h($users->town) ?></td>
                            <td><?= h($users->city) ?></td>
                            <td><?= h($users->first_time_voter) ?></td>
                            <td><?= h($users->user_status) ?></td>
                            <td><?= h($users->voting_station_id) ?></td>
                            <td><?= h($users->branch_id) ?></td>
                            <td><?= h($users->role_id) ?></td>
                            <td><?= h($users->token) ?></td>
                            <td><?= h($users->points) ?></td>
                            <td><?= h($users->created_at) ?></td>
                            <td><?= h($users->updated_at) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
