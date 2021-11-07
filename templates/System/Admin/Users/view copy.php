<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$this->assign('title', $user->name);
?>
<div class="row">
    <div class="col-md-4" id="print-area">
        <div class="card card-profile-1 mb-4" style="background-color: #05b615;">
            <div class="card-header border-0">
                <img src="/images/logo-front.svg" alt="ActionSA">
                <h5 class="text-uppercase text-center font-weight-bolder" style="color: black;">Membership Card</h5>
            </div>
            <div class="card-body text-center bg-white pb-5">
                <h5 class="text-uppercase m-0" style="color:#000;"><?= h($user->name) ?></h5>
                <p class="text-uppercase"><span class="font-weight-bold d-none">ID:</span> <?= h($user->id_number) ?></p>
                <p class="text-uppercase"><span class="font-weight-bold">Joined:</span> <?= h($user->created_at->toDateString()) ?></p>
            </div>
            <div class="card-footer user-profile" style="border-top: 3px solid #3053a3;">
                <div class="user-info" style="margin-top: -60px;">
                    <?php
                        $data = 'Name: ' . $user->name . PHP_EOL;
                        $data .= '#: ' . $user->membership_number . PHP_EOL;
                        $data .= 'Since: ' . $user->created_at->toDateString();

                        $options = new QROptions([
                            'version'      => 7,
                            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                            'eccLevel'     => QRCode::ECC_L,
                            'scale'        => 5,
                            'imageBase64'  => false,
                            'moduleValues' => [
                                // finder
                                1536 => [0, 63, 255], // dark (true)
                                6    => [255, 255, 255], // light (false), white is the transparency color and is enabled by default
                                // alignment
                                2560 => [255, 0, 255],
                                10   => [255, 255, 255],
                                // timing
                                3072 => [255, 0, 0],
                                12   => [255, 255, 255],
                                // format
                                3584 => [67, 191, 84],
                                14   => [255, 255, 255],
                                // version
                                4096 => [62, 174, 190],
                                16   => [255, 255, 255],
                                // data
                                1024 => [0, 0, 0],
                                4    => [255, 255, 255],
                                // darkmodule
                                512  => [0, 0, 0],
                                // separator
                                8    => [255, 255, 255],
                                // quietzone
                                18   => [255, 255, 255],
                            ],
                        ]);
                    ?>
                    <div class="box-shadow-2 mb-3 bg-white">
                        <img src="<?= (new QRCode)->render($data) ?>" alt="ASA QR Code" style="width: 200px;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center border-0 pb-0">
                <div class="w-90">
                    <ul class="nav nav-tabs profile-nav" id="profileTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">Personal Info</a></li>
                        <li class="nav-item"><a class="nav-link" id="access-tab" data-toggle="tab" href="#access" role="tab" aria-controls="access" aria-selected="false">Membership</a></li>
                        <li class="nav-item"><a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">Activity Log</a></li>
                        <li class="nav-item"><a class="nav-link" id="badges-tab" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="false">Badges</a></li>
                        <li class="nav-item"><a class="nav-link" id="stats-tab" data-toggle="tab" href="#statistics" role="tab" aria-controls="statistics" aria-selected="false">Statistics</a></li>
                    </ul>
                </div>
                <div class="dropdown dropleft text-right w-10 float-right">
                    <button class="btn bg-gray-100" id="asaCtrlOpts" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon i-Gear-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="asaCtrlOpts">
                        <?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $user->id], ['class' => 'dropdown-item']) ?>
                        <?= $this->Html->link(__('Authentication'), ['action' => 'accessDetails', $user->id], ['class' => 'dropdown-item']) ?>
                        <?= $this->Html->link(__('View Leads'), 'javascript:', ['class' => 'dropdown-item']) ?>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:window.print()" class="dropdown-item">Print Membership Card</a>
                        <div class="dropdown-divider"></div>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $user->id],
                            ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'dropdown-item text-mute']
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="card-body">


                <div class="tab-content" id="profileTabContent">
                    <div class="tab-pane fade active show" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Name
                                    </p>
                                    <span><?= h($user->name) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Email
                                    </p>
                                    <span><?= h($user->email) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Address
                                    </p>
                                    <span><?= h($user->address) ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        ID Number
                                    </p>
                                    <span><?= h($user->id_number) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Phone
                                    </p>
                                    <span><?= h($user->phone) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Building
                                    </p>
                                    <span><?= h($user->building) ?></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Birth Date
                                    </p>
                                    <span><?= h($user->birthdate) ?></span>
                                </div>

                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Gender
                                    </p>
                                    <span><?= $user->has('gender') ? $this->Html->link($user->gender->name, ['controller' => 'Genders', 'action' => 'view', $user->gender->id]) : '' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Town/City
                                    </p>
                                    <span><?= h($user->town) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="access" role="tabpanel" aria-labelledby="access-tab">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Membership Number
                                    </p>
                                    <span><?= h($user->membership_number) ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Referrer
                                    </p>
                                    <span><?= $user->has('parent_user') ? $this->Html->link($user->parent_user->id, ['controller' => 'Users', 'action' => 'view', $user->parent_user->id]) : '' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Voting Station
                                    </p>
                                    <span><?= $user->has('voting_station') ? $this->Html->link($user->voting_station->name, ['controller' => 'VotingStations', 'action' => 'view', $user->voting_station->id]) : '' ?></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        First Time Voter
                                    </p>
                                    <span><?= $user->first_time_voter ? __('Yes') : __('No'); ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Branch
                                    </p>
                                    <span><?= $user->has('branch') ? $this->Html->link($user->branch->name, ['controller' => 'Branches', 'action' => 'view', $user->branch->id]) : '' ?></span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-primary mb-1">
                                        Points
                                    </p>
                                    <span><?= $this->Number->format($user->points) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                        <table>
                            <tr>
                                <th><?= __('Role') ?></th>
                                <td><?= $user->has('role') ? $this->Html->link($user->role->name, ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : '' ?></td>
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


                        <?php if (!empty($user->audit_logs)) : ?>
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
                                                <?= $this->Html->link(__('View'), ['controller' => 'AuditLogs', 'action' => 'view', $auditLogs->id]) ?>
                                                <?= $this->Html->link(__('Edit'), ['controller' => 'AuditLogs', 'action' => 'edit', $auditLogs->id]) ?>
                                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'AuditLogs', 'action' => 'delete', $auditLogs->id], ['confirm' => __('Are you sure you want to delete # {0}?', $auditLogs->id)]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
                        <?php if (!empty($user->badges)) : ?>
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
                                                <?= $this->Html->link(__('View'), ['controller' => 'Badges', 'action' => 'view', $badges->id]) ?>
                                                <?= $this->Html->link(__('Edit'), ['controller' => 'Badges', 'action' => 'edit', $badges->id]) ?>
                                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Badges', 'action' => 'delete', $badges->id], ['confirm' => __('Are you sure you want to delete # {0}?', $badges->id)]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</div>

