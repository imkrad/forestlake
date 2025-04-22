<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-dark-subtle" title="Create Section" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                
                <BCol lg="4" class="mt-n1">
                    <InputLabel for="name" value="Phase" :message="form.errors.phase_id"/>
                    <Multiselect v-model="form.phase_id" placeholder="Select Phase" :close-on-select="true" label="name" :options="phases" />
                </BCol>
                <BCol lg="4" class="mt-n1">
                    <InputLabel for="name" value="Area" :message="form.errors.area_id"/>
                    <Multiselect v-model="form.area_id" placeholder="Select Area" :close-on-select="true" label="name" :options="areas" />
                </BCol>
                <BCol lg="4" class="mt-n1">
                    <InputLabel for="name" value="Section" :message="form.errors.section_id"/>
                    <Multiselect v-model="form.section_id" placeholder="Select Area" :close-on-select="true" object label="name" :options="sections" />
                </BCol>
                <BCol lg="12">
                    <div class="table-responsive mt-n2 mb-2">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th class="fs-14 text-primary align-middle">LIST OF BLOCKS ({{ totalcount + form.blocks.length }} blocks are occupied out of {{ (form.section_id) ? form.section_id.limit : 0 }} blocks)</th>
                                    <th>
                                        <b-button @click="addBlock" v-if="totalcount < form.section_id?.limit" variant="primary" class=" float-end btn-sm">Add Block</b-button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </BCol>
            </BRow>
            <p class="text-muted" v-if="totalcount == form.section_id?.limit">Full</p>
            <BRow class="g-3 mt-n1" v-for="(block, index) in form.blocks" :key="index">
                <BCol lg="4" class="mt-1">
                    <InputLabel value="Block" :message="form.errors['blocks.'+index+'.block']"/>
                    <TextInput
                    :value="block.block = Number(index + 1) + Number(totalcount)"
                    type="text"
                    class="form-control"
                    readonly
                    :light="true"
                    />
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel value="Size" :message="form.errors['blocks.'+index+'.size']"/>
                    <TextInput v-model="block.size" type="text" class="form-control" placeholder="Please enter size" @input="handleInput('size')" :light="true" />
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel value="Lots" :message="form.errors['blocks.'+index+'.lots']"/>
                    <TextInput v-model="block.lots" type="text" class="form-control" placeholder="Please enter lots" @input="handleInput('lots')" :light="true" />
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
    props: ['phases','areas','sections'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                section_id: null,
                area_id: null,
                phase_id: null,
                blocks: [],
            }),
            totalcount: 0,
            showModal: false,
            editable: false
        }
    },
    watch: {
        "form.section_id"(newVal){
            if(newVal){
                this.fetchCount();
            }
        },
        "form.area_id"(newVal){
            if(newVal){
                this.fetchCount();
            }
        },
        "form.phase_id"(newVal){
            if(newVal){
                this.fetchCount();
            }
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        fetchCount(){
            if (this.form.phase_id && this.form.area_id && this.form.section_id) {
                
                axios.get('/blockcount',{
                    params : {
                        phase_id: this.form.phase_id,
                        area_id: this.form.area_id,
                        section_id: this.form.section_id
                    }
                })
                .then(response => {
                    if(response){
                        this.totalcount = response.data;     
                    }
                })
                .catch(err => console.log(err));
            }
        },
        submit(){
            if(this.editable){
                this.form.put('/lots/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.form.reset();
                        this.hide();
                    }
                });
            }else{
                this.form.post('/lots',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
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
        },
        addBlock() {
            if (this.form.section_id && this.form.blocks.length < this.form.section_id.limit) {
                this.form.blocks.push({ 
                    block: `${this.form.blocks.length + 1 + this.totalcount}`, 
                    size: null, 
                    lots: null 
                });
            }
        },
    }
}
</script>