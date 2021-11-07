<?php
    /**
     * @var \App\View\AppView $this
     * @var array $provinces
     */

    $provinces = [
        1 => 'Eastern Cape',
        2 => 'Free State',
        3 => 'Gauteng',
        9 => 'KwaZulu-Natal',
        4 => 'Limpopo',
        5 => 'Mpumalanga',
        6 => 'Northern Cape',
        7 => 'North West',
        8 => 'Western Cape'
    ];
?>
<div class="card-header p-0">
    <div class="accordion" id="accordionRightIcon">
        <div class="card-header header-elements-inline">
            <h6 class="card-title ul-collapse__icon--size ul-collapse__right-icon mb-0">
                <a class="text-default collapsed" data-toggle="collapse" href="#accordion-item-icons-1" aria-expanded="false">
                    <span><i class="i-Magnifi-Glass1 ul-accordion__font"> </i></span> Filter</a>
            </h6>
        </div>
        <div class="collapse show" id="accordion-item-icons-1" data-parent="#accordionRightIcon">
            <div class="card-body">
                <?= $this->Form->create(null, ['url' => '/system/admin/wards/advanced-search', 'type'=>'get', 'autocomplete'=>'off']) ?>
                <div class="row">
                    <input type="hidden" name="municipality_id" id="municipality_id">
                    <input type="hidden" name="area_id" id="area_id">

                    <div class="col-md-3 form-group">
                        <?= $this->Form->control('p', [
                            'class' => 'form-control-rounded',
                            'empty' => 'Select a Province',
                            'label' => false,
                            'id'    => 'filterProv',
                            'options' => $provinces
                        ]); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <input class="form-control form-control-rounded" id="filterMun" name="m" type="text"
                               placeholder="Municipality" autocomplete="off">
                        <div style="top:90%; position:absolute; z-index:1100; display: none" class="card w-90" id="municipalities">
                            <div class="card-header py-2">
                                <h6 class="w-80 float-left m-0">Please select a municipality below</h6>
                                <a class="text-right w-20 float-right" href="javascript:" id="asacMc"><i class="i-Close font-weight-bolder"></i></a>
                            </div>
                            <div class="card-body p-0 m-0" style="height:160px; overflow-y: scroll;">
                                <div class="list-group list-group-flush w-100" id="asaMuns"></div>
                            </div>
                            <div class="card-footer" style="background:#ffeeba url('/images/logo.svg') right no-repeat;">
                                <small>Can't find the municipality?</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <input class="form-control form-control-rounded" id="filterArea" name="a" type="text"
                               placeholder="Sub-region" autocomplete="off">
                        <div style="top:90%; position:absolute; z-index:1100; display: none" class="card w-90" id="areas">
                            <div class="card-header py-2">
                                <h6 class="w-80 float-left m-0">Please select an area below</h6>
                                <a class="text-right w-20 float-right" href="javascript:" id="asacAc"><i class="i-Close font-weight-bolder"></i></a>
                            </div>
                            <div class="card-body p-0 m-0" style="height:160px; overflow-y: scroll;">
                                <div class="list-group list-group-flush w-100" id="asaVDs"></div>
                            </div>
                            <div class="card-footer" style="background:#ffeeba url('/images/logo.svg') right no-repeat;">
                                <small>Can't find the area?</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 form-group text-right">
                        <button class="btn btn-rounded btn-primary" id="filterGo" type="button">Search</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->start('element_footer_scripts'); ?>
<script>
jQuery(document).ready(function() {
    /**
     * Autocomplete
     */
    $("#filterMun").on("keyup", function (){
        let prov = $("#filterProv").val();
        let mun  = $(this).val();

        if (mun.length >= 3){
            $("#asaMuns").empty();

            $.get("/system/admin/municipalities/autocomplete/" + mun +'/?p='+ prov, function (resp){
                $("#asaMuns").html(resp);

                $("#municipalities").show();
            });

            return;
        }

        $("#municipalities").hide();
    });

    $("#filterArea").on("keyup", function (){
        let prov = $("#filterProv").val();
        let mun  = $('#municipality_id').val();
        let area = $(this).val();

        if (area.length >= 3){
            $("#asaVDs").empty();

            $.get("/system/admin/areas/autocomplete/" + area +'/?p='+ prov +'&m='+ mun, function (resp){
                $("#asaVDs").html(resp);

                $("#areas").show();
            });

            return;
        }

        $("#areas").hide();
    });

    $("#filterGo").on('click', function (){
        let prov = $("#filterProv").val();
        let mun  = $('#municipality_id').val();
        let area = $('#area_id').val();

        if (prov === '' && mun === '' && area === ''){
            return;
        }

        url = '/system/admin/wards/advanced-search/?';

        if (prov !== ''){
            url = url + 'p'+ prov +'&';
        }

        if (mun !== ''){
            url = url + 'm='+ mun +'&';
        }

        if (area !== ''){
            url = url + 'a='+ area +'&';
        }

        window.location = url;
    });

    /**
     * Provinces Filter
     */
    $(document).on("change", "#filterProv", function(){
        $("#filterMun").val('');
        $("#filterArea").val('');
        $("#municipality_id").val('');
        $("#area_id").val('');
    });

    /**
     * Autocomplete option selection
     */
    $(document).on("click", ".asaVD", function () {
        $("#area_id").val($(this).data('id'));
        $("#filterArea").val($(this).text());

        $("#areas").hide();
    });

    /**
     * Autocomplete option selection
     */
    $(document).on("click", ".asaMunOpt", function () {
        $("#municipality_id").val($(this).data('id'));
        $("#filterMun").val($(this).text());

        $("#filterArea").val('');
        $("#area_id").val('');

        $("#municipalities").hide();
    });


    $("#asacAc").on("click", function () {
        $("#areas").hide();
    });

    $("#asacMc").on("click", function () {
        $("#municipalities").hide();
    });
});
</script>
<?php $this->end(); ?>

