<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $votingStations Voting stations array
 * @var array $parentUsers Volunteers and other admins
 * @var array $branches Constituencies
 * @var array $badges May not be relevant
 * @var array $roles System User roles
 */

$this->assign('title', ('add' == $this->getRequest()->getParam('action')) ? __('Add Member') : $user->name);
?>

<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'btn btn-default']) ?>
<?php $this->end(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <?= $this->Form->create($user) ?>
            <div class="card-body">
                <div class="users form content row">
                    <div class="col-md-12 form-group mb-3"><?= $this->Form->control('id_number', ['label'=>'South African Identity Number', 'id'=>'id_number']); ?></div>
                </div>

                <div class="users form content row addressDisplay d-none">
                    <?php if (4 != $this->Identity->get('role_id')): ?>
                    <div class="col-md-12 form-group mb-3">
                        <?= $this->Form->control('parent_id', ['options' => $parentUsers, 'empty' => true, 'class'=>'select2', 'label' => 'Referer']); ?>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('first_name'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('last_name'); ?></div>

                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('email'); ?></div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('phone'); ?></div>

                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <?= $this->Form->control('station_id', ['type'=>'hidden', 'id'=>'station_id', 'default'=>$user->voting_station_id]); ?>
                                <?= $this->Form->control('voting_station', [
                                    'id' => 'voting_station',
                                    'placeholder' => (isset($user->station) && !empty($user->station)) ? $user->station : 'Type to start search and select from the results if a match appears',
                                    'autocomplete' => 'off',
                                    'help'=>'Leave blank if you do not know your voting station.']); ?>
                                <div style="top:90%; position:absolute; z-index:1100; display: none" class="card w-90" id="stations">
                                    <div class="card-header py-2">
                                        <h6 class="w-80 float-left m-0">Please select Voting Station below</h6>
                                        <a class="text-right w-20 float-right" href="javascript:" id="asacAc"><i class="i-Close font-weight-bolder"></i></a>
                                    </div>
                                    <div class="card-body p-0 m-0" style="height:160px; overflow-y: scroll;">
                                        <div class="list-group list-group-flush w-100" id="asaVDs"></div>
                                    </div>
                                    <div class="card-footer" style="background:#ffeeba url('/images/logo.svg') right no-repeat;">
                                        <small>Can't find your Voting Station?</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('branch_id', ['options' => $branches, 'empty' => true]); ?></div>

                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('first_time_voter', ['custom'=>true, 'label'=>'Is this lead a first time voter?']); ?></div>

                    <div class="col-md-6 form-group mb-3"><?= ('add' == $this->getRequest()->getParam('action')) ? $this->Form->control('user_status', ['custom'=>true, 'type'=>'checkbox', 'value'=>MEMBER_VOLUNTEER_REVIEW, 'label'=> 'Add as a Supporter', 'required'=>false]) : ''; ?></div>
                    <?php //endif; ?>
                </div>

                <hr>

                <div class="card-title mb-3">Address Details</div>
                <div class="users form content row addressDisplay d-none">
                    <div class="col-md-12 form-group mb-3">
                        <?= $this->Form->control('street_address', [
                            'label' => 'Street Address',
                            'id' => 'street_address',
                            'autocomplete'=>'off',
                            'help' => '<img src="/images/powered_by_google_on_white.png" alt="Powered by Google" class="float-right" style="height: 14px;">']); ?>
                    </div>
                    <div class="col-6 form-group mb-3"><?= $this->Form->control('suburb', ['label' => 'Suburb/Township', 'id'=>'sublocality_level_1', 'placeholder'=>'Alexandra']); ?></div>
                    <div class="col-6 form-group mb-3"><?= $this->Form->control('town', ['label' => 'Town/City', 'id'=>'locality', 'placeholder'=>'Sandton']); ?></div>

                    <div class="col-6 form-group mb-3"><?= $this->Form->control('province', ['label' => 'Province', 'id'=>'administrative_area_level_1', 'empty'=>'Please select option', 'default'=> $user->province_id]); ?></div>
                    <div class="col-6 form-group mb-3"><?= $this->Form->control('municipality', ['label' => 'Municipality', 'id'=>'administrative_area_level_2', 'placeholder'=>'City of JHB']); ?></div>
                    <div class="col-3 form-group mb-3"><?= $this->Form->control('postal_code', ['label' => 'PO Code', 'type'=>'text', 'id'=>'postal_code', 'placeholder'=>'2001']); ?></div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row addressDisplay d-none">
                    <button class="btn btn-secondary" type="reset" data-dismiss="modal">Reset</button>
                    <button class="btn btn-primary ml-2" type="submit">Save</button>
                </div>
                <div class="row togglable">
                    <div class="col-12"><button class="btn btn-primary btn-block" type="button">Validate ID Number</button></div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->start('footer_scripts'); ?>
<link rel="stylesheet" href="/css/plugins/toastr.css" />
<link rel="stylesheet" href="/css/plugins/select2.min.css" />
<script src="/js/plugins/toastr.min.js"></script>
<script src="/js/plugins/select2.full.min.js"></script>
<script src="/js/scripts/form.validation.script.min.js"></script>
<style type="text/css">
    .select2.select2-container.select2-container--default{
        width: 100% !important;
    }
</style>
<script>
    jQuery(document).ready(function() {
        $(".select2").select2();

        $("#id_number").on('blur', function(){

            const idNumber = $("#id_number").val();

            $.getJSON("/humanity/" + idNumber, function (resp) {
                if (resp.status === 1 && resp.message.length > 0) {
                    toastr.success(resp.message, {
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 2e3
                    });

                    if (typeof resp.redirect !== "undefined" && resp.redirect == -1){
                        unhideEls();
                    }

                    return;
                }

                toastr.error(resp.message, {
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 2e3
                });

                hideEls();
            });
        });

        function unhideEls(){
            $(".addressDisplay").removeClass("d-none");
            $(".togglable").addClass("d-none");
        }

        function hideEls(){
            $(".addressDisplay").addClass("d-none");
            $(".togglable").removeClass("d-none");
        }

        /**
         * Autocomplete
         */
        $("#voting_station").on("keyup", function (){
            let votingStation = $(this).val();

            if (votingStation.length >= 3){
                $("#asaVDs").empty();

                $.get("/users/voting-stations/" + votingStation, function (resp) {
                    $("#asaVDs").html(resp);

                    $("#stations").show();
                });

                return;
            }

            $("#stations").hide();
        });

        /**
         * Autocomplete
         */
        $(document).on("click", ".asaVD", function () {
            $("#station_id").val($(this).data('id'));
            $("#voting_station").val($(this).text());

            $("#stations").hide();
        });

        $("#asacAc").on("click", function () {
            $("#stations").hide();
        });

        <?php if('edit' == $this->getRequest()->getParam('action')): ?>
        unhideEls();
        <?php endif; ?>
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABwnssGgrYFQA5cvTm69qQJhZ36Si42tQ&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
<script>
    let placeSearch;
    let autocomplete;

    const componentForm = {
        street_number: "short_name",
        route: "long_name",
        sublocality_level_1: "short_name",
        locality: "long_name",
        administrative_area_level_1: "short_name",
        administrative_area_level_2: "long_name",
        country: "long_name",
        postal_code: "short_name"
    };

    let componentFormValues = {
        street_number: "",
        route: "",
        sublocality_level_1: "",
        locality: "",
        administrative_area_level_1: "",
        administrative_area_level_2: "",
        country: "",
        postal_code: ""
    };

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("street_address"),
            { types: ["geocode"] }
        );

        autocomplete.setFields(["address_component"]);
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        for (const component in componentForm) {
            if (document.getElementById(component)){
                document.getElementById(component).value = "";
            }
        }

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        for (const component of place.address_components) {
            const addressType = component.types[0];

            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                componentFormValues[addressType] = val;
            }
        }

        if (componentFormValues.street_number.length > 0){
            componentFormValues["route"] = componentFormValues["street_number"] +" "+ componentFormValues["route"];
        }

        for (const component in componentFormValues) {
            if ("route" === component){
                document.getElementById("street_address").value = componentFormValues[component];
            } else if (document.getElementById(component)) {
                document.getElementById(component).value = componentFormValues[component];
            }
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                const circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy,
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>
<?php $this->end(); ?>
