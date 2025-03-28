<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-dark-subtle" title="Update Grave" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                <BCol lg="12" class="mt-n1">
                    <InputLabel for="name" value="Fullname" :message="form.errors.name"/>
                    <TextInput type="text" v-model="form.name" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Birth Date" :message="form.errors.birth_date"/>
                    <TextInput type="date" v-model="form.birth_date" class="form-control" placeholder="Please enter date" @input="handleInput('birth_date')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Death Date" :message="form.errors.death_date"/>
                    <TextInput type="date" v-model="form.death_date" class="form-control" placeholder="Please enter date" @input="handleInput('death_date')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Burial Date" :message="form.errors.burial_date"/>
                    <TextInput type="date" v-model="form.burial_date" class="form-control" placeholder="Please enter date" @input="handleInput('burial_date')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Type" :message="form.errors.type_id"/>
                    <Multiselect v-model="form.type_id"  placeholder="Select type" :close-on-select="true" label="name" :options="types" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Cause of Death" :message="form.errors.cause_of_death"/>
                    <TextInput type="text" v-model="form.cause_of_death" class="form-control" placeholder="Please enter cause" @input="handleInput('cause_of_death')" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Update</b-button>
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
    props: ['types'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                birth_date: null,
                death_date: null,
                burial_date: null,
                cause_of_death: null,
                type_id: null,
                lot_id: null
            }),
            originalData: {
                birth_date: null,
                death_date: null,
                burial_date: null,
            },
            lots: [],
            owners: [],
            showModal: false,
            editable: false
        }
    },
    mounted() {
        this.formatDates();
    },
    methods: { 
        show(data){
            console.log(data);
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.birth_date = this.formatDate(data.birth_date);
            this.form.death_date = this.formatDate(data.death_date);
            this.form.burial_date = this.formatDate(data.burial_date);
            this.form.cause_of_death = data.cause_of_death;
            this.form.type_id = data.type_id;
            this.showModal = true;
        },
        formatDates() {
            this.form.birth_date = this.formatDate(this.originalData.birth_date);
            this.form.death_date = this.formatDate(this.originalData.death_date);
            this.form.burial_date = this.formatDate(this.originalData.burial_date);
        },
        formatDate(dateString) {
            if (!dateString) return ""; // Handle null or undefined values

            let date = new Date(dateString);
            if (isNaN(date.getTime())) {
                // Try parsing manually if it's in "Month Day, Year" format
                date = new Date(Date.parse(dateString));
                if (isNaN(date.getTime())) return ""; // If still invalid, return empty
            }
            
            return date.toISOString().split("T")[0]; // Convert to YYYY-MM-DD
        },
        submit(){
            this.form.put('/graves/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.form.reset();
                    this.hide();
                }
            });
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