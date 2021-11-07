<?php
    /**
     * @var \App\View\AppView $this
     */

    $this->assign('bodyClass', 'membership-field');
?>
<section>
    <div class="container ">
        <div class="card">
        <form class="w-full">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/2 xl:w-1/2 px-4">
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full px-3">
                            <label
                                for="id_number"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >ID number</label
                            >
                            <input
                                type="text"
                                placeholder="ID number"
                                id="id_number"
                                name="id_number"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-2 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                                tabindex="1"

                            />

                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                            <label
                                for="first_name"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >First name</label
                            >
                            <input
                                type="text"
                                id="first_name"
                                tabindex="2"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-2 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                                name="first_name"
                                placeholder="First name"

                            />

                        </div>

                        <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                            <label
                                for="first_name"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >Last name</label
                            >
                            <input

                                id="last_name"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-2 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                                name="last_name"
                                placeholder="Last name"

                            />

                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full px-3">
                            <label
                                for="email"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >Email</label
                            >
                            <input
                                type="email"
                                tabindex="4"

                                placeholder="Email"
                                id="lead_email"
                                name="lead_email"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                            />
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 xl:w-1/2 px-4">
                    <div class="flex flex-wrap -mx-3 mb-3">
                        <div class="w-full px-3">
                            <label
                                for="phone"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >Phone</label
                            >
                            <input
                                type="text"
                                placeholder="Phone number"
                                id="phone"
                                name="phone"
                                tabindex="4"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-3 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"

                            />

                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-3">
                        <div class="w-full px-3">
                            <label
                                for="address"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >Address
                            </label>


                            <input

                                name="address"
                                placeholder="Please type your address"
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-3 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                            />


                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-3">
                        <div class="w-full px-3">
                            <label
                                for="voting_station"
                                class="block tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                >Voting Station
                            </label>


                            <input
                                class="rounded-md border-solid border-2 border-green-500 appearance-none block w-full text-gray-700 py-3 px-4 mb-2 leading-tight focus:outline-none focus:border-green-500 focus:bg-white"
                                type="text"
                                id="voting_station"
                                name="voting_station"
                                placeholder="Type in Voting station"

                            />

                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-3">
                        <div class="w-full px-3">
                            <label
                                for="first_time_voter"
                                class="block tracking-wide text-gray-700 text-md font-bold mb-2"
                                >Are you a first time voter?
                            </label>

                            <div class="custom-control custom-radio">
                                <input
                                    type="radio"
                                    id="customRadio3"
                                    class="custom-control-input"
                                    name="first_time_voter"

                                />
                                <label
                                    class="custom-control-label tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                    for="customRadio3"
                                    >Yes</label
                                >
                            </div>
                            <div class="custom-control custom-radio">
                                <input
                                    type="radio"
                                    id="customRadio4"
                                    class="custom-control-input"
                                    name="first_time_voter"
                                    value="0"

                                />
                                <label
                                    class="custom-control-label tracking-wide text-gray-700 text-2xl font-bold mb-2"
                                    for="customRadio4"
                                    >No</label
                                >
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <hr>

            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full px-3">
                    <button
                        class="bg-green-500 py-2 px-6 font-bold rounded-full text-white"
                    >
                        Register
                    </button>
                </div>
            </div>
        </form>
        </div>
</div> <!-- ./container -->
</section>
