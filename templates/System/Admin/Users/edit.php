<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $votingStations Voting stations array
 * @var array $parentUsers Volunteers and other admins
 * @var array $branches Constituencies
 * @var array $badges May not be relevant
 * @var array $roles System User roles
 */

$this->assign('title', __('Edit User'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-75 float-left card-title m-0"><?= __('Edit User') ?></h3>
                <div class="dropdown dropleft text-right w-25 float-right">
                    <button class="btn bg-gray-100" id="asaCtrlOpts" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon i-Gear-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="asaCtrlOpts">
                        <?= $this->Html->link(__('View User'), ['action' => 'view', $user->id], ['class' => 'dropdown-item']) ?>
                        <?= $this->Html->link(__('All Users'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
                        <div class="dropdown-divider"></div>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $user->id],
                            ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'dropdown-item text-mute']
                            ) ?>

                    </div>
                </div>
            </div>

            <?= $this->Form->create($user) ?>
            <div class="card-body">
                <div class="card-title mb-3">Personal Details</div>
                <div class="users form content row">
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('first_name'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('last_name'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('id_number', ['label'=>'ID Number']); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('birthdate', ['empty' => true]); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('email'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('phone'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('first_time_voter', ['custom'=>true]); ?></div>
                </div>

                <hr>

                <div class="card-title mb-3">Address Details</div>
                <div class="users form content row">
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('address'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('building', ['label' => 'Street Address']); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('town', ['label' => 'Town/City']); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('postal_code'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('voting_station_id', ['options' => $votingStations, 'empty' => true]); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('branch_id', ['options' => $branches, 'empty' => true]); ?></div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary" type="reset" data-dismiss="modal">Reset</button>
                <button class="btn btn-primary ml-2" type="submit">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
