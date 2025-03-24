<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 1000px;" header-class="p-3 bg-dark-subtle" title="View Grave" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h4>{{selected.name}}</h4>
                    <div class="hstack gap-3 flex-wrap">
                        <div class="text-muted">Birth Date : <span class="text-body fw-medium">{{selected.birth_date}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Death Date : <span class="text-body fw-medium">{{selected.death_date}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Burial Date : <span class="text-body fw-medium">{{selected.burial_date}}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Type : <span class="text-body fw-medium">{{selected.type.name}}</span></div>
                        <span class="text-primary fw-semibold mt-n2"><span class="fw-normal text-muted">Location : </span> Lot {{ selected.lot.lot }} - Block {{ selected.lot.block.block }} - Section {{ selected.lot.block.section.section.name }} - Area {{ selected.lot.block.section.area.name }} - {{ selected.lot.block.section.phase.name }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <!-- <div>
                        <a href="apps-ecommerce-add-product.html" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="ri-pencil-fill align-bottom"></i></a>
                    </div> -->
                </div>
            </div>
            <hr class="text-muted"/>
            <Map @set="handleCoordinates" :coordinate="selected.lot.coordinate" ref="map" class="leaflet-map"/>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
import Map from './Map.vue';
export default {
    components: { Map },
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
        handleCoordinates(coords) {
            this.coordinates = coords;
            this.form.longitude = this.coordinates.lng;
            this.form.latitude = this.coordinates.lat;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>