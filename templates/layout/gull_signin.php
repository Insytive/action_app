<?php
/**
 * Concept Jitsu Ninjas - Gull
 * Copyright (c) Concept Jitsu Ninjas
 *
 * @var \App\View\AppView $this
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>ASA | System Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <?= $this->Html->css('themes/lite-purple.min.css') ?>
    <?= $this->Html->meta('icon') ?>
</head>
<body style="background-color: #05b616;">
<div class="auth-layout-wrap">
    <div class="auth-content">
        <div class="card o-hidden">
            <div class="row">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('plugins/jquery-3.3.1.min.js') ?>
<?= $this->Html->script('plugins/bootstrap.bundle.min.js') ?>
<?= $this->Html->script('scripts/script.min.js') ?>

<?php echo $this->fetch('footer_scripts'); ?>
</body>
</html>
