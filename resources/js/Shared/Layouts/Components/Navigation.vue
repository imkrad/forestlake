<script>
    import { router } from '@inertiajs/vue3';
    import { layoutMethods } from "@/Shared/State/helpers";
    import simplebar from "simplebar-vue";
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                text: null,
                value: null,
                myVar: 1,
                refreshInterval: null,
            };
        },
        components: {
            simplebar
        },
        methods: {
            ...layoutMethods,
            logout() {
                router.post('/logout');
            },
            toggleHamburgerMenu() {
                var windowSize = document.documentElement.clientWidth;
                let layoutType = document.documentElement.getAttribute("data-layout");

                document.documentElement.setAttribute("data-sidebar-visibility", "show");
                let visiblilityType = document.documentElement.getAttribute("data-sidebar-visibility");

                if (windowSize > 767)
                    document.querySelector(".hamburger-icon").classList.toggle("open");

                //For collapse horizontal menu
                if (
                    document.documentElement.getAttribute("data-layout") === "horizontal"
                ) {
                    document.body.classList.contains("menu") ?
                        document.body.classList.remove("menu") :
                        document.body.classList.add("menu");
                }

                //For collapse vertical menu

                if (visiblilityType === "show" && (layoutType === "vertical" || layoutType === "semibox")) {
                    if (windowSize < 1025 && windowSize > 767) {
                        document.body.classList.remove("vertical-sidebar-enable");
                        document.documentElement.getAttribute("data-sidebar-size") == "sm" ?
                            document.documentElement.setAttribute("data-sidebar-size", "") :
                            document.documentElement.setAttribute("data-sidebar-size", "sm");
                    } else if (windowSize > 1025) {
                        document.body.classList.remove("vertical-sidebar-enable");
                        document.documentElement.getAttribute("data-sidebar-size") == "lg" ?
                            document.documentElement.setAttribute("data-sidebar-size", "sm") :
                            document.documentElement.setAttribute("data-sidebar-size", "lg");
                    } else if (windowSize <= 767) {
                        document.body.classList.add("vertical-sidebar-enable");
                        document.documentElement.setAttribute("data-sidebar-size", "lg");
                    }
                }

                //Two column menu
                if (document.documentElement.getAttribute("data-layout") == "twocolumn") {
                    document.body.classList.contains("twocolumn-panel") ?
                        document.body.classList.remove("twocolumn-panel") :
                        document.body.classList.add("twocolumn-panel");
                }
            },
            toggleMenu() {
                this.$parent.toggleMenu();
            },
            toggleRightSidebar() {
                this.$parent.toggleRightSidebar();
            }
        },

        mounted() {
            document.addEventListener("scroll", function () {
                var pageTopbar = document.getElementById("page-topbar");
                if (pageTopbar) {
                    document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50 ? pageTopbar
                        .classList.add(
                            "topbar-shadow") : pageTopbar.classList.remove("topbar-shadow");
                }
            });
            if (document.getElementById("topnav-hamburger-icon"))
                document.getElementById("topnav-hamburger-icon").addEventListener("click", this.toggleHamburgerMenu);
        },
    };
</script>

<template>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <Link href="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="@assets/images/job.png" alt="" height="22" />
                        </span>
                        <span class="logo-lg">
                            <img src="@assets/images/adzu.png" alt="" height="45" />
                        </span>
                        <span class="fs-24 ms-2">Registrify </span>
                        </Link>

                        <Link href="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="@assets/images/job.png" alt="" height="22" />
                        </span>
                        <span class="logo-lg">
                            <img src="@assets/images/adzu.png" alt="" height="45" />
                        </span>
                        </Link>
                    </div>
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                        id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                </div>

                <div class="d-flex align-items-center">
                    <BDropdown class="dropdown d-md-none topbar-head-dropdown header-item" variant="ghost-secondary"
                        dropstart :offset="{ alignmentAxis: 55, crossAxis: 15, mainAxis: 0 }"
                        toggle-class="btn-icon btn-topbar rounded-circle arrow-none"
                        menu-class="dropdown-menu-lg dropdown-menu-end p-0">
                        <template #button-content>
                            <i class="bx bx-search fs-22"></i>
                        </template>
                        <BDropdownItem aria-labelledby="page-header-search-dropdown">
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..."
                                            aria-label="Recipient's username" />
                                        <BButton variant="primary" type="submit">
                                            <i class="mdi mdi-magnify"></i>
                                        </BButton>
                                    </div>
                                </div>
                            </form>
                        </BDropdownItem>
                    </BDropdown>

                    

                    <BDropdown variant="link" class="ms-sm-3 header-item topbar-user"
                        toggle-class="rounded-circle arrow-none" menu-class="dropdown-menu-end"
                        :offset="{ alignmentAxis: -14, crossAxis: 0, mainAxis: 0 }">
                        <template #button-content>
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar"
                                    :alt="$page.props.user.data.username">
                                <span class="text-start ms-xl-2">
                                    <span
                                        class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ $page.props.user.data.name }}</span>
                                    <span
                                        class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{$page.props.user.data.role}}</span>
                                </span>
                            </span>
                        </template>
                        <h6 class="dropdown-header fs-10">Welcome {{ $page.props.user.data.username }}!</h6>


                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout" class="dropdown-item">
                            <BButton variant="none" type="submit" class="btn p-0"><i
                                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> Logout</BButton>
                        </form>
                    </BDropdown>
                </div>
            </div>
        </div>
    </header>
    <View ref="view"/>
    <Student ref="student"/>
</template>