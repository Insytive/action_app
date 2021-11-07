<?php
/**
 * @var \App\View\AppView $this
 * @var string|null $password_reset Password reset code if set
 */

$this->assign('title', __('Authentication'));
?>
<div class="col-md-6">
    <div class="p-4">
        <div class="auth-logo text-center mb-4">
            <img src="/images/actionSA_logo.png" alt="Action SA">
        </div>
        <?= $this->Flash->render('auth') ?>
        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['autocomplete'=>'off']) ?>
        <?php
            echo $this->Form->control('password_reset', ['class'=>'form-control-rounded', 'required'=>true, 'default'=>$password_reset]);
            echo $this->Form->control('membership_number', ['class'=>'form-control-rounded', 'required'=>true, 'autocomplete'=>'off']);
            echo $this->Form->control('new_password', ['class'=>'form-control-rounded', 'label'=>'New Password', 'required'=>true, 'type'=>'password', 'help' => 'Must contain 8+ alphanumeric and special characters.']);
            echo $this->Form->control('confirm_password', ['class'=>'form-control-rounded', 'label'=>'Confirm Password', 'required'=>true, 'type'=>'password']);
        ?>
            <button class="btn btn-rounded btn-primary btn-block mt-2">Reset Password</button>
        <?= $this->Form->end() ?>
        <div class="mt-3 text-center">
            <?= $this->Html->link('Sign In', ['action'=>'login'], ['class'=>'text-muted']) ?>
        </div>
    </div>
</div>
<div class="col-md-6 text-center" style="background-color: #0a0d1e;">
    <div class="pr-3 auth-right">
        <?= $this->Html->link('Not a member? Sign up.', '/register/member', ['class'=>'text-muted']) ?>
    </div>
</div>
