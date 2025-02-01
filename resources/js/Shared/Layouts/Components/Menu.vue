<template>
    <BContainer fluid>
        <div id="two-column-menu"></div>

        <ul class="navbar-nav h-100" id="navbar-nav">
              <li class="menu-title">
                <span data-key="t-menu"> asd</span>
              </li>
              <li class="nav-item">
                <Link href="/dashboard" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Dashboard') }">
                  <i class="ri-apps-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">Dashboard</span>
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/lots" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Lot') }">
                  <i class="ri-map-pin-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">Lot Plotting & Mapping</span>
                </Link>
              </li>
              <li class="nav-item">
                <Link  href="/owners" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Owner') }">
                  <i class="ri-team-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">Owner Management</span>
                </Link>
              </li>
              <li class="nav-item">
                <Link  href="/graves" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Grave') }">
                  <i class="ri-ghost-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">Grave Management</span>
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/reports" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Report') }">
                  <i class="ri-line-chart-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">Reports & Analytics</span>
                </Link>
              </li>
              <li class="nav-item">
                <Link  href="/users" class="nav-link menu-link" :class="{'active': $page.component.startsWith('Users') }">
                  <i class="ri-account-circle-line"></i>
                  <span class="fw-semibold fs-14" data-key="t-dashboards">User Management</span>
                </Link>
              </li>
              <!-- <li class="nav-item">
                <Link class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                  aria-expanded="false" aria-controls="sidebarDashboards">
                  <i class="ri-dashboard-2-line"></i>
                  <span data-key="t-dashboards fw-semibold">Dashboard</span>
                </Link>
                <div class="collapse menu-dropdown" id="sidebarDashboards">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <router-link to="/dashboard/analytics" class="nav-link custom-abc" data-key="t-analytics">
                     asd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/dashboard/crm" class="nav-link" data-key="t-crm">
                      asd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/" class="nav-link" data-key="t-ecommerce">
                      asd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/dashboard/crypto" class="nav-link" data-key="t-crypto">
                     aasd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/dashboard/projects" class="nav-link" data-key="t-projects">
                       asd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/dashboard/nft" class="nav-link" data-key="t-nft">
                       asd
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link to="/dashboard/job" class="nav-link" data-key="t-job">
                     asd
                      </router-link>
                    </li>
                  </ul>
                </div>
              </li> -->
        </ul>
    </BContainer>
</template>
<script>
import { layoutComputed } from "@/Shared/State/helpers";
import simplebar from "simplebar-vue";

