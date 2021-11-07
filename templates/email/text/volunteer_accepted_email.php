<?php
    /**
     * @var \Cake\Datasource\EntityInterface $user
     */
?>
Hi <?= $user->name ?>,

Thank you for taking ACTION!

You have officially registered with as a volunteer.

Please use the following credentials to login
URL: https://www.actionsa.app/login
Username: <?= $user->membership_number ?>
Password: *your ID number*

You will be required to change your password on the first login attempt.
