<?php
    /**
     * @var \Cake\Datasource\EntityInterface $user
     */
?>
Hi <?= $user->name ?>,

A password reset for your account has been requested.

Please go to https://actionsa.app/rp and enter *<?= $user->password_reset ?>* to change your password or simply visit http://actionsa.app/system/admin/users/reset-password/<?= $user->password_reset ?>.

If you did not request this ACTION, please notify us.
