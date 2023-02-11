<template>
    <div class="desktop_layout">
        <div class="desktop_layout__leftcol">
            <div class="aside-menu">
                <ul class="menu-nav">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="far fa-home"></i></span>
                            <span class="menu-text">Home</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu menu-item-open">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon"><i class="far fa-megaphone"></i></span>
                            <span class="menu-text">Market Overview</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item">
                                    <a class="menu-link" v-on:click="selectMenu($event)" data-menu="tvl" data-menu_parent="market"><span class="menu-text">TVL</span></a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" v-on:click="selectMenu($event)" data-menu="fundraising" data-menu_parent="market"><span class="menu-text">Fundraising</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="far fa-vector-square"></i></span>
                            <span class="menu-text">Sector Overview</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu menu-item-open">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon"><i class="far fa-folder-open"></i></span>
                            <span class="menu-text">Projects</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item"
                                    v-for="(menu, index) in projects_menu"
                                    :key="index"
                                    :id="menu.coin_id"
                                >
                                    <a class="menu-link" v-on:click="selectMenu($event)" :data-menu="menu.coin_id" data-menu_parent="projects"><span class="menu-text">{{ menu.name }}</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="far fa-not-equal"></i></span>
                            <span class="menu-text">Crypto Compare</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Dropdown from 'vue-simple-search-dropdown';
import ClickOutside from 'vue-click-outside';

export default {
    name: "Project",
    components: {
        Loading,
        Dropdown,
    },
    data() {
        return {
            projects_menu: [],
        };
    },
    computed: {
        currentRouteName() {
            return this.$route.name;
        }
    },
    directives: {
        ClickOutside
    },
    created() {
        //this.$store.commit('setLastLoading', true);
        //this.$store.commit('setMenuItem', this.menu_item);
        //this.filterArr = [{ value: '1d', name: '24h'}, { value: '7d', name: '7D'}, { value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}];

        if(this.currentRouteName === "project" && this.$route.params.slug) {
            //let slug = this.$route.params.slug;
            //if(!slug) this.$router.push({ name: 'project', query: { slug: state_id } }).catch( () => {} );
        }
        this.getMenuItems();
    },
    methods: {
        getMenuItems() {
            axios.get("".concat(window.apiUrl, "/v2/coins/all")).then(response => {
                this.projects_menu = response.data
            });
        }
    }
}
</script>
