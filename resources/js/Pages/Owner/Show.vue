<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 900px;" header-class="p-3 bg-dark-subtle" title="View Lot" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
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