<?php
    /**
     * @var App\View\AppView $this
     * @var App\Model\Entity\User $user
     * @var array $provinces Provinces options
     * @var string $type Registration type
     */

    $this->assign('title', __('Register {0}', ucfirst($type)));
?>
<section class="container mt-5">
    <div class="card mb-4 shadow-sm" id="membershipFormContainer">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">Register</h4>
        </div>
        <?= $this->Form->create($user, ['id'=>'membershipForm', 'novalidate'=>'novalidate', 'class'=>'needs-validation']) ?>
        <div class="card-body">
            <div class="users form content row">
                <div class="col-md-6 form-group mb-3"><?= $this->Form->control('id_number', ['label'=>'ID Number', 'id'=>'id_number', 'autocomplete'=>'off', 'placeholder'=>'Please input your ID number to start the process.']); ?></div>
                <div class="col-md-6 form-group mb-3 d-none addressDisplay"><?= $this->Form->control('phone'); ?></div>
                <div class="col-md-6 mb-3 d-none addressDisplay">
                    <div class="row">
                        <div class="col-md-6 form-group"><?= $this->Form->control('first_name'); ?></div>
                        <div class="col-md-6 form-group"><?= $this->Form->control('last_name'); ?></div>
                    </div>
                </div>
                <div class="col-md-6 form-group mb-3 d-none addressDisplay"><?= $this->Form->control('email'); ?></div>

            </div>


            <div class="card-title mb-3 addressDisplay d-none">Address Details</div>
            <div class="users form addressDisplay row d-none">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <?= $this->Form->control('address', ['label' => 'Street Address', 'autocomplete'=>'off', 'help' => '<img src="/images/powered_by_google_on_white.png" alt="Powered by Google" class="float-right" style="height: 14px;">']); ?>
                        </div>
                        <div class="col-6 form-group mb-3"><?= $this->Form->control('suburb', ['label' => 'Suburb/Township', 'id'=>'sublocality_level_1', 'placeholder'=>'Alexandra']); ?></div>
                        <div class="col-6 form-group mb-3"><?= $this->Form->control('town', ['label' => 'Town/City', 'id'=>'locality', 'placeholder'=>'Sandton']); ?></div>
                        <div class="col-6 form-group mb-3"><?= $this->Form->control('province', ['label' => 'Province', 'id'=>'administrative_area_level_1', 'empty'=>'Please select option']); ?></div>
                        <div class="col-6 form-group mb-3"><?= $this->Form->control('municipality', ['label' => 'Municipality', 'id'=>'administrative_area_level_2', 'placeholder'=>'City of JHB']); ?></div>
                        <div class="col-4 form-group mb-3"><?= $this->Form->control('postal_code', ['label' => 'PO Code', 'type'=>'text', 'id'=>'postal_code', 'placeholder'=>'2001']); ?></div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <?= $this->Form->control('station_id', ['type'=>'hidden', 'id'=>'station_id']); ?>
                            <?= $this->Form->control('voting_station', [
                                'id' => 'voting_station',
                                'placeholder' => 'Type to start search and select from the results if a match appears',
                                'autocomplete' => 'off',
                                'help'=>'Leave black if you do not know your voting station.']); ?>
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

                        <div class="col-md-12 form-group mb-3"><?= $this->Form->control('first_time_voter', ['custom'=>true, 'label'=>'Are you a first time voter?']); ?></div>
                    <?php if ('supporter' != $type): ?>
                        <div class="col-md-12 form-group mb-3">
                            <?= $this->Form->control('volunteer', ['custom'=>true, 'type'=>'checkbox', 'label'=>'Apply for volunteer opportunities', 'default' => ('volunteer' === $type)]); ?>
                        </div>

                        <div class="col-md-12 form-group mb-3">
                            <?= $this->Form->control('constitution', ['custom'=>true, 'type'=>'checkbox', 'required'=>true, 'label'=>'I accept the ActionSA constitution and policy', 'help'=>'Please read and/or download <a href="/actionsa_interim_constitution.pdf" target="_blank" class="btn btn-outline-info btn-sm">ActionSA constitution and policies.</a>.']); ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row addressDisplay d-none">
                <div class="col-6"><a class="btn btn-secondary" href="/">Cancel</a></div>
                <div class="col-6"><button class="btn btn-secondary btn-block ripple" type="submit" disabled>Save</button></div>
            </div>
            <div class="row togglable">
                <div class="col-12"><button class="btn btn-primary btn-block" type="button">Validate ID Number</button></div>
            </div>
        </div>
        <?= $this->Form->end() ?>
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
        $("#id_number").on('blur', function(){

            const idNumber = $("#id_number").val();
            const volApply = $("#volunteer").is(':checked');

            $.getJSON("/humanity/" + idNumber +'/'+volApply, function (resp) {
                if (resp.status === 1 && resp.message.length > 0) {
                    toastr.success(resp.message, {
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 3e3
                    });

                    unhideEls();

                    if (typeof resp.redirect !== "undefined" && resp.redirect == 1){
                        $('#membershipForm').remove();

                        $('#membershipFormContainer').append('<div class="alert alert-success" role="alert"><strong class="text-capitalize">Success!</strong> Please <a href="https://www.actionsa.org.za/">click here</a> to go back to the main website. Redirecting in 3 seconds... \n' +
                            '                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                            '                                </div>');

                        setTimeout(function(){
                            window.location.href = "https://www.actionsa.org.za/";
                        }, 3000);
                    }

                    return;
                }

                toastr.error(resp.message, {
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3e3
                });

                hideEls();
            });
        });

        function unhideEls(){
            $(".addressDisplay").removeClass("d-none");
            $(".togglable").addClass("d-none");
            $("#membershipForm .btn.ripple").removeClass("btn-secondary").addClass("btn-primary").removeAttr("disabled");
        }

        function hideEls(){
            $(".addressDisplay").addClass("d-none");
            $(".togglable").removeClass("d-none");
            $("#membershipForm .btn.ripple").removeClass("btn-primary").addClass("btn-secondary").attr("disabled", true);
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

        <?php if ($this->getRequest()->is('post')): ?>
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
            document.getElementById("address"),
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
                document.getElementById("address").value = componentFormValues[component];
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
