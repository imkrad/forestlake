<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-dark-subtle" title="Update Type" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h5 class="fs-13 mb-0 text-dark">{{selected.name}}</h5>
                    <p class="fs-12 text-muted mb-0">Lot {{selected.lot.lot }} - Block {{ selected.lot.block.block }} - Section {{ selected.lot.block.section.section.name }} - Area {{selected.lot.block.section.area.name }} - {{ selected.lot.block.section.phase.name }}</p>
                    <!-- <div class="hstack">
                        <div class="text-muted">Block : <span class="text-body fw-medium">{{selected.lot.block.block}}</span></div>
                        <div class="vr"></div> <br />
                        <div class="text-muted">Section : <span class="text-body fw-medium">{{selected.lot.block.section.section.name}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Area : <span class="text-body fw-medium">{{selected.lot.block.section.area.name}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Phase : <span class="text-body fw-medium">{{selected.lot.block.section.phase.name}}</span></div>
                       
                    </div> -->
                </div>
                <div class="flex-shrink-0">
                    <!-- <div>
                        <a href="apps-ecommerce-add-product.html" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="ri-pencil-fill align-bottom"></i></a>
                    </div> -->
                </div>
            </div>
            <hr class="text-muted"/>
            <form class="customform">
                <BRow class="g-3 mt-2">
                    <BCol lg="12" class="mt-2">
                        <Multiselect v-model="form.type_id" placeholder="Select Type" :searchable="true" :close-on-select="true" label="name" :options="filteredTypes" />
                    </BCol>
                    <BCol lg="12" v-if="form.type_id == 20">
                        <InputLabel for="name" value="Date" :message="form.errors.deceased_at"/>
                        <TextInput type="date" v-model="form.deceased_at" class="form-control" placeholder="Please enter date" @input="handleInput('deceased_at')" :light="true"/>
                    </BCol>
                    <BCol lg="12" v-if="form.type_id == 20" class="mt-0">
                        <InputLabel for="name" value="Cementery" :message="form.errors.cementery"/>
                        <TextInput type="text" v-model="form.cementery" class="form-control" placeholder="Please enter cementery" @input="handleInput('cementery')" :light="true"/>
                    </BCol>
                </BRow>
            </form>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput },
    props: ['types'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: useForm({
                id: null,
                type_id: null,
                deceased_at: null,
                cementery: null,
                information: null
            }),
            showModal: false,
        }
    },
    computed: {
        filteredTypes() {
        return this.types.filter(type => type.name !== 'Memorial Service')
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = this.selected.id;
            this.showModal = true;
        },
        fetchOwner(code){
            axios.get('/search',{
                params: {
                    option: 'owner',
                    id: this.id,
                    code: code
                }
            })
            .then(response => {
                this.owners = response.data;
            })
            .catch(err => console.log(err));
        },
        submit(){
            this.form.post('/changetype',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    // this.$emit('selected',response.props.flash.data);
                    this.hide();
                },
            });
        },
        addOwner(){
            this.$refs.owner.show();
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>