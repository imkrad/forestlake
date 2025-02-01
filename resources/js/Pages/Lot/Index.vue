<template>
    <Head title="Lot Plotting & Mapping" />
    <BRow>
      <BCol lg="12">
        <BCard no-body id="tasksList">
          <BCardHeader class="border-0">
            <div class="d-flex align-items-center">
              <h5 class="card-title mb-0 flex-grow-1">Lot Plotting & Mapping</h5>
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
                <BCol xxl="5" sm="12">
                  <div class="search-box">
                    <input type="text" class="form-control search bg-light border-light"
                      placeholder="Search a lot" v-model="searchQuery" />
                    <i class="ri-search-line search-icon"></i>
                  </div>
                </BCol>

                <BCol xxl="3" sm="4">
                    <Multiselect v-model="status" placeholder="Filter by Status" :close-on-select="true" label="name" :options="lot_statuses" />
                </BCol>

                <BCol xxl="3" sm="4">
                  <div class="input-light">
                    <Multiselect v-model="filtervalue1" :close-on-select="true" :searchable="true" :create-option="true"
                      :options="[
                        { value: 'All', label: 'All' },
                        { value: 'New', label: 'New' },
                        { value: 'Pending', label: 'Pending' },
                        { value: 'Inprogress', label: 'Inprogress' },
                        { value: 'Completed', label: 'Completed' },
                      ]" />
                  </div>
                </BCol>
                <BCol xxl="1" sm="4">
                    <BButton variant="danger" class="add-btn" @click="toggleCreate">
                    <i class="ri-add-line align-bottom me-1"></i>New Block
                  </BButton>
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
                    <th class="text-center" style="width: 4%;">#</th>
                    <th >Lot Name</th>
                    <th class="text-center">Block</th>
                    <th class="text-center">Section</th>
                    <th class="text-center">Area</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Availability</th>
                    <th class="text-center">Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="list form-check-all">
                    <tr v-for="(list, index) of lists" :key="index">
                        <td class="text-center"> 
                            {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                        </td>
                        <td>
                            <h5 class="fs-13 mb-0 text-dark">Lot {{list.number}}</h5>
                        </td>
                        <td class="text-center fs-12">Block {{list.block_number}} - {{ list.block_name }}</td>
                        <td class="text-center fs-12">{{list.section}}</td>
                        <td class="text-center fs-12">{{list.area}}</td>
                        <td class="text-center fs-12">{{list.price}}</td>
                        <td class="text-center">
                            <i v-if="list.is_available" class="ri-checkbox-circle-fill text-success fs-18"></i>
                            <i v-else class="ri-close-circle-fill text-danger fs-18"></i>
                        </td>
                        <td class="text-center">
                            <span :class="'badge '+list.status.color">{{list.status.name}}</span>
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
    <Create ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Create.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, PageHeader, Multiselect, Create, simplebar }, 
    props: ['lot_statuses'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            keyword: null,
            status: null,
            index: null,
        }
    },
    watch: {
        "keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "status"(newVal){
            this.fetch();
        }
    },
    created(){
      this.fetch();
    },
    methods: {
        toggleCreate(){
            this.$refs.create.show();
        },
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/lots';
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