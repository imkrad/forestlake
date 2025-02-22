<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-dark-subtle" title="Add Lot" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Section" :message="form.errors.section_id"/>
                    <Multiselect v-model="form.section_id" placeholder="Filter by Section" :close-on-select="true" label="name" :options="sections" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Block" :message="form.errors.block_id"/>
                    <Multiselect v-model="form.block_id" placeholder="Filter by Block" :close-on-select="true" label="name" :options="blocks" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Lot" :message="form.errors.lot_id"/>
                    <Multiselect v-model="form.lot_id" placeholder="Filter by Lot" :close-on-select="true" label="name" :options="lots" />
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
                owner_id: null,
                block_id: null,
                section_id: null,
                lot_id: null,
                option: 'add'
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
        show(id){
            this.form.reset();
            this.form.owner_id = id;
            this.showModal = true;
        },
        submit(){
            this.form.post('/sales',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.$emit('selected',response.props.flash.data);
                    this.hide();
                },
            });
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