<?php
    /**
     * @var \App\View\AppView      $this
     * @var \App\Model\Entity\User $user
     * @var bool|null $display_membership_card
     */

    $this->assign('title', $user->name);
?>

<?php $this->start('page_options'); ?>
<div class="dropdown dropleft ml-auto">
    <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaPageOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="asaPageOptions">
        <?= $this->Html->link(__('Edit Details'), ['action' => 'edit', $user->id],
            ['class' => 'dropdown-item']) ?>

        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
        <?= $this->Html->link(__('Access Credentials'), ['action' => 'accessDetails', $user->id],
            ['class' => 'dropdown-item']) ?>
        <?php endif; ?>
        
        <?php if (4 === $this->Identity->get('role_id') && 2 & $user->user_status): ?>
            <?= $this->Html->link(__('View Leads'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
        <?php elseif (in_array($this->Identity->get('role_id'), [1, 2, 3]) && 2 & $user->user_status): ?>
            <?= $this->Html->link(__('View Leads'), ['action' => 'index', '?' =>['volunteer' =>$user->id]], ['class' => 'dropdown-item']) ?>
        <?php endif; ?>
        <div class="dropdown-divider"></div>
        <a href="javascript:window.print()" class="dropdown-item">Print Membership Card</a>
        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
            <div class="dropdown-divider"></div>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                [
                    'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                    'class'   => 'dropdown-item text-mute',
                ]
            ) ?>
        <?php endif; ?>
    </div>
</div>
<?php $this->end(); ?>

<div class="row">
<?php
    $col_length = 12;
    if (true === $display_membership_card && in_array($user->user_status, [1, 3])):
        $col_length = 8;
?>
    <div class="col-md-4" id="print-area">
        <?= $this->element('membership_card') ?>
    </div>
<?php endif; ?>

    <div class="col-md-<?= $col_length ?>">
        <div class="card">
            <div class="card-header d-flex align-items-center border-0 pb-0">
                <div class="w-100">
                <ul class="nav nav-tabs profile-nav" id="profileTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" role="tab"
                           aria-controls="about" aria-selected="true">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="access-tab" data-toggle="tab" href="#access" role="tab"
                           aria-controls="access" aria-selected="false">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab"
                           aria-controls="logs" aria-selected="false">Activity Log</a></li>
                    <li class="nav-item">
                        <a class="nav-link" id="badges-tab" data-toggle="tab" href="#badges" role="tab"
                           aria-controls="badges" aria-selected="false">Badges</a></li>
                    <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                    <li class="nav-item">
                        <a class="nav-link" id="stats-tab" data-toggle="tab" href="#statistics" role="tab"
                           aria-controls="statistics" aria-selected="false">Statistics</a></li>
                    <?php endif; ?>
                </ul>
                </div>
            </div>

            <div class="card-body">


                <div class="tab-content" id="profileTabContent">
                    <div class="tab-pane fade active show" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Name</p>
                                    <span><?= h($user->name) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Email</p>
                                    <span><?= h($user->email) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Street Address</p>
                                    <span><?= h($user->street_address) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Municipality</p>
                                    <span><?= $user->municipality; ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">ID Number</p>
                                    <?php
                                        $id_number = '';

                                        if (!empty($user->id_number) && 13 === strlen($user->id_number)){
                                            $id_number = substr($user->id_number, 0, 6) . str_repeat('*', 7);
                                        }
                                    ?>
                                    <span><?= h($id_number) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Phone</p>
                                    <span><?= h($user->phone) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Suburb</p>
                                    <span><?= h($user->suburb) ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Birth Date</p>
                                    <span><?= h($user->birthdate) ?> &nbsp;</span>
                                </div>

                                <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Gender</p>
                                    <span><?= $user->has('gender') ? $this->Html->link($user->gender->name, [
                                            'controller' => 'Genders',
                                            'action'     => 'view',
                                            $user->gender->id,
                                        ]) : '' ?> &nbsp;</span>
                                </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <p class="text-primary mb-1">Town/City</p>
                                    <span><?= h($user->town) ?>&nbsp;</span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Province</p>
                                    <span><?= $user->has('province') ? $user->province->name : 'Not Available' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="access" role="tabpanel" aria-labelledby="access-tab">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Membership Number</p>
                                    <span><?= (1 === $user->user_status || 3 === $user->user_status) ? h($user->membership_number) : 'N/A' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Referrer</p>
                                    <span><?= $user->has('parent_user') ? $this->Html->link($user->parent_user->id, [
                                            'controller' => 'Users',
                                            'action'     => 'view',
                                            $user->parent_user->id,
                                        ]) : '' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Voting Station</p>
                                    <span><?= $user->has('voting_station') ? $this->Html->link($user->voting_station->name,
                                            [
                                                'controller' => 'VotingStations',
                                                'action'     => 'view',
                                                $user->voting_station->id,
                                            ]) : '' ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">First Time Voter</p>
                                    <span><?= $user->first_time_voter ? __('Yes') : __('No'); ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Branch</p>
                                    <span><?= $user->has('branch') ? $this->Html->link($user->branch->name, [
                                            'controller' => 'Branches',
                                            'action'     => 'view',
                                            $user->branch->id,
                                        ]) : '' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Points</p>
                                    <span><?= $this->Number->format($user->points) ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Membership Status</p>
                                    <span><?= nl2br(userStatusToString($user->user_status, PHP_EOL, '- ')); ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">Referer Token</p>
                                    <span><?= (empty($user->token)) ? 'Not Available' : 'https://actionsa.app/r/'.$user->token; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                        <table>
                            <tr>
                                <th><?= __('Role') ?></th>
                                <td><?= $user->has('role') ? $this->Html->link($user->role->name,
                                        ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Token') ?></th>
                                <td><?= h($user->token) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Id') ?></th>
                                <td><?= $this->Number->format($user->id) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('User Status') ?></th>
                                <td><?= $this->Number->format($user->user_status) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Created At') ?></th>
                                <td><?= h($user->created_at) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Updated At') ?></th>
                                <td><?= h($user->updated_at) ?></td>
                            </tr>
                        </table>

                        <hr>


                        <?php if ( ! empty($user->audit_logs)) : ?>
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <th><?= __('Id') ?></th>
                                        <th><?= __('Description') ?></th>
                                        <th><?= __('Subject Id') ?></th>
                                        <th><?= __('Subject Type') ?></th>
                                        <th><?= __('User Id') ?></th>
                                        <th><?= __('Properties') ?></th>
                                        <th><?= __('Host') ?></th>
                                        <th><?= __('Created At') ?></th>
                                        <th><?= __('Updated At') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                    <?php foreach ($user->audit_logs as $auditLogs) : ?>
                                        <tr>
                                            <td><?= h($auditLogs->id) ?></td>
                                            <td><?= h($auditLogs->description) ?></td>
                                            <td><?= h($auditLogs->subject_id) ?></td>
                                            <td><?= h($auditLogs->subject_type) ?></td>
                                            <td><?= h($auditLogs->user_id) ?></td>
                                            <td><?= h($auditLogs->properties) ?></td>
                                            <td><?= h($auditLogs->host) ?></td>
                                            <td><?= h($auditLogs->created_at) ?></td>
                                            <td><?= h($auditLogs->updated_at) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('View'), [
                                                    'controller' => 'AuditLogs',
                                                    'action'     => 'view',
                                                    $auditLogs->id,
                                                ]) ?>
                                                <?= $this->Html->link(__('Edit'), [
                                                    'controller' => 'AuditLogs',
                                                    'action'     => 'edit',
                                                    $auditLogs->id,
                                                ]) ?>
                                                <?= $this->Form->postLink(__('Delete'),
                                                    ['controller' => 'AuditLogs', 'action' => 'delete', $auditLogs->id],
                                                    [
                                                        'confirm' => __('Are you sure you want to delete # {0}?',
                                                            $auditLogs->id),
                                                    ]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
                        <?php if ( ! empty($user->badges)) : ?>
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <th><?= __('Id') ?></th>
                                        <th><?= __('Name') ?></th>
                                        <th><?= __('Description') ?></th>
                                        <th><?= __('Icon') ?></th>
                                        <th><?= __('Level') ?></th>
                                        <th><?= __('Created At') ?></th>
                                        <th><?= __('Updated At') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                    <?php foreach ($user->badges as $badges) : ?>
                                        <tr>
                                            <td><?= h($badges->id) ?></td>
                                            <td><?= h($badges->name) ?></td>
                                            <td><?= h($badges->description) ?></td>
                                            <td><?= h($badges->icon) ?></td>
                                            <td><?= h($badges->level) ?></td>
                                            <td><?= h($badges->created_at) ?></td>
                                            <td><?= h($badges->updated_at) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('View'),
                                                    ['controller' => 'Badges', 'action' => 'view', $badges->id]) ?>
                                                <?= $this->Html->link(__('Edit'),
                                                    ['controller' => 'Badges', 'action' => 'edit', $badges->id]) ?>
                                                <?= $this->Form->postLink(__('Delete'),
                                                    ['controller' => 'Badges', 'action' => 'delete', $badges->id], [
                                                        'confirm' => __('Are you sure you want to delete # {0}?',
                                                            $badges->id),
                                                    ]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                    <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="stats-tab">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                                <div class="card-body text-center"><i class="i-Add-User"></i>
                                    <div class="content">
                                        <p class="text-muted mt-2 mb-0">Total Leads</p>
                                        <p class="text-primary text-24 line-height-1 mb-2">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

