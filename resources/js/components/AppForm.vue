<template>
    <form-wizard
        @on-complete="submit"
        color="#556595"
        title=""
        subtitle="">

        <div v-if="errorMessage" class="notification is-danger is-light">
            There were a couple of issues submitting your application, please correct the errors below.
        </div>


        <tab-content :before-change="validateStep.bind(this, 1)" title="About">
            <div id="app-about" class="content">

                <section>
                    <h3>Personal Information</h3>
                    <div class="columns">
                        <div class="column">
                            <div v-if="generalErrors.about.length > 0" class="notification is-danger is-light">
                                {{ joinErrors(generalErrors.about) }}
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-body">
                                    <text-input v-model="fields.first_name" :errors="errors.first_name"
                                                label="First Name"/>
                                    <text-input v-model="fields.middle_name" :errors="errors.middle_name"
                                                label="Middle Name"/>
                                    <text-input v-model="fields.last_name" :errors="errors.last_name"
                                                label="Last Name"/>
                                </div>
                            </div>
                            <div class="field is-horizontal">
                                <div class="field-body">
                                    <text-input v-model="fields.suffix" :errors="errors.suffix" label="Suffix"/>
                                    <text-input type="date" v-model="fields.date_of_birth"
                                                :errors="errors.date_of_birth"
                                                label="Date of Birth"/>
                                    <text-input type="number" v-model="fields.social_security_number"
                                                :errors="errors.social_security_number" label="Social Security Number"/>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">

                        </div>
                    </div>
                </section>


                <section>
                    <h3>Preferences</h3>
                    <div class="columns">
                        <div class="column">
                            <div class="field is-horizontal">
                                <div class="field-body">

                                    <text-input type="date" v-model="fields.preferred_move_in"
                                                :errors="errors.preferred_move_in"
                                                label="Preferred Move In Date"/>
                                    <dropdown-input v-model="fields.preferred_term" :errors="errors.preferred_term"
                                                    label="Preferred Term" :options="options.termOptions"/>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <p>If you have any move-in preferences you can denote those here.</p>
                        </div>
                    </div>
                </section>


                <section>
                    <h3>Contact Information</h3>
                    <div class="columns">
                        <div class="column">
                            <div class="field is-horizontal">
                                <div class="field-body">
                                    <text-input type="email" v-model="fields.email" :errors="errors.email"
                                                label="Email"/>
                                    <text-input type="tel" v-model="fields.phone" :errors="errors.phone" label="Phone"/>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <p>These contact details are used to prepare your lease, and give the landlord contact information after they approve your application.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h3>Identification</h3>
                    <div class="columns">
                        <div class="column">
                            <div v-if="generalErrors.identification.length > 0" class="notification is-danger is-light">
                                {{ joinErrors(generalErrors.identification) }}
                            </div>

                            <form-rows v-model.sync="fields.identification" :errors="errors.identification"
                                       v-slot:default="slotProps">
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <dropdown-input v-model="slotProps.row.id_type"
                                                        :errors="slotProps.errors.id_type"
                                                        label="ID Type" :options="options.identificationTypeOptions"/>
                                        <dropdown-input v-model="slotProps.row.id_state"
                                                        :errors="slotProps.errors.id_state"
                                                        label="ID State" :options="options.stateOptions"/>
                                        <text-input v-model="slotProps.row.id_number"
                                                    :errors="slotProps.errors.id_number"
                                                    label="ID Number"/>
                                    </div>
                                </div>
                            </form-rows>
                        </div>
                        <div class="column is-3">
                            <p>At least one form of identification is required to verify your rental application.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h3>References</h3>

                    <div class="columns">
                        <div class="column">
                            <div v-if="generalErrors.reference.length > 0" class="notification is-danger is-light">
                                {{ joinErrors(generalErrors.reference) }}
                            </div>
                            <form-rows v-model.sync="fields.reference" :errors="errors.reference"
                                       v-slot:default="slotProps">
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <text-input v-model="slotProps.row.ref_first_name"
                                                    :errors="slotProps.errors.ref_first_name"
                                                    label="First Name"></text-input>
                                        <text-input v-model="slotProps.row.ref_last_name"
                                                    :errors="slotProps.errors.ref_last_name"
                                                    label="Last Name"></text-input>
                                        <dropdown-input v-model="slotProps.row.ref_relation"
                                                        :errors="slotProps.errors.ref_relation"
                                                        label="Relation" :options="options.relationOptions"/>
                                        <text-input v-model="slotProps.row.ref_phone"
                                                    :errors="slotProps.errors.ref_phone"
                                                    label="Phone"></text-input>
                                    </div>
                                </div>
                            </form-rows>
                        </div>
                        <div class="column is-3">
                            <p>No references are required, but they can greatly help landlord's decision making.</p>
                        </div>
                    </div>
                </section>

            </div>
        </tab-content>

        <tab-content :before-change="validateStep.bind(this, 2)" title="Employment History">
            <div id="app-employment" class="columns content">
                <div class="column">
                    <h3>Current Income</h3>

                    <div class="field is-horizontal">
                        <div class="field-body">
                            <text-input v-model="fields.income_annual"
                                        :errors="fields.income_annual" label="Annual Income"/>
                            <text-input v-model="fields.income_comments"
                                        :errors="fields.income_comments" label="Comments"/>
                            <text-input v-model="fields.income_proof" :errors="fields.income_proof"
                                        label="Proof of Income"/>
                        </div>
                    </div>

                    <h3>Employment History</h3>

                    <div v-if="generalErrors.employer.length > 0" class="notification is-danger is-light">
                        {{ joinErrors(generalErrors.employer) }}
                    </div>

                    <form-rows v-model.sync="fields.employer" :errors="errors.employer" v-slot:default="slotProps">
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <dropdown-input v-model="slotProps.row.employer_status"
                                                :errors="slotProps.errors.employer_status" label="Status"
                                                :options="options.employmentStatusOptions"/>
                                <text-input v-model="slotProps.row.employer_name"
                                            :errors="slotProps.errors.employer_name" label="Employer Name"/>
                                <text-input v-model="slotProps.row.employer_position"
                                            :errors="slotProps.errors.employer_position" label="Your Position"/>
                                <text-input v-model="slotProps.row.employer_city"
                                            :errors="slotProps.errors.employer_city" label="City"/>
                                <dropdown-input v-model="slotProps.row.employer_state"
                                                :errors="slotProps.errors.employer_state" label="State"
                                                :options="options.stateOptions"/>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <text-input type="date" v-model="slotProps.row.employer_start_date"
                                            :errors="slotProps.errors.employer_start_date" label="Start Date"/>
                                <text-input type="date" v-model="slotProps.row.employer_end_date"
                                            :errors="slotProps.errors.employer_end_date" label="End Date"/>
                                <text-input v-model="slotProps.row.employer_supervisor"
                                            :errors="slotProps.errors.employer_supervisor" label="Supervisor"/>
                                <text-input v-model="slotProps.row.employer_supervisor_phone"
                                            :errors="slotProps.errors.employer_supervisor_phone"
                                            label="Supervisor Phone"/>
                            </div>
                        </div>
                    </form-rows>
                </div>
                <div class="column is-3">
                    <p>bla bla</p>
                </div>
            </div>
        </tab-content>

        <tab-content :before-change="validateStep.bind(this, 3)" title="Residence History">
            <div id="app-residence" class="columns content">
                <div class="column">

                    <h3>Residence History</h3>


                    <div v-if="generalErrors.rental_history.length > 0" class="notification is-danger is-light">
                        {{ joinErrors(generalErrors.rental_history) }}
                    </div>


                    <form-rows v-model.sync="fields.rental_history" :errors="errors.rental_history"
                               v-slot:default="slotProps">
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <text-input v-model="slotProps.row.street_address"
                                            :errors="slotProps.errors.street_address" label="Street Address"/>
                                <text-input v-model="slotProps.row.unit_apt" :errors="slotProps.errors.unit_apt"
                                            label="Unit / Apt"/>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <text-input v-model="slotProps.row.city" :errors="slotProps.errors.city" key="city"
                                            label="City"/>
                                <dropdown-input v-model="slotProps.row.state" :errors="slotProps.errors.state"
                                                key="state" label="State" :options="options.stateOptions"/>
                                <text-input v-model="slotProps.row.zip" :errors="slotProps.errors.zip" key="zip"
                                            label="Zip Code"/>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <text-input v-model="slotProps.row.landlord_name"
                                            :errors="slotProps.errors.landlord_name" key="landlord_name"
                                            label="Landlord Name"/>
                                <text-input v-model="slotProps.row.landlord_phone"
                                            :errors="slotProps.errors.landlord_phone" key="landlord_phone"
                                            label="Landlord Phone"/>
                                <text-input type="number" v-model="slotProps.row.rent_monthly"
                                            :errors="slotProps.errors.rent_monthly" key="rent_monthly"
                                            label="Rent Monthly"/>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <dropdown-input v-model="slotProps.row.rent_own_other"
                                                :errors="slotProps.errors.rent_own_other" key="rent_own_other"
                                                label="Rent / Own" :options="options.rentOrOwnOptions"/>
                                <text-input type="date" v-model="slotProps.row.start_date"
                                            :errors="slotProps.errors.start_date" label="Start Date"/>
                                <text-input type="date" v-model="slotProps.row.end_date"
                                            :errors="slotProps.errors.end_date" label="End Date"/>
                            </div>
                        </div>
                    </form-rows>

                </div>
                <div class="column is-3">
                    <p>Bla bla</p>
                </div>
            </div>
        </tab-content>

        <tab-content :before-change="validateStep.bind(this, 4)" title="Occupants">
            <div id="app-occupants" class="columns content">
                <div class="column">

                    <h3>Pets</h3>

                    <div v-if="generalErrors.pet.length > 0" class="notification is-danger is-light">
                        {{ joinErrors(generalErrors.pet) }}
                    </div>

                    <form-rows v-model.sync="fields.pet" :errors="errors.pet" v-slot:default="slotProps">
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <dropdown-input v-model="slotProps.row.pet_type" :errors="slotProps.errors.pet_type"
                                                key="pet_type" label="Pet Type" :options="options.petTypeOptions"/>
                                <text-input v-model="slotProps.row.pet_breed" :errors="slotProps.errors.pet_breed"
                                            key="pet_breed" label="Pet Breed"/>
                                <text-input v-model="slotProps.row.pet_weight" :errors="slotProps.errors.pet_weight"
                                            key="pet_weight" label="Pet Weight"/>
                            </div>
                        </div>
                    </form-rows>

                    <h3>Vehicles</h3>

                    <div v-if="generalErrors.vehicle.length > 0" class="notification is-danger is-light">
                        {{ joinErrors(generalErrors.vehicle) }}
                    </div>

                    <form-rows v-model.sync="fields.vehicle" :errors="errors.vehicle" v-slot:default="slotProps">
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <text-input type="number" v-model="slotProps.row.vehicle_year"
                                            :errors="slotProps.errors.vehicle_year" key="vehicle_year" label="Year"/>
                                <text-input v-model="slotProps.row.vehicle_make" :errors="slotProps.errors.vehicle_make"
                                            key="vehicle_make" label="Make"/>
                                <text-input v-model="slotProps.row.vehicle_model"
                                            :errors="slotProps.errors.vehicle_model" key="vehicle_model" label="Model"/>
                                <text-input v-model="slotProps.row.vehicle_plate"
                                            :errors="slotProps.errors.vehicle_plate" key="vehicle_plate" label="Plate"/>
                            </div>
                        </div>
                    </form-rows>
                </div>
                <div class="column is-3">
                    <p>bla bla bla</p>
                </div>
            </div>
        </tab-content>

        <tab-content :before-change="validateStep.bind(this, 5)" title="Final">
            <div id="app-submit" class="columns content">
                <div class="column">

                    <h3>Account Information</h3>

                    <div v-if="generalErrors.account.length > 0" class="notification is-danger is-light">
                        {{ joinErrors(generalErrors.account) }}
                    </div>

                    <div class="field is-horizontal">
                        <div class="field-body">
                            <text-input type="password" v-model="fields.password" :errors="errors.password"
                                        label="Password"/>
                            <text-input type="password" v-model="fields.password_confirmation"
                                        :errors="errors.password_confirmation" label="Password Confirmation"/>
                        </div>
                    </div>
                </div>
                <div class="column is-3">
                    <p>bla bla bla</p>
                </div>
            </div>
        </tab-content>

        <div v-if="success" class="notification is-success is-light">
            Message sent!
        </div>

    </form-wizard>
