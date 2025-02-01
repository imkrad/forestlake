<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-dark-subtle" title="Create Section" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                
                <BCol lg="4" class="mt-n1">
                    <InputLabel for="name" value="Section Name" :message="form.errors.name"/>
                    <TextInput type="text" v-model="form.name" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="8" class="mt-n1">
                    <InputLabel for="name" value="Description" :message="form.errors.description"/>
                    <TextInput type="text" v-model="form.description" class="form-control" placeholder="Please enter description" @input="handleInput('description')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <div class="table-responsive mt-n2 mb-2">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th class="fs-14 text-primary align-middle">LIST OF BLOCKS ({{ form.blocks.length }} blocks)</th>
                                    <th>
                                        <b-button @click="addBlock" variant="primary" class=" float-end btn-sm">Add Block</b-button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </BCol>
            </BRow>
            <BRow class="g-3 mt-n1" v-for="(block, index) in form.blocks" :key="index">
                <BCol lg="2" class="mt-1">
                    <InputLabel value="Number" :message="form.errors['blocks.'+index+'.number']"/>
                    <TextInput v-model="block.number" type="text" class="form-control" placeholder="Please enter campus" @input="handleInput('block')" :light="true" />
                </BCol>
                <BCol lg="5" class="mt-1">
                    <InputLabel value="Name" :message="form.errors['blocks.'+index+'.name']"/>
                    <TextInput v-model="block.name" type="text" class="form-control" placeholder="Please enter campus" @input="handleInput('block')" :light="true" />
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Area" :message="form.errors['blocks.'+index+'.area']"/>
                    <TextInput v-model="block.area" type="text" class="form-control" placeholder="Please enter campus" @input="handleInput('block')" :light="true" />
                </BCol>
                <BCol lg="2" class="mt-1">
                    <InputLabel value="Lots" :message="form.errors['blocks.'+index+'.lots']"/>
                    <TextInput v-model="block.lots" type="text" class="form-control" placeholder="Please enter campus" @input="handleInput('block')" :light="true" />
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                number: null,
                description: null,
                area: null,
                blocks: [{ name: '', number: null, area: null, lots: null }],
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
            this.form.blocks.push({ 
                name: '', 
                number: null, 
                area: null, 
                lots: null
            });
        },
    }
}
</script>