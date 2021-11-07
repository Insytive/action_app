<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ward $ward
 */

$this->assign('title', __('Add Ward'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-75 float-left card-title m-0"><?= __('Add Ward') ?></h3>
                <div class="dropdown dropleft text-right w-25 float-right">
                    <button class="btn bg-gray-100" id="asaCtrlOpts" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon i-Gear-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="asaCtrlOpts">
                        <?= $this->Html->link(__('List Wards'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
                    </div>
                </div>
            </div>

            <?= $this->Form->create($ward) ?>
            <div class="card-body">
                <div class="wards form row">
                    <div class="col-6 form-group mb-3">
                        <?= $this->Form->control('name'); ?>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <?= $this->Form->control('_area_id', ['type'=>'hidden', 'id'=>'area_id']); ?>
                        <?= $this->Form->control('area', [
                            'id' => 'area',
                            'placeholder' => 'Start typing and pick from the list',
                            'label' => 'Region/Municipality',
                            'autocomplete' => 'off']);
                        ?>
                        <div style="top:90%; position:absolute; z-index:1100; display: none" class="card w-90" id="areas">
                            <div class="card-header py-2">
                                <h6 class="w-80 float-left m-0">Please select a municipality below</h6>
                                <a class="text-right w-20 float-right" href="javascript:" id="asacAc"><i class="i-Close font-weight-bolder"></i></a>
                            </div>
                            <div class="card-body p-0 m-0" style="height:160px; overflow-y: scroll;">
                                <div class="list-group list-group-flush w-100" id="asaVDs"></div>
                            </div>
                            <div class="card-footer" style="background:#ffeeba url('/images/logo.svg') right no-repeat;">
                                <small>Can't find the municipality?</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary" type="reset" data-dismiss="modal">Reset</button>
                <button class="btn btn-primary ml-2" type="submit">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->start('footer_scripts'); ?>
<script>
jQuery(document).ready(function() {
    /**
     * Autocomplete
     */
    $("#area").on("keyup", function (){
        let area = $(this).val();

        if (area.length >= 3){
            $("#asaVDs").empty();

            $.get("/system/admin/areas/autocomplete/" + area, function (resp) {
                $("#asaVDs").html(resp);

                $("#areas").show();
            });

            return;
        }

        $("#areas").hide();
    });

    /**
     * Autocomplete option selection
     */
    $(document).on("click", ".asaVD", function () {
        $("#area_id").val($(this).data('id'));
        $("#area").val($(this).text());

        $("#areas").hide();
    });

    $("#asacAc").on("click", function () {
        $("#areas").hide();
    });

});
</script>
<?php $this->end(); ?>