</template>

<style>
    section {
        margin-bottom: 40px;
    }

    i.wizard-icon {
        font-style: normal;
    }

    /* Development */
    /*.wizard-tab-container {*/
    /*    display: block !important;*/
    /*}*/
</style>

<script>
    import VueFormWizard from 'vue-form-wizard';
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'

    export default {
        components: {VueFormWizard},
        props: [
            'submitRoute',
            'formOptionsRoute',
            'validateStepRoute',
        ],
        mounted() {
            axios.get(this.formOptionsRoute).then(response => {
                this.options = response.data;
            }).catch(error => {
                this.errors = "Failure to retrieve form options from the backend, contact support?"
            });

            // if (localStorage.getItem('fields')) {
            //     console.log("setting")
            //     this.fields = JSON.parse(localStorage.getItem('fields'));
            // }
        },
        data() {
            return {
                options: {
                    'termOptions': {},
                    'rentOrOwnOptions': {},
                    'petTypeOptions': {},
                    'stateOptions': {},
                    'relationOptions': {},
                    'identificationTypeOptions': {},
                    'employmentStatusOptions': {}
                },
                fields: {},
                errors: {},
                errorMessage: null,
                generalErrors: {
                    about: [],
                    account: [],
                    employer: [],
                    identification: [],
                    rental_history: [],
                    reference: [],
                    pet: [],
                    vehicle: []
                },
                success: false,
                loaded: true,
            }
        },
        watch: {
            fields: {
                handler() {
                    // localStorage.setItem('fields', JSON.stringify(this.fields));
                },
                deep: true,
            },
        },
        methods: {
            validateStep(step) {
                console.log('validateStep', step);
                let parent = this;
                parent.fields.step = step;

                return new Promise((resolve, reject) => {
                    axios.post(parent.validateStepRoute, parent.fields).then(response => {
                        parent.setErrors({});
                        parent.errorMessage = null;
                        resolve(true);
                    }).catch(error => {
                        if (error.response.status === 422) {
                            reject();
                            parent.setErrors(error.response.data.errors || {});
                            parent.errorMessage = error.response.data.message || null;
                            window.scrollTo(0, 0);
                        }
                    });
                });

            },
            joinErrors(errors) {
                return errors.join('<br>')
            },
            setErrors(errors) {
                this.generalErrors.about = errors.about || [];
                this.generalErrors.account = errors.account || [];
                this.generalErrors.employer = errors.employer || [];
                this.generalErrors.identification = errors.identification || [];
                this.generalErrors.rental_history = errors.rental_history || [];
                this.generalErrors.reference = errors.reference || [];
                this.generalErrors.pet = errors.pet || [];
                this.generalErrors.vehicle = errors.vehicle || [];

                this.errors = this.dotToObject(errors);
            },
            submit() {
                if (this.loaded) {
                    this.loaded = false;
                    this.success = false;
                    this.errors = {};
                    this.errorMessage = null;
                    axios.post(this.submitRoute, this.fields).then(response => {
                        // this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                    }).catch(error => {
                        this.loaded = true;
                        console.error(error.response.data.errors);
                        if (error.response.status === 422) {
                            this.setErrors(error.response.data.errors || {});
                            this.errorMessage = error.response.data.message || null;
                            window.scrollTo(0, 0);
                        }
                    });
                }
            },
        },
    }

</script>
