<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $votingStations Voting stations array
 * @var array $parentUsers Volunteers and other admins
 * @var array $branches Constituencies
 * @var array $genders May not be relevant
 * @var array $badges May not be relevant
 * @var array $roles System User roles
 */

$this->assign('title', __('Authentication'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-75 float-left card-title m-0"><?= h($user->name) ?></h3>
                <div class="dropdown dropleft text-right w-25 float-right">
                    <button class="btn bg-gray-100" id="asaCtrlOpts" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon i-Gear-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="asaCtrlOpts">
                            <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $user->id],
                            ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'dropdown-item text-mute']
                            ) ?>
                        <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
                    </div>
                </div>
            </div>

            <?= $this->Form->create($user) ?>
            <div class="card-body">
                <div class="users form content row">
                    <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('role_id', ['options' => $roles, 'empty' => true]); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('user_status', [
                        'type' =>'select',
                        'options' =>getUserStatuses(),
                        'multiple' =>true,
                        'val' =>bitMaskToOptions($user->user_status),
                        'empty' =>true]); ?>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('change_password', ['type'=>'password', 'require'=>false, 'help' => 'Leave blank unless changing the current password']); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('confirm_password', ['type'=>'password', 'help' => 'Match passwords', 'require'=>false ]); ?></div>

                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('send_password', ['type'=>'checkbox', 'custom'=>true, 'help' => 'Send password via email to the user', 'disabled'=>true]); ?></div>
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