export default {
  components: {
    simplebar,
  },
  data() {
    return {
       currentUrl: window.location.origin,
      menus: [],
      settings: {
        minScrollbarLength: 60,
      },
    };
  },
  computed: {
    ...layoutComputed,
    layoutType: {
      get() {
        return this.$store ? this.$store.state.layout.layoutType : {} || {};
      },
    },
    filteredFinance() {
      const cashier = ['Dashboard', 'Receipts', 'Deposits', 'Collection Type', 'OR Series', 'Names', 'Reports'];
      const accountant = ['Dashboard', 'Order of Payment','Collection Type','Reports'];

      const role = this.$page.props.user.data.assigned_role;
      const menus = this.$page.props.menus.finance;

      const isMenuNameInArray = (menuName, nameArray) => 
        nameArray.some(name => menuName.toLowerCase().includes(name.toLowerCase()));

      if (role === 'Cashier') {
        return menus.filter(menu => isMenuNameInArray(menu.main.name, cashier));
      } else if (role === 'Accountant') {
        return menus.filter(menu => isMenuNameInArray(menu.main.name, accountant));
      }

      return [];
    }
  },
  mounted() {
    this.initActiveMenu();
    this.onRoutechange();
    // this.fetch();
    if (document.querySelectorAll(".navbar-nav .collapse")) {
      let collapses = document.querySelectorAll(".navbar-nav .collapse");

      collapses.forEach((collapse) => {
        // Hide sibling collapses on `show.bs.collapse`
        collapse.addEventListener("show.bs.collapse", (e) => {
          e.stopPropagation();
          let closestCollapse = collapse.parentElement.closest(".collapse");
          if (closestCollapse) {
            let siblingCollapses =
              closestCollapse.querySelectorAll(".collapse");
            siblingCollapses.forEach((siblingCollapse) => {
              if (siblingCollapse.classList.contains("show")) {
                siblingCollapse.classList.remove("show");
                siblingCollapse.parentElement.firstChild.setAttribute("aria-expanded", "false");
              }
            });
          } else {
            let getSiblings = (elem) => {
              // Setup siblings array and get the first sibling
              let siblings = [];
              let sibling = elem.parentNode.firstChild;
              // Loop through each sibling and push to the array
              while (sibling) {
                if (sibling.nodeType === 1 && sibling !== elem) {
                  siblings.push(sibling);
                }
                sibling = sibling.nextSibling;
              }
              return siblings;
            };
            let siblings = getSiblings(collapse.parentElement);
            siblings.forEach((item) => {
              if (item.childNodes.length > 2) {
                item.firstElementChild.setAttribute("aria-expanded", "false");
                item.firstElementChild.classList.remove("active");
              }
              let ids = item.querySelectorAll("*[id]");
              ids.forEach((item1) => {
                item1.classList.remove("show");
                item1.parentElement.firstChild.setAttribute("aria-expanded", "false");
                item1.parentElement.firstChild.classList.remove("active");
                if (item1.childNodes.length > 2) {
                  let val = item1.querySelectorAll("ul li a");

                  val.forEach((subitem) => {
                    if (subitem.hasAttribute("aria-expanded"))
                      subitem.setAttribute("aria-expanded", "false");
                  });
                }
              });
            });
          }
        });

        // Hide nested collapses on `hide.bs.collapse`
        collapse.addEventListener("hide.bs.collapse", (e) => {
          e.stopPropagation();
          let childCollapses = collapse.querySelectorAll(".collapse");
          childCollapses.forEach((childCollapse) => {
            let childCollapseInstance = childCollapse;
            childCollapseInstance.classList.remove("show");
            childCollapseInstance.parentElement.firstChild.setAttribute("aria-expanded", "false");
          });
        });
      });
    }
  },

  methods: {
    checkUrl() {
      const path = window.location.pathname;
      return path.split('/')[1] || null;
    },
    onRoutechange() {
      // this.initActiveMenu();
      setTimeout(() => {
        var currentPath = window.location.pathname;
        if (document.querySelector("#navbar-nav")) {
          let currentPosition = document.querySelector("#navbar-nav").querySelector('[href="' + currentPath + '"]')?.offsetTop;
          if (currentPosition > document.documentElement.clientHeight) {
            document.querySelector("#scrollbar .simplebar-content-wrapper") ? document.querySelector("#scrollbar .simplebar-content-wrapper").scrollTop = currentPosition + 300 : '';
          }
        }
      }, 500);
    },

    initActiveMenu() {
      setTimeout(() => {
        var currentPath = window.location.pathname;
        if (document.querySelector("#navbar-nav")) {
          let a = document.querySelector("#navbar-nav").querySelector('[href="' + currentPath + '"]');
          if (a) {
            a.classList.add("active");
            let parentCollapseDiv = a.closest(".collapse.menu-dropdown");
            if (parentCollapseDiv) {
              parentCollapseDiv.classList.add("show");
              parentCollapseDiv.parentElement.children[0].classList.add("active");
              parentCollapseDiv.parentElement.children[0].setAttribute("aria-expanded", "true");
              if (parentCollapseDiv.parentElement.closest(".collapse.menu-dropdown")) {
                parentCollapseDiv.parentElement.closest(".collapse").classList.add("show");
                if (parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling)
                  parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling.classList.add("active");
                const grandparent = parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling.parentElement.closest(".collapse");
                if (grandparent && grandparent && grandparent.previousElementSibling) {
                  grandparent.previousElementSibling.classList.add("active");
                  grandparent.classList.add("show");
                }
              }
            }
          }
        }
      }, 0);
    },
  },
};
</script>