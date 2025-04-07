<template>
    <Head title="Grave Management" />
    <BRow>
      <BCol lg="12">
        <BCard no-body id="tasksList">
          <BCardHeader class="border-0">
            <div class="d-flex align-items-center">
              <h5 class="card-title mb-0 flex-grow-1">Grave Management</h5>
              <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                  <BButton variant="soft-danger" class="me-1" id="remove-actions" @click="deleteMultiple">
                    <i class="ri-delete-bin-2-line"></i>
                  </BButton>
                  <!-- <BButton variant="danger" class="add-btn" @click="toggleCreate">
                    <i class="ri-add-line align-bottom me-1"></i> Create Block
                  </BButton> -->
                </div>
              </div>
            </div>
          </BCardHeader>
          <BCardBody class="border border-dashed border-end-0 border-start-0">
            <BFrom>
              <BRow class="g-3">
                <BCol xxl="8" sm="12">
                  <div class="search-box">
                    <input type="text" class="form-control search bg-light border-light"
                      placeholder="Search owner name" v-model="keyword" />
                    <i class="ri-search-line search-icon"></i>
                  </div>
                </BCol>

                <BCol xxl="2" sm="4">
                  <input type="date" class="form-control search bg-light border-light"
                  placeholder="Search owner name" v-model="death" />
                </BCol>
                
                <BCol xxl="2" sm="4">
                    <div class="d-grid gap-2" >
                      <BButton variant="dark" class="add-btn" @click="toggleCreate">
                        <i class="ri-add-line align-bottom me-1"></i>New Decease
                      </BButton>
                    </div>

                </BCol>
              </BRow>
            </BFrom>
          </BCardBody>
          <BCardBody>
            <div class="table-responsive table-card mb-4">
                <simplebar data-simplebar style="height: calc(100vh - 420px);">
              <table class="table align-middle table-nowrap mb-0">
                <thead class="table-light text-muted thead-fixed">
                  <tr>
                    <th style="width: 5%;"></th>
                    <th>Name</th>
                    <th style="width: 12%;" class="text-center">Birth Date</th>
                    <th style="width: 12%;" class="text-center">Death Date</th>
                    <th style="width: 12%;" class="text-center">Burial Date</th>
                    <th style="width: 15%;" class="text-center">Type</th>
                    <th style="width: 7%;" ></th>
                  </tr>
                </thead>
                <tbody class="list form-check-all">
                    <tr v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center"> 
                            {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                        </td>
                        <td>
                            <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                            <p class="fs-12 text-muted mb-0">Lot {{ list.lot.lot }} - Block {{ list.lot.block.block }} - Section {{ list.lot.block.section.section.name }} - Area {{list.lot.block.section.area.name }} - {{ list.lot.block.section.phase.name }}</p>
                        </td>
                        <td class="text-center fs-12">{{list.birth_date}}</td>
                        <td class="text-center fs-12">{{list.death_date}}</td>
                        <td class="text-center fs-12">{{list.burial_date}}</td>
                        <td class="text-center fs-12">
                          <span :class="'badge '+list.type.color">{{list.type.name}}</span>
                        </td>
                        <td class="text-end">
                          <b-button @click="openView(list)" variant="info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                            <b-button @click="openUpdate(list)" variant="danger" class="me-1" v-b-tooltip.hover title="Update" size="sm">
                                <i class="ri-skull-fill align-bottom"></i>
                            </b-button>
                            <b-button @click="openEdit(list,index)" variant="warning" class="me-1" v-b-tooltip.hover title="Edit" size="sm">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </b-button>
                        </td>
                    </tr>
                </tbody>
              </table>
              <div class="noresult" v-if="lists.length < 1">
                <div class="text-center mt-5">
                  <h5 class="mt-2">Sorry! No Result Found</h5>
                  <p class="text-muted mb-0">
                    We've searched more than 200k+ lots. We did not find any
                   lots for you search.
                  </p>
                </div>
              </div>
            </simplebar>
            </div>

            <div class="d-flex justify-content-end" style="margin-top: -30px;">
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
          </BCardBody>
        </BCard>
      </BCol>
    </BRow>
    <Create :sections="sections" @update="fetch()" ref="create"/>
    <Edit :types="types" ref="edit"/>
    <Update @update="fetch()" :types="types" ref="update"/>
    <View ref="view"/>
</template>
<script>
import _ from 'lodash';
import View from './View.vue';
import Edit from './Edit.vue';
import Update from './Update.vue';
import Create from './Create.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader, Multiselect, simplebar, Create, Edit, View, Update }, 
    props: ['sections','types'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            keyword: null,
            death: null,
            index: null,
        }
    },
    watch: {
        "keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "death"(newVal){
            this.fetch();
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
                    status: this.status,
                    death: this.death,
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
        toggleCreate(){
            this.$refs.create.show();
        },
        openEdit(data){
          this.$refs.edit.show(data);
        },
        openView(data)
        {
          this.$refs.view.show(data);
        },
        openUpdate(data)
        {
          this.$refs.update.show(data);
        }
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>