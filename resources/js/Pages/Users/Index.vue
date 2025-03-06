<template>
    <Head title="Owner Management" />
    <BRow>
      <BCol lg="12">
        <BCard no-body id="tasksList">
          <BCardHeader class="border-0">
            <div class="d-flex align-items-center">
              <h5 class="card-title mb-0 flex-grow-1">Staff Management</h5>
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
                <BCol xxl="10" sm="12">
                  <div class="search-box">
                    <input type="text" class="form-control search bg-light border-light"
                      placeholder="Search User" v-model="keyword" />
                    <i class="ri-search-line search-icon"></i>
                  </div>
                </BCol>

                <BCol xxl="2" sm="4">
                    <div class="d-grid gap-2" >
                      <BButton variant="dark" class="add-btn" @click="toggleCreate">
                        <i class="ri-add-line align-bottom me-1"></i>New Staff
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
                    <th></th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 15%;" class="text-center">Role</th>
                    <th style="width: 15%;" class="text-center">Email</th>
                    <th style="width: 15%;" class="text-center">Contact no.</th>
                    <th style="width: 15%;" class="text-center">Status</th>
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
                        </td>
                        <td class="text-center fs-12">{{list.role}}</td>
                        <td class="text-center fs-12">{{list.email}}</td>
                        <td class="text-center fs-12">{{list.mobile}}</td>
                        <td class="text-center fs-12">
                            <span v-if="list.is_active" class="badge bg-success">Active</span>
                            <span v-else class="badge bg-danger">Inactive</span>
                        </td>
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
    <Create @update="fetch()" ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Create.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader, Multiselect, simplebar, Create }, 
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            keyword: null,
            index: null,
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
        fetch(page_url){
            page_url = page_url || '/users';
            axios.get(page_url,{
                params : {
                    keyword: this.keyword,
                    status: this.status,
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