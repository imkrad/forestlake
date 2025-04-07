<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 850px;" header-class="p-3 bg-dark-subtle" title="View Owner" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h4>{{selected.lastname}}, {{selected.firstname}} {{selected.middlename[0]}}.</h4>
                    <div class="hstack gap-3 flex-wrap">
                        <div class="text-muted">Email : <span class="text-body fw-medium">{{selected.email}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Contact number: <span class="text-body fw-medium">{{selected.contact_number }}</span></div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <!-- <div>
                        <a href="apps-ecommerce-add-product.html" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="ri-pencil-fill align-bottom"></i></a>
                    </div> -->
                </div>
            </div>
            <hr class="text-muted"/>
            <table class="table align-middle table-nowrap mb-0">
                <thead class="table-light text-muted thead-fixed">
                    <tr>
                        <th class="text-center">Lot</th>
                        <th class="text-center">Block</th>
                        <th class="text-center">Section</th>
                        <th class="text-center">Area</th>
                        <th class="text-center">Phase</th>
                        <th class="text-center">Occupied</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <tr v-for="(list, index) of selected.lots" :key="index">
                        <td class="text-center">{{ list.lot.lot }}</td>
                        <td class="text-center">{{ list.lot.block }}</td>
                        <td class="text-center">{{ list.lot.section }}</td>
                        <td class="text-center">{{ list.lot.area }}</td>
                        <td class="text-center">{{ list.lot.phase }}</td>
                        <td class="text-center">{{list.lot.deceaseds.length}} / {{ list.lot.max_count }}</td>
                        <td class="text-end">
                            <b-button @click="openView(list)" variant="info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                            <b-button @click="openSell(list)" variant="danger" class="me-1" v-b-tooltip.hover title="Sell" size="sm">
                                <i class="ri-hand-coin-fill align-bottom"></i>
                            </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
    <Show ref="view"/>
    <Sell @update="hide" ref="sell"/>
</template>
<script>
import Sell from './Sell.vue';
import Show from './Show.vue';
export default {
    components: { Show, Sell },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            showModal: false,
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        openView(data){
          this.$refs.view.show(data);
        },
        openSell(data){
          this.$refs.sell.show(data,this.selected.id);
        },
        hide(){
            this.$emit('update',true);
            this.showModal = false;
        }
    }
}
</script>