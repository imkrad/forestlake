<template>
    <!-- style="--vz-modal-width: 800px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-dark-subtle" title="View Logs" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div v-if="selected.event === 'updated'">
                Updated <span class="text-warning fst-italic" v-for="(old,key,index) in selected.properties.old" v-bind:key="index">{{key}} : {{old}}, </span> to <span class="text-success fst-italic" v-for="(news,key,index) in selected.properties.attributes" v-bind:key="index">{{key}} : {{news}}, </span>
            </div>
            <div v-else>
                Created  <span class="text-success fst-italic" v-for="(news,key,index) in selected.properties.attributes" v-bind:key="index">{{key}} : {{news}}, </span>
            </div>
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
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
        hide(){
            this.showModal = false;
        }
    }
}
</script>