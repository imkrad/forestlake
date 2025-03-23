<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal style="--vz-modal-width: 900px;" v-model="showModal" header-class="p-3 bg-dark-subtle" title="Buy Lot" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-2">
                <BCol lg="12" class="mt-n1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel value="Owner" :message="form.errors.owner"/>
                            <Multiselect v-model="form.owner_id" @search-change="fetchOwner" placeholder="Select by Owner" :searchable="true" :close-on-select="true" label="name" :options="owners" />
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="addOwner()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Phase" :message="form.errors.phase_id"/>
                    <Multiselect v-model="form.phase_id" placeholder="Select Phase" :close-on-select="true" label="name" :options="phases" />
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Area" :message="form.errors.area_id"/>
                    <Multiselect v-model="form.area_id" placeholder="Select Area" :close-on-select="true" label="name" :options="areas" />
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Section" :message="form.errors.section_id"/>
                    <Multiselect v-model="form.section_id" placeholder="Select Area" :close-on-select="true" label="name" :options="sections" />
                </BCol>
                <BCol lg="12" class="mt-0 mb-n4"><hr class="text-muted"/></BCol>
                <BCol lg="12">
                    <div class="table-responsive mt-2">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th></th>
                                    <th >Lot</th>
                                    <th class="text-center">Block</th>
                                    <th class="text-center">Section</th>
                                    <th class="text-center">Area</th>
                                    <th  class="text-center">Phase</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody v-if="checkedItems.length > 0">
                                <tr v-for="(list,index) in checkedItems" v-bind:key="index" :class="(isItemChecked(list.id)) ? 'table-success' : (index == matchedRowIndex) ? 'table-warning' : ''" :id="'row-' + index">
                                    <td style="width: 7%;" class="text-center fs-10">{{index+1}}</td>
                                    <td>{{list.name}}</td>
                                    <td class="text-center">{{list.block}}</td>
                                    <td class="text-center">{{list.section}}</td>
                                    <td class="text-center">{{list.area}}</td>
                                    <td class="text-center">{{list.phase}}</td>
                                    <td style="width: 6%;" class="text-center"> 
                                        <b-button @click="openDeleteTest(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                                            <i class="ri-delete-bin-fill align-bottom"></i>
                                        </b-button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="6" class="text-center text-muted fs-12">No lots found/selected. Please use search box.</td>
                                </tr>
                            </tbody>
                        </table>
                        <simplebar data-simplebar style="max-height: 200px">
                            <div v-if="lots.length > 0">
                                <table class="table table-centered table-bordered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr class="fs-11">
                                            <th></th>
                                            <th class="text-center">Lot</th>
                                            <th class="text-center">Block</th>
                                            <th class="text-center">Section</th>
                                            <th class="text-center">Area</th>
                                            <th  class="text-center">Phase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(list,index) in lots" v-bind:key="list.value" :class="(isItemChecked(list.value)) ? 'table-success' : (index == matchedRowIndex) ? 'table-warning' : ''" :id="'row-' + index">
                                            <td style="width: 7%;" class="text-center"> 
                                                <input class="form-check-input me-1" type="checkbox" :checked="isItemChecked(list.id)" @change="toggleChecked(list,$event)">
                                            </td>
                                            <td class="text-center fs-11">{{list.name}}</td>
                                            <td style="width: 15%;" class="text-center fs-11">{{list.block}}</td>
                                            <td style="width: 15%;" class="text-center fs-11">{{list.section}}</td>
                                            <td style="width: 25%;" class="text-center fs-11">{{list.area}}</td>
                                            <td style="width: 15%;" class="text-center fs-11">{{list.phase}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </simplebar>
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
    props: ['sections','phases','areas'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                owner_id: null,
                phase_id: null,
                section_id: null,
                area_id: null,
                lot_id: null,
                lists: [],
                option: 'buy'
            }),
            checkedItems: [],
            owners: [],
            lots: [],
            showModal: false,
            editable: false
        }
    },
    watch: {
        "form.section_id"(newVal){
            (newVal) ? this.fetchLot() : '';
        },
        "form.area_id"(newVal){
            this.form.section_id = null;
            (newVal) ? this.fetchLot() : '';
        },
        "form.phase_id"(newVal){
            this.form.area_id = null;
            this.form.section_id = null;
            (newVal) ? this.fetchLot() : '';
        },
    },
    methods: { 
        show(id){
            this.form.reset();
            this.form.owner_id = id;
            this.showModal = true;
        },
        submit(){
            this.form.lists = this.checkedItems;
            this.form.post('/sales',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        fetchLot(code){
            axios.get('/search',{
                params: {
                    option: 'lots3',
                    section_id: this.form.section_id,
                    area_id_id: this.form.area_id_id,
                    phase_id: this.form.phase_id,
                }
            })
            .then(response => {
                this.lots = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchOwner(code){
            axios.get('/search',{
                params: {
                    option: 'owner',
                    code: code
                }
            })
            .then(response => {
                this.owners = response.data;
            })
            .catch(err => console.log(err));
        },
        toggleChecked(item, event) {
            const isChecked = event.target.checked;
            const itemId = item.value;

            if (isChecked) {
                // Add item to checkedItems if not already present
                if (!this.checkedItems.some(checkedItem => checkedItem.value === itemId)) {
                    this.checkedItems.push(item);
                    // Remove item from testservices
                    const testIndex = this.lots.findIndex(test => test.value === itemId);
                    if (testIndex !== -1) {
                        this.lots.splice(testIndex, 1);
                    }
                }
            } else {
                // Remove item from checkedItems if present
                const checkedIndex = this.checkedItems.findIndex(checkedItem => checkedItem.value === itemId);
                if (checkedIndex !== -1) {
                    this.checkedItems.splice(checkedIndex, 1);
                    // Restore item to testservices if it was unchecked
                    const itemToRestore = this.checkedItems.find(item => item.value === itemId);
                    if (itemToRestore) {
                        this.lots.push(itemToRestore);
                    }
                }
            }
        },
        isItemChecked(item) {
            return this.checkedItems.some(checkedItem => checkedItem.value === item);
        },
        openDeleteTest(data){
            const index = this.checkedItems.findIndex(test => test.value === data.value);
            if (index !== -1) {
                this.checkedItems.splice(index, 1);
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