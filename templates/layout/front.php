<?php
/**
 * Concept Jitsu Ninjas - Front
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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <?= $this->Html->css('style') ?>
    <?= $this->Html->meta('icon') ?>
</head>

<body class="<?= $this->fetch('bodyClass') ?>">
<?= $this->element('front_nav') ?>
<main>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>
<?= $this->element('front_footer') ?>
</body>
</html>
