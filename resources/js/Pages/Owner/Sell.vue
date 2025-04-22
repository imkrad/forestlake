<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 900px;" header-class="p-3 bg-dark-subtle" title="New Owner" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h4>Lot {{selected.lot.lot}}</h4>
                    <div class="hstack gap-3 flex-wrap">
                        <div class="text-muted">Block : <span class="text-body fw-medium">{{selected.lot.block}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Section : <span class="text-body fw-medium">{{selected.lot.section}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Area : <span class="text-body fw-medium">{{selected.lot.area}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Phase : <span class="text-body fw-medium">{{selected.lot.phase}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Allowed Bodies : <span class="text-body fw-medium">{{selected.lot.deceaseds.length}} / {{ selected.lot.max_count }}</span></div>
                    </div>
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
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <InputLabel value="Owner" :message="form.errors.owner"/>
                                <Multiselect v-model="form.owner_id" @search-change="fetchOwner" placeholder="Select by Owner" :searchable="true" :close-on-select="true" label="name" :options="owners" />
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="addOwner()" variant="light" class="waves-effect waves-light ms-1" style="margin-top: 20px;"><i class="ri-add-circle-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                    <BCol lg="12" class="mt-2">
                        <InputLabel for="name" value="Date" :message="form.errors.email"/>
                        <TextInput type="date" v-model="form.date" class="form-control" placeholder="Input enter date" @input="handleInput('date')" :light="true"/>
                    </BCol>
                </BRow>
            </form>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Owner @selected="set" ref="owner"/>
</template>
<script>
import Owner from '../Owner/Create.vue';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, Owner, InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: useForm({
                lot_id: null,
                date: new Date().toISOString().split('T')[0],
                owner_id: null,
            }),
            id: null,
            owners: [],
            showModal: false,
        }
    },
    methods: { 
        show(data,id){
            this.id = id;
            this.selected = data;
            this.form.lot_id = this.selected.lot.id;
            this.showModal = true;
        },
        fetchOwner(code){
            if(code != null){
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
            }else{
                this.owners = [];
            }
        },
        submit(){
            this.form.post('/sell',{
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