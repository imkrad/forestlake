<template>
    <Head title="Report & Analytics" />
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body border-bottom bg-white">
                    <p class="mb-0 ms-2 text-primary fs-15 fw-semibold">{{currentMonth}} {{ currentYear }} Reports</p>
                </div>
                <div class="card-body" style="height: calc(100vh - 275px); overflow: auto;">
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n1 p-0">
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <!-- <div class="flex-shrink-0 avatar-xs"><span class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-error-warning-line fs-20 text-warning"></i></span></div> -->
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">Memorial Service</h6>
                                    <p class="fs-11 mb-0 text-muted">Ceremony held to honor and remember</p>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h6 class="mt-2 me-2 fs-12">{{deceased[0].total}}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <!-- <div class="flex-shrink-0 avatar-xs"><span class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-error-warning-fill fs-20 text-danger"></i></span></div> -->
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">Bone Transfer</h6>
                                    <p class="fs-11 mb-0 text-muted">Relocation of human skeletal remains</p>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h6 class="mt-2 me-2 fs-12">{{deceased[1].total}}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <!-- <div class="flex-shrink-0 avatar-xs"><span class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-alert-fill fs-20 text-success"></i></span></div> -->
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">Exhumation</h6>
                                    <p class="fs-11 mb-0 text-muted">Digging up and removing human remains</p>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h6 class="mt-2 me-2 fs-12">{{deceased[2].total}}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Activity Logs</h4>
                            <div class="flex-shrink-0">
                                <!-- <button @click="openCreate" type="button" class="btn btn-primary waves-effect waves-light">New Record</button> -->
                            </div>
                        </div>
                        <div class="card-body" style="height: calc(100vh - 275px); overflow: auto;">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="table-light fs-11">
                                        <tr>
                                            <th style="width: 5%;"></th>
                                            <th style="width: 15%;">Log Name</th>
                                            <th>Description</th>
                                            <!-- <th>Properties</th> -->
                                            <th class="text-center">Type</th>
                                            <th class="text-center">User</th>
                                            <th class="text-center">Date</th>
                                            <th style="width: 7%;" ></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(list,index) in logs.data" v-bind:key="index">
                                            <td class="text-center"> 
                                                {{ index + 1 }}.
                                            </td>
                                            <td >{{ list.log_name }}</td>
                                            <td >{{ list.description }}</td>
                                            <!-- <td >{{ list }}</td> -->
                                            <td class="text-center">{{ list.event }}</td>
                                            <td class="text-center">{{ list.causer.profile.name }}</td>
                                            <td class="text-center">{{ list.created_at }}</td>
                                            <td class="text-end">
                                                <b-button @click="openView(list)" variant="info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </b-button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <View ref="view"/>
</template>
<script>
import _ from 'lodash';
import View from './View.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader, View }, 
    props: ['logs','deceased'],
    data(){
        const now = new Date();
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            filter: {
                keyword: null,
                status: null,
                sortby: 'Created At',
                sort: 'desc'
            },
            index: null,
            currentMonth: now.toLocaleString('default', { month: 'long' }),
            currentYear: now.getFullYear()
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
        openView(data)
        {
          this.$refs.view.show(data);
        },
    }
}
</script>