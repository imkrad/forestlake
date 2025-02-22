<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 650px;" header-class="p-3 bg-dark-subtle" title="Create Grave" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                
                <BCol lg="12" class="mt-n1">
                    <InputLabel for="name" value="Fullname" :message="form.errors.name"/>
                    <TextInput type="text" v-model="form.name" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-n1 mb-n4">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="4">
                    <InputLabel for="name" value="Section" :message="form.errors.section_id"/>
                    <Multiselect v-model="form.section_id" placeholder="Filter by Section" :close-on-select="true" label="name" :options="sections" />
                </BCol>
                <BCol lg="4">
                    <InputLabel for="name" value="Block" :message="form.errors.block_id"/>
                    <Multiselect v-model="form.block_id" placeholder="Filter by Block" :close-on-select="true" label="name" :options="blocks" />
                </BCol>
                <BCol lg="4">
                    <InputLabel for="name" value="Lot" :message="form.errors.lot_id"/>
                    <Multiselect v-model="form.lot_id" placeholder="Filter by Lot" :close-on-select="true" label="name" :options="lots" />
                </BCol>
                <BCol lg="12" class="mb-n2 mt-0">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Birth Date" :message="form.errors.birth_date"/>
                    <TextInput type="date" v-model="form.birth_date" class="form-control" placeholder="Please enter date" @input="handleInput('birth_date')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Death Date" :message="form.errors.death_date"/>
                    <TextInput type="date" v-model="form.death_date" class="form-control" placeholder="Please enter date" @input="handleInput('death_date')" :light="true"/>
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel for="name" value="Burial Date" :message="form.errors.burial_date"/>
                    <TextInput type="date" v-model="form.burial_date" class="form-control" placeholder="Please enter date" @input="handleInput('burial_date')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Cause of Death" :message="form.errors.cause_of_death"/>
                    <TextInput type="text" v-model="form.cause_of_death" class="form-control" placeholder="Please enter cause" @input="handleInput('cause_of_death')" :light="true"/>
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
    props: ['sections'],
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
                block_id: null,
                section_id: null,
                lot_id: null
            }),
            blocks: [],
            lots: [],
            showModal: false,
            editable: false
        }
    },
    watch: {
        "form.section_id"(newVal){
            if(!newVal){
                this.form.block_id = null;
                this.form.lot_id = null;
            }
            this.fetchBlock(newVal);
        },
        "form.block_id"(newVal){
            if(!newVal){
                this.form.lot_id = null;
            }
            this.fetchLot(newVal);
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/graves/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.form.reset();
                        this.hide();
                    }
                });
            }else{
                this.form.post('/graves',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.hide();
                    },
                });
            }
        },
        fetchBlock(code){
            axios.get('/search',{
                params: {
                    option: 'blocks',
                    code: code
                }
            })
            .then(response => {
                this.blocks = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchLot(code){
            axios.get('/search',{
                params: {
                    option: 'lots',
                    code: code
                }
            })
            .then(response => {
                this.lots = response.data;
            })
            .catch(err => console.log(err));
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