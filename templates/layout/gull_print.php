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
    <title>ASA - <?= $this->fetch('title') ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <?= $this->Html->css('themes/lite-purple.min.css') ?>
    <?= $this->Html->css('plugins/perfect-scrollbar.min.css') ?>
    <?= $this->Html->meta('icon') ?>
</head>

<body class="text-left">
    <div class="app-admin-wrap layout-sidebar-large">
        <?= $this->element('nav') ?>

        <?= $this->element('sidebar') ?>

        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">
                <div class="breadcrumb">
                    <h1><?= $this->fetch('title') ?></h1>
                    <ul>
                        <li><a href="/system/admin/">Dashboard</a></li>
                        <li>Current</li>
                    </ul>
                </div>
                <div class="separator-breadcrumb border-top"></div>

                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>

            </div><!-- end of main-content -->

            <?= $this->element('footer') ?>

        </div>
    </div>

    <?= $this->element('search') ?>

    <?= $this->Html->script('plugins/jquery-3.3.1.min.js') ?>
    <?= $this->Html->script('plugins/bootstrap.bundle.min.js') ?>
    <?= $this->Html->script('plugins/perfect-scrollbar.min.js') ?>
    <?= $this->Html->script('scripts/script.min.js') ?>
    <?= $this->Html->script('scripts/sidebar.large.script.min.js') ?>

    <?php echo $this->fetch('footer_scripts'); ?>
</body>
</html>
