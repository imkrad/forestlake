<template>
    <Head title="Dashboard" />
    <div class="row">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-9">
            <div class="row h-100">
                <div class="col-lg-4 col-md-6"  v-for="(list,index) in lists" v-bind:key="index">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-primary rounded-circle fs-3 material-shadow">
                                        <i :class="list.icon+' align-middle '+list.color"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> {{list.name}}</p>
                                    <h4 class=" mb-0">{{list.count}}</h4>
                                </div>
                                <!-- <div class="flex-shrink-0 align-self-end">
                                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-fill align-middle me-1"></i>6.24 %<span> </span></span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader }, 
    props: ['lists'],
    data(){
        return {
            currentUrl: window.location.origin,
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        }
    },
    created(){
       
    },
    methods: {

    }
}
</script>