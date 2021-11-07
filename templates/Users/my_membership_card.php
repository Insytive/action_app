<?php
/**
 * @var App\View\AppView $this
 * @var User $user
 */
use App\Model\Entity\User;
?><!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>ActionSA - My Membership Card</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet"/>
    <?= $this->Html->css('themes/lite-purple.min.css') ?>
    <?= $this->Html->css('plugins/perfect-scrollbar.min.css') ?>
    <?= $this->Html->meta('icon') ?>
</head>
<body class="text-left">
<section class="container mt-5">
    <div class="d-flex flex-row justify-content-center">
        <div class="col-md-4">

            <div id="print-area">
                <?= $this->element('membership_card', ['actions' => false]) ?>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <button class="btn btn-warning btn-block" type="button" id="capture">Download</button>
                </div>

                <div class="col-6">
                    <small class="mb-1 text-muted">
                        ActionSA &copy; <?= date('Y') ?>
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

    <link rel="stylesheet" href="/css/plugins/toastr.css"/>

    <?= $this->Html->script('plugins/jquery-3.3.1.min.js') ?>
    <?= $this->Html->script('plugins/bootstrap.bundle.min.js') ?>
    <?= $this->Html->script('plugins/perfect-scrollbar.min.js') ?>
    <?= $this->Html->script('plugins/toastr.min.js') ?>
    <?= $this->Html->script('scripts/script.min.js') ?>
    <?= $this->Html->script('scripts/sidebar.large.script.min.js') ?>
    <?= $this->Html->script('scripts/form.validation.script.min.js') ?>
    <?= $this->Html->script('scripts/html2canvas.min.js') ?>

    <script>
    jQuery(document).ready(function(){
        html2canvas(document.querySelector(".card-profile-1"), {scale: 2}).then(canvas => {
            $('#print-area').html(canvas);
        });

        /* Canvas Download */
        function download(canvas, filename) {
            /// create an "off-screen" anchor tag
            var lnk = document.createElement('a'), e;

            /// the key here is to set the download attribute of the a tag
            lnk.download = filename;

            /// convert canvas content to data-uri for link. When download
            /// attribute is set the content pointed to by link will be
            /// pushed as "download" in HTML5 capable browsers
            lnk.href = canvas.toDataURL("image/png;base64");

            /// create a "fake" click-event to trigger the download
            if (document.createEvent) {
                e = document.createEvent("MouseEvents");
                e.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);

                lnk.dispatchEvent(e);
            } else if (lnk.fireEvent) {
                lnk.fireEvent("onclick");
            }
        }

        $('#capture').click(function(){
            console.log('Output the file.');

            let canvas = document.getElementsByTagName('canvas');

            console.log(canvas);
            download(canvas[0], '<?= h($user->membership_number) ?>');
        });
    });
    </script>

    <?= $this->element('google_analytics') ?>
</body>
</html>
