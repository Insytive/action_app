<?php
/**
 * @var App\View\AppView $this
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>ActionSA - My Action</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet"/>
    <?= $this->Html->css('themes/lite-purple.min.css') ?>
    <?= $this->Html->css('plugins/perfect-scrollbar.min.css') ?>
    <?= $this->Html->meta('icon') ?>
</head>
<body class="text-left">
<section class="container mt-5">
    <div class="d-flex flex-row justify-content-center">
        <div class="col-md-5">
            <div class="card mb-4" id="membershipFormContainer">
                <div class="card-body px-5">
                    <div class="d-flex flew-row justify-content-center">
                        <div class="pt-0 p-3 px-md-4">
                            <a href="/"><img src="/images/actionSA_logo.png" alt="Action SA logo" style="width: 168px;"></a>
                            <h2 class="mt-4 text-center">My Action</h2>
                            <p class="text-center">Retrieve my membership number.</p>
                        </div>
                    </div>

                    <?= $this->Flash->render() ?>

                    <div class="form-group mb-3"><?= $this->Form->control('id_number', [
                            'label'        => false,
                            'id'           => 'id_number',
                            'autocomplete' => 'off',
                            'placeholder'  => 'Please input your ID number to start the process.',
                        ]); ?></div>

                    <p>Do you already know your membership number? <a href="/login">Login</a>.</p>
                </div>
                <div class="card-footer d-flex flex-row justify-content-between">
                    <a class="btn btn-link font-weight-bolder" href="/my-membership-card">My Membership Card</a>
                    <button class="btn btn-primary" type="button" id="takeAction">Retrieve Number</button>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <small class="mb-1 text-muted">
                        ActionSA &copy; <?= date('Y') ?>  | All rights reserved.
                    </small>
                </div>
                <div class="col-6 text-right">
                    <ul class="list-inline text-small">
                        <li class="list-inline-item"><a class="text-muted" href="https://www.actionsa.org.za/">Home</a></li>
                        <li class="list-inline-item"><a class="text-muted" href="https://www.actionsa.org.za/about">About</a></li>
                        <li class="list-inline-item"><a class="text-muted" href="https://www.actionsa.org.za/contact">Contacts</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->script('plugins/jquery-3.3.1.min.js') ?>
<?= $this->Html->script('plugins/bootstrap.bundle.min.js') ?>
<?= $this->Html->script('plugins/perfect-scrollbar.min.js') ?>
<?= $this->Html->script('scripts/script.min.js') ?>
<?= $this->Html->script('scripts/sidebar.large.script.min.js') ?>



<link rel="stylesheet" href="/css/plugins/toastr.css"/>
<script src="/js/plugins/toastr.min.js"></script>
<script src="/js/scripts/form.validation.script.min.js"></script>
<script>
    jQuery(document).ready(function () {
        $("#takeAction").on("click", function () {
            $('#ajaxFeedback').remove();

            const idNumber = $("#id_number").val();

            $.getJSON("/my-membership-number/" + idNumber, function (resp) {
                if (resp.status === 1 && resp.message.length > 0) {
                    $('#membershipFormContainer').prepend('<div class="alert alert-success" role="alert" id="ajaxFeedback">Your membership number is: <strong class="text-capitalize">' + resp.message + '</strong>  \n' +
                        '                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                        '                                </div>');

                    toastr.success(resp.data.notice, {
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 3e3
                    });


                } else if (resp.status === -1 && resp.message.length > 0) {
                    $('#membershipFormContainer').prepend('<div class="alert alert-danger" role="alert" id="ajaxFeedback">Error!<strong class="text-capitalize"> ' + resp.message + '</strong>  \n' +
                        '                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                        '                                </div>');

                    toastr.error(resp.data.notice, {
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 3e3
                    });
                }
            });
        });
    });
</script>

<?= $this->element('google_analytics') ?>
</body>
</html>
