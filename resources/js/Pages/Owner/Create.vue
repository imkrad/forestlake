<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 650px;" header-class="p-3 bg-dark-subtle" title="Create Owner" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                
                <BCol lg="12" class="mt-n1">
                    <InputLabel for="name" value="Fullname" :message="form.errors.name"/>
                    <TextInput type="text" v-model="form.name" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Email" :message="form.errors.email"/>
                    <TextInput type="text" v-model="form.email" class="form-control" placeholder="Please enter email" @input="handleInput('description')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Contact Number" :message="form.errors.contact_number"/>
                    <TextInput type="text" v-model="form.contact_number" class="form-control" placeholder="Please enter contact number" @input="handleInput('contact_number')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Civil Status" :message="form.errors.civil_status"/>
                    <Multiselect v-model="form.civil_status" placeholder="Select Status" :close-on-select="true" label="name" :options="['Single','Married']" />
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Birth Date" :message="form.errors.email"/>
                    <TextInput type="date" v-model="form.birth_date" class="form-control" placeholder="Please enter date" @input="handleInput('birth_date')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Birth Place" :message="form.errors.contact_number"/>
                    <TextInput type="text" v-model="form.birth_place" class="form-control" placeholder="Please enter place" @input="handleInput('birth_place')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Occupation" :message="form.errors.occupation"/>
                    <TextInput type="text" v-model="form.occupation" class="form-control" placeholder="Please enter occupation" @input="handleInput('occupation')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Address" :message="form.errors.address"/>
                    <TextInput type="text" v-model="form.address" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true"/>
                </BCol>
                <BCol lg="7" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="(form.errors.gender) ? 'text-danger' : ''">Please select sex :</BCol>
                <BCol lg="5" style="margin-top: 13px; margin-bottom: -12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio1" class="custom-control-input me-2" @input="handleInput('gender')" value="Male" v-model="form.gender">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio1">Male</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio2" class="custom-control-input me-2" @input="handleInput('gender')" value="Female" v-model="form.gender">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio2">Female</label>
                            </div>
                        </div>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                gender: null,
                contact_number: null,
                email: null,
                address: null,
                birth_place: null,
                birth_date: null,
                civil_status: null,
                occupation: null
            }),
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/owners/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.form.reset();
                        this.hide();
                    }
                });
            }else{
                this.form.post('/owners',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.$emit('selected',response.props.flash.data);
                        this.hide();
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>