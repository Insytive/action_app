<?php
    /**
     * @var App\View\AppView $this
     */

    $this->assign('title', __('My Action'));
?>
<section class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm" id="membershipFormContainer">
                <div class="card-body">
                    <div class="form-group mb-3"><?= $this->Form->control('id_number', ['label'=>'ID Number', 'id'=>'id_number', 'autocomplete'=>'off', 'placeholder'=>'Please input your ID number to start the process.']); ?></div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-block" type="button" id="takeAction">Retrieve Details</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Resources</div>
                <ul class="list-group list-group-flush" id="myactionLinks">
                    <a href="/login" class="list-group-item list-group-item-action">Login</a>
                    <a href="javascript:" class="list-group-item list-group-item-action disabled">Membership Card</a>
                </ul>
            </div>
        </div>
    </div>

</section>

<!--  Terms and Conditions Modal -->
<div class="modal fade modalTC" tabindex="-1" role="dialog" aria-labelledby="asaModalTC" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asaModalTC">ActionSA Terms and Conditions</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /Terms and Conditions Modal -->


<?php $this->start('footer_scripts'); ?>
<style>
select.form-control,
input[type=text].form-control,
input[type=number].form-control,
input[type=email].form-control,
input[type=tel].form-control
{
    border-color: #7cd682;
}
</style>

<link rel="stylesheet" href="/css/plugins/toastr.css" />
<script src="/js/plugins/toastr.min.js"></script>
<script src="/js/scripts/form.validation.script.min.js"></script>
<script>
    jQuery(document).ready(function() {
        $("#takeAction").on("click", function () {
            $('#ajaxFeedback').remove();

            const idNumber = $("#id_number").val();

            $.getJSON("/my-membership-number/" + idNumber, function (resp) {
                if (resp.status === 1 && resp.message.length > 0) {
                    $('#membershipFormContainer').prepend('<div class="alert alert-success" role="alert" id="ajaxFeedback">Your membership number is: <strong class="text-capitalize">'+resp.message+'</strong>  \n' +
                        '                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                        '                                </div>');

                    toastr.success(resp.data.notice, {
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 3e3
                    });

                    return;
                } else if (resp.status === -1 && resp.message.length > 0){
                    $('#membershipFormContainer').prepend('<div class="alert alert-danger" role="alert" id="ajaxFeedback">Error!<strong class="text-capitalize"> '+resp.message+'</strong>  \n' +
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

<?php $this->end(); ?>
