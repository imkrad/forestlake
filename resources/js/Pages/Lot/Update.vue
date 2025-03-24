<template>
    <b-modal v-model="showModal" header-class="p-3 bg-dark-subtle" title="Update Lot" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow class="g-3 mt-1">
                <BCol lg="12">
                    <div class="table-responsive mt-n2 mb-2">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th class="fs-14 text-primary align-middle">
                                        LOT {{ selected.lot }} - BLOCK {{ selected.block }} - SECTION {{ selected.section }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </BCol>
            </BRow>
            <BRow class="g-3 mt-n1">
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Price" :message="form.errors.price"/>
                    <Amount @amount="amount" ref="testing" :readonly="false" @input="handleInput('amount')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Area" :message="form.errors.area"/>
                    <TextInput v-model="form.area" type="text" class="form-control" placeholder="Please enter area" @input="handleInput('area')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel value="Max Count Allowed (Bodies Buried)" :message="form.errors.max_count"/>
                    <TextInput v-model="form.max_count" type="number" class="form-control" placeholder="Please enter count" @input="handleInput('max_count')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-0 mb-0">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="6" class="mt-n2">
                    <InputLabel value="Longitude" :message="form.errors.longitude"/>
                    <TextInput v-model="form.longitude" type="text" class="form-control" placeholder="Please enter longitude" @input="handleInput('longitude')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-n2">
                    <InputLabel value="Latitude" :message="form.errors.latitude"/>
                    <TextInput v-model="form.latitude" type="text" class="form-control" placeholder="Please enter latitude" @input="handleInput('latitude')" :light="true" />
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
import {VMoney} from 'v-money';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Amount },
    directives: {money: VMoney},
    data(){
        return {
            currentUrl: window.location.origin,
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '₱',
                precision: 2,
                masked: false
            },
            form: useForm({
                id: null,
                area: null,
                price: null,
                longitude: null,
                latitude: null,
                max_count: null
            }),
            selected: null,
            showModal: false
        }
    },
    methods: {
        amount(val){
            this.form.price = val;
        }, 
        show(data){
            this.selected = data;
            this.form.reset();
            this.form.id = data.id;
            this.showModal = true;
        },
        submit(){
            this.form.put('/lots/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',response.props.flash.data.data);
                    this.hide();
                }
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();         
            this.$refs.testing.empty();
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