<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-dark-subtle" title="View Section" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <div class="hstack gap-3 flex-wrap">
                        <div class="text-muted">Section : <span class="text-body fw-medium">{{selected.section.section.name}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Area : <span class="text-body fw-medium">{{selected.section.area.name}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Phase : <span class="text-body fw-medium">{{selected.section.phase.name}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Available : <span class="text-body fw-medium">{{availableCount}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Unavailable : <span class="text-body fw-medium">{{unavailableCount}}</span></div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    
                </div>
            </div>
            <hr class="text-muted"/>
            <div style="height: calc(100vh - 650px); overflow: auto;">
            <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light text-muted thead-fixed">
                        <tr>
                            <th class="text-center" style="width: 4%;">#</th>
                            <th >Lot Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Availability</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            <tr v-for="(list, index) of selected.lots" :key="index">
                                <td class="text-center"> 
                                    {{ index + 1 }}.
                                </td>
                                <td>
                                    <h5 class="fs-13 mb-0 text-dark">Lot {{list.lot}} - Block {{ list.block.block }}</h5>
                                </td>
                                <td class="text-center fs-12">{{list.price}}</td>
                                <td class="text-center">
                                    <i v-if="list.is_available" class="ri-checkbox-circle-fill text-success fs-18"></i>
                                    <i v-else class="ri-close-circle-fill text-danger fs-18"></i>
                                </td>
                                <td class="text-center">
                                    <span :class="'badge '+list.status.color">{{list.status.name}}</span>
                                </td>
                                <td class="text-end">
                                    <b-button @click="openView(list)" variant="info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                        <i class="ri-eye-fill align-bottom"></i>
                                    </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
    <View ref="view"/>
</template>
<script>
import _ from 'lodash';
import View from './ViewLot.vue';
export default {
    components: { View },
    data(){
        return {
            currentUrl: window.location.origin,
            id : null,
            area: null,
            selected: null,
            showModal: false,
        }
    },
    computed: {
        availableCount() {
            if (this.selected && this.selected.lots) {
                return this.selected.lots.filter(lot => lot.is_available).length;
            }
            return 0;
        },
        unavailableCount() {
            if (this.selected && this.selected.lots) {
                return this.selected.lots.filter(lot => !lot.is_available).length;
            }
            return 0;
        }
    },
    methods: { 
        show(data,area){
            this.id = data;
            this.area = area;
            this.fetch();
            this.showModal = true;
        },
        fetch(){
            axios.get('/section',{
                params: {
                    id: this.id,
                    area: this.area
                }
            })
            .then(response => {
                this.selected = response.data;
                if (this.selected && this.selected.lots) {
                    this.selected.lots.sort((a, b) => {
                        return b.is_available - a.is_available;  // Available first (true > false)
                    });
                }
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.show(data);
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>