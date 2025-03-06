<template>
    <Head title="Dashboard" />
    <div class="row">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-9">
            <div class="row g-3 h-100">
                <div class="col-lg-3 col-md-8"  v-for="(list,index) in counts" v-bind:key="index">
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
                <div class="col-lg-12 mt-n1">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">List of Records</h4>
                            <div class="flex-shrink-0">
                                <button @click="openCreate" type="button" class="btn btn-primary waves-effect waves-light">New Record</button>
                            </div>
                        </div>
                        <div class="card-body" style="height: calc(100vh - 450px); overflow: auto;">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="text-muted">
                                            <th style="width: 3%;">#</th>
                                            <th class="text-center" style="width: 20%;">Owner</th>
                                            <th class="text-center" style="width: 20%;">Deceased</th>
                                            <th class="text-center" style="width: 15%;">Lot</th>
                                            <th class="text-center" style="width: 15%;">Death Date</th>
                                            <th class="text-center" style="width: 15%;">Buried Date</th>
                                            <th class="text-center" style="width: 10%;">

                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(list,index) in lists" v-bind:key="index">
                                            <td class="text-center"> 
                                                {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                                            </td>
                                            <td class="text-center">
                                                <h5 class="fs-13 mb-0 text-dark">{{list.lot.owner.owner.name}}</h5>
                                            </td>
                                            <td class="text-center fs-12">{{list.name}}</td>
                                            <td class="text-center fs-12">{{list.lot.number}}</td>
                                            <td class="text-center fs-12">{{list.death_date}}</td>
                                            <td class="text-center fs-12">{{list.burial_date}}</td>
                                            <td class="text-end">
                                                <b-button @click="openView(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </b-button>
                                                <b-button @click="openEdit(list,index)" variant="soft-warning" class="me-1" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Create :sections="sections" ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Create.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader, Create }, 
    props: ['counts','sections'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            keyword: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/graves';
            axios.get(page_url,{
                params : {
                    keyword: this.keyword,
                    count: 10,
                    option: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openCreate(){
            this.$refs.create.show();
        }
    }
}
</script>