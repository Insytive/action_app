<?php
/**
 * @var \App\View\AppView $this
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
        <?= $this->Form->create(null) ?>
        <?php
            echo $this->Form->control('membership_number', ['class'=>'form-control-rounded']);
            echo $this->Form->control('password', ['class'=>'form-control-rounded']);
        ?>
            <button class="btn btn-rounded btn-primary btn-block mt-2">Sign In</button>
        <?= $this->Form->end() ?>
        <div class="mt-3 text-center">
            <?= $this->Html->link('Forgot Password', ['action'=>'forgot'], ['class'=>'text-muted']) ?>
                 |
            <?= $this->Html->link('My Membership Number', '/myaction', ['class'=>'text-muted']) ?>
        </div>
    </div>
</div>
<div class="col-md-6 text-center" style="background-color: #0a0d1e;">
    <div class="pr-3 auth-right">
        <?= $this->Html->link('Not a member? Sign up.', '/register/member', ['class'=>'text-muted']) ?>
    </div>
</div>
