<template>
    <div class="svg-background">
        <div class="map-screen">
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
                              <span class="menu-text">Overview</span>
                              <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu">
                              <i class="menu-arrow"></i>
                              <ul class="menu-subnav">
                                <li class="menu-item"
                                    v-bind:class="{ 'menu-item-active': (market_menu.id === 'btc-overview') }"
                                >
                                  <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="btc-overview" data-menu_parent="market">
                                    <span class="menu-text">Market</span>
                                    <span class="version">New</span>
                                  </a>
                                </li>
                                <li class="menu-item"
                                    :id="bitcoin_menu.coin_id"
                                    v-bind:class="{ 'menu-item-active': (menu_item.coin_id === bitcoin_menu.coin_id) }"
                                >
                                  <a class="menu-link" v-on:click="selectProjectMenu($event)" :data-menu="bitcoin_menu.coin_id" data-menu_parent="projects">
                                    <img :src="bitcoin_menu.image" v-if="bitcoin_menu.image" />
                                    <span class="menu-text">{{ bitcoin_menu.name }}</span>
                                  </a>
                                </li>
                                <li class="menu-item"
                                    v-bind:class="{ 'menu-item-active': (market_menu.id === 'btc-performance') }"
                                >
                                  <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="btc-performance" data-menu_parent="market">
                                    <span class="menu-text">BTC Performance</span>
                                    <span class="version">New</span>
                                  </a>
                                </li>
                                <li class="menu-item"
                                    v-bind:class="{ 'menu-item-active': (market_menu.id === 'marco-event') }"
                                >
                                  <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="marco-event" data-menu_parent="market">
                                    <span class="menu-text">Events</span>
                                    <span class="version">New</span>
                                  </a>
                                </li>
                                <li class="menu-item"
                                    v-bind:class="{ 'menu-item-active': (market_menu.id === 'tvl') }"
                                    v-if="allow_show"
                                >
                                  <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="tvl" data-menu_parent="market"><span class="menu-text">TVL</span></a>
                                </li>
                                <li class="menu-item"
                                    v-bind:class="{ 'menu-item-active': (market_menu.id === 'fundraising') }"
                                    v-if="allow_show"
                                >
                                  <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="fundraising" data-menu_parent="market"><span class="menu-text">Fundraising</span></a>
                                </li>
                              </ul>
                            </div>
                          </li>
                            <li class="menu-item menu-item-submenu menu-item-open">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-icon"><i class="far fa-megaphone"></i></span>
                                    <span class="menu-text">Fund raises</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <i class="menu-arrow"></i>
                                    <ul class="menu-subnav">
                                        <li class="menu-item"
                                            v-bind:class="{ 'menu-item-active': (market_menu.id === 'overview') }"
                                        >
                                          <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="overview" data-menu_parent="fund-raises">
                                            <span class="menu-text">Overview</span>
                                            <span class="version">New</span>
                                          </a>
                                        </li>
                                        <li class="menu-item"
                                            v-bind:class="{ 'menu-item-active': (market_menu.id === 'category-rounds') }"
                                        >
                                            <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="category-rounds" data-menu_parent="fund-raises">
                                              <span class="menu-text">Category-rounds</span>
                                              <span class="version">New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu menu-item-open">
                              <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon"><i class="far fa-megaphone"></i></span>
                                <span class="menu-text">Indicators Screener</span>
                                <i class="menu-arrow"></i>
                              </a>
                              <div class="menu-submenu">
                                <i class="menu-arrow"></i>
                                <ul class="menu-subnav">
                                  <li class="menu-item"
                                      v-bind:class="{ 'menu-item-active': (market_menu.id === 'long-term') }"
                                  >
                                    <a class="menu-link" v-on:click="selectMarketMenu($event)" data-menu="long-term" data-menu_parent="indicators-screener">
                                      <span class="menu-text">Long term</span>
                                      <span class="version">New</span>
                                    </a>
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
                                    <ul class="menu-subnav projects">
                                        <li class="menu-item"
                                            v-for="(menu, index) in projects_menu"
                                            :key="index"
                                            :id="menu.coin_id"
                                            v-bind:class="{ 'menu-item-active': (menu_item.coin_id === menu.coin_id) }"
                                            v-if="index > 0"
                                        >
                                            <a class="menu-link" v-on:click="selectProjectMenu($event)" :data-menu="menu.coin_id" data-menu_parent="projects">
                                                <img :src="menu.image" v-if="menu.image" />
                                                <span class="menu-text">{{ menu.name }}</span>
                                            </a>
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
                <div class="desktop_layout__rightcol">
                    <div class="subheader" v-if="menu_item.parent === 'projects' && allow_show">This is sub-header</div>
                    <div class="subheader-bottom" v-if="allow_show" style="display: none;"></div>
                    <div class="key-metrics">
                        <div class="title" v-if="market_menu.parent == 'projects' || market_menu.id == 'bitcoin'">
                            <span v-on:click="selectTab($event)" data-tab="tokenomics" v-bind:class="{ active: (menu_item.tab === 'tokenomics') }">Tokenomics</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="technology" v-bind:class="{ active: (menu_item.tab === 'technology') }">Technology</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="ecosystem" v-bind:class="{ active: (menu_item.tab === 'ecosystem') }">Ecosystem</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="team-backers" v-bind:class="{ active: (menu_item.tab === 'team-backers') }">Team backers</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="investors" v-bind:class="{ active: (menu_item.tab === 'investors') }">Investors</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="community" v-bind:class="{ active: (menu_item.tab === 'community') }">Community</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="roadmap" v-bind:class="{ active: (menu_item.tab === 'roadmap') }">Roadmap</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="orientation" v-bind:class="{ active: (menu_item.tab === 'orientation') }">Orientation</span>
                            <span v-if="allow_show" v-on:click="selectTab($event)" data-tab="analysis" v-bind:class="{ active: (menu_item.tab === 'analysis') }">Analysis</span>
                        </div>
                        <div class="title" v-if="market_menu.id=='marco-event' && allow_show">
                            <span v-on:click="selectTab($event)" data-tab="calendar" v-bind:class="{ active: (menu_item.tab === 'calendar') }">Calendar</span>
                            <span v-on:click="selectTab($event)" data-tab="bitcoin" v-bind:class="{ active: (menu_item.tab === 'bitcoin') }">Bitcoin</span>
                            <span v-on:click="selectTab($event)" data-tab="btc-returns" v-bind:class="{ active: (menu_item.tab === 'btc-returns') }">BTC Returns</span>
                            <span v-on:click="selectTab($event)" data-tab="ethereum" v-bind:class="{ active: (menu_item.tab === 'ethereum') }">Ethereum</span>
                        </div>
                    </div>
                    <div class="content" id="fund-raises" v-if="menu_item.parent === 'fund-raises'">
                      <div class="row">
                        <div class="col-lg-12">
                          <div>
                            <iframe
                                src="https://market.coingen.net/fundraise-overview"
                                frameborder="0"
                                height="800"
                                allowtransparency
                                v-if="market_menu.id === 'overview'"
                                style="width: 100%"
                            ></iframe>
                            <iframe
                                src="https://market.coingen.net/category-rounds"
                                frameborder="0"
                                height="800"
                                allowtransparency
                                v-if="market_menu.id === 'category-rounds'"
                                style="width: 100%"
                            ></iframe>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="content" id="indicators-screener" v-if="menu_item.parent === 'indicators-screener'">
                      <div class="row">
                        <div class="col-lg-12">
                          <div>
                            <iframe
                                src="https://admin.coingen.net"
                                frameborder="0"
                                height="800"
                                allowtransparency
                                v-if="market_menu.id === 'long-term'"
                                style="width: 100%"
                            ></iframe>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="content" id="market-overview" v-if="menu_item.parent === 'market'">
                        <div class="row">
                            <div class="col-lg-12">
                                <price v-bind:coin_object="coin_object" ref="price_metrics" v-if="market_menu.id === 'marco-event'"></price>
                                <div>

                                    <iframe
                                        src="https://www.footprint.network/public/dashboard/Fundraising-Dashboard-fp-171c0401-f0e4-4695-87d9-53359fc0ceb2"
                                        frameborder="0"
                                        height="800"
                                        allowtransparency
                                        v-if="market_menu.id === 'fundraising'"
                                    ></iframe>

                                    <iframe
                                        src="https://www.footprint.network/public/dashboard/TVL-Overview-fp-2e715067-350e-4b0b-8ca2-87f8a5662957?date_filter=past90days"
                                        frameborder="0"
                                        allowtransparency
                                        height="800"
                                        v-if="market_menu.id === 'tvl'"
                                    ></iframe>
                                </div>
                                <btc-overview v-bind:coin_object="coin_object" ref="fear_index" v-if="market_menu.id === 'btc-overview'"></btc-overview>
                                <btc-performance ref="btc_performance" v-if="market_menu.id === 'btc-performance'"></btc-performance>
                            </div>
                        </div>
                    </div>
                    <div class="content" id="projects" v-if="menu_item.parent === 'projects'">
                        <ul class="tabs" v-if="menu_item.tab == 'tokenomics'">
                            <li v-bind:class="{ active: (project_menu_tab === 'metrics') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="metrics">Metrics</a></li>
                            <li v-if="allow_show" v-bind:class="{ active: (project_menu_tab === 'performance') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="performance">Performance</a></li>
                            <li v-if="allow_show" v-bind:class="{ active: (project_menu_tab === 'allocation') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="allocation">Allocation</a></li>
                            <li v-if="allow_show" v-bind:class="{ active: (project_menu_tab === 'fund-raising') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="fund-raising">Fund Raising</a></li>
                            <li v-if="allow_show" v-bind:class="{ active: (project_menu_tab === 'release-schedule') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="release-schedule">Release Schedule</a></li>
                            <li v-if="allow_show" v-bind:class="{ active: (project_menu_tab === 'valuation') }"><a href="javascript:;" v-on:click="selectTabB($event)" data-tab="valuation">Valuation</a></li>
                        </ul>
                        <div class="row px-3" v-if="menu_item.tab == 'ecosystem'">
                            <div class="col-lg-12">
                              <div class="team-backers" v-if="coin_object.ecosystem.length">
                                <div
                                    v-for="row in coin_object.ecosystem"
                                    class="team-member"
                                >
                                  <div class="avatar" v-if="row.image">
                                    <img :src="row.image">
                                  </div>
                                  <div class="content">
                                    <div class="name">{{ row.text }}</div>
                                    <div class="description">{{ row.address }}</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'technology'">
                            <div class="col-lg-12">
                                <div v-if="menu_item.tab == 'technology'" v-html="coin_object.technology" class="technology"></div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'team-backers'">
                            <div class="col-lg-12">
                                <div class="team-backers">
                                    <div
                                        v-for="row in coin_object.team_backers"
                                        class="team-member"
                                    >
                                        <div class="avatar" v-if="row.image">
                                            <img :src="row.image">
                                        </div>
                                        <div class="content">
                                            <div class="name">{{ row.name }}</div>
                                            <div class="title" v-if="row.position">{{ row.position }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'investors'">
                            <div class="col-lg-12">
                                <div class="investors">
                                    <div
                                        v-if="coin_object.investors"
                                        v-for="row in coin_object.investors"
                                        class="team-member"
                                    >
                                        <div class="avatar" v-if="row.image">
                                            <img :src="row.image">
                                        </div>
                                        <div class="content">
                                            <div class="name">{{ row.name }}</div>
                                            <div class="description" v-readMore:70="row.description"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'roadmap'">
                            <div class="col-lg-12">
                                <div class="roadmap">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="roadmap in coin_object.roadmap">
                                            <td>{{ roadmap.title }}</td>
                                            <td>{{ roadmap.date }}</td>
                                            <td>{{ roadmap.type }}</td>
                                            <td>{{ roadmap.details }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'community' && coin_object.community_data.length">
                            <div class="col-lg-12">
                                <div class="social-stats">
                                    <div
                                        v-if="coin_object.community_data.facebook_likes"
                                        class="list-item"
                                    >
                                        <div class="key">Facebook Likes</div>
                                        <div class="value">{{ coin_object.community_data.facebook_likes }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.twitter_followers"
                                        class="list-item"
                                    >
                                        <div class="key">Twitter Followers</div>
                                        <div class="value">{{ coin_object.community_data.twitter_followers }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.reddit_average_posts_48h"
                                        class="list-item"
                                    >
                                        <div class="key">Reddit Average Posts 48h</div>
                                        <div class="value">{{ coin_object.community_data.reddit_average_posts_48h }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.reddit_average_comments_48h"
                                        class="list-item"
                                    >
                                        <div class="key">Reddit Average Comments 48h</div>
                                        <div class="value">{{ coin_object.community_data.reddit_average_comments_48h }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.reddit_subscribers"
                                        class="list-item"
                                    >
                                        <div class="key">Reddit Subscribers</div>
                                        <div class="value">{{ coin_object.community_data.reddit_subscribers }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.reddit_accounts_active_48h"
                                        class="list-item"
                                    >
                                        <div class="key">Reddit Accounts Active 48h</div>
                                        <div class="value">{{ coin_object.community_data.reddit_accounts_active_48h }}</div>
                                    </div>
                                    <div
                                        v-if="coin_object.community_data.telegram_channel_user_count"
                                        class="list-item"
                                    >
                                        <div class="key">Telegram channel user count</div>
                                        <div class="value">{{ coin_object.community_data.telegram_channel_user_count }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'orientation'">
                            <div class="col-lg-12">
                                <div v-if="menu_item.tab == 'orientation'" v-html="coin_object.orientation" class="orientation"></div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'tokenomics' && project_menu_tab === 'metrics'">
                            <div class="col-lg-12">
                                <m-size v-bind:mcap_data="mcap_data" v-bind:coin_object="coin_object" ref="m_size"></m-size>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'tokenomics' && project_menu_tab === 'metrics'">
                            <div class="col-lg-12">
                                <div class="two-charts">
                                    <supply-metrics v-bind:coin_object="coin_object" ref="supply_metrics"></supply-metrics>
                                    <mcap-metrics v-bind:mcap_data="mcap_data" ref="mcap_metrics"></mcap-metrics>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'tokenomics' && project_menu_tab === 'metrics'">
                            <div class="col-lg-12">
                                <div class="two-charts">
                                    <actual-inflation v-bind:coin_object="coin_object" ref="inflation_metrics"></actual-inflation>
                                    <monthly-token-release v-bind:coin_object="coin_object" ref="monthly_token_release"></monthly-token-release>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="menu_item.tab == 'tokenomics' && project_menu_tab === 'metrics'">
                            <div class="col-lg-12">
                              <dom-metrics v-bind:coin_object="coin_object" ref="dom_metrics"></dom-metrics>
                            </div>
                        </div>
                        <div class="row px-3" v-if="project_menu_tab === 'performance'">
                          <div class="col-lg-12">
                            <div v-html="coin_object.performance" class="performance"></div>
                          </div>
                        </div>
                        <div class="row px-3" v-if="project_menu_tab === 'release-schedule'">
                          <div class="col-lg-12">
                            <div v-html="coin_object.release_schedule" class="release-schedule"></div>
                          </div>
                        </div>
                        <div class="row px-3" v-if="project_menu_tab === 'allocation'">
                            <div class="col-lg-12">
                                <div class="dark">
                                    <allocation v-bind:coin_object="coin_object" ref="allocation"></allocation>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3" v-if="project_menu_tab === 'fund-raising'">
                            <div class="col-lg-12">
                                <div class="dark">
                                    <fundraising v-bind:coin_object="coin_object" ref="fundraising"></fundraising>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vld-parent">
            <loading
                :active.sync="$store.state.isLoading"
                loader="dots"
                color="#8853f9"
                v-if="$store.state.isLoading"
            />
        </div>
        <simplert />
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Dropdown from 'vue-simple-search-dropdown';
import ClickOutside from 'vue-click-outside';
import ChartLoading from "./ChartLoading";
import McapMetrics from "./McapMetrics";
import SupplyMetrics from "./SupplyMetrics";
import MonthlyTokenRelease from "./MonthlyTokenRelease";
import InflationMetrics from "./InflationMetrics";
import ActualInflation from "./ActualInflation";
import DomMetrics from "./DomMetrics";
import MSize from "./MSize";
import Footprint from "./Footprint";
import Price from "./Price";
import Fundraising from "./market-overview/Fundraising";
import Allocation from "./market-overview/Allocation";
import BtcOverview from "./market-overview/BtcOverview";
import BtcPerformance from "./market-overview/BtcPerformance";


export default {
    name: 'Analysis',
    components: {
        Loading,
        Dropdown,
        ChartLoading,
        McapMetrics,
        SupplyMetrics,
        MonthlyTokenRelease,
        InflationMetrics,
        DomMetrics,
        MSize,
        Footprint,
        Price,
        Fundraising,
        Allocation,
        ActualInflation,
        BtcOverview,
        BtcPerformance
    },
    data() {
        return {
            date_range_object: {},
            opened: false,
            filterArr: [],
            mcap_data: [],
            coin_object: {},
            menu_item: { coin_id: "ethereum", parent: "projects", tab: "tokenomics" },
            projects_menu: [],
            bitcoin_menu: {},
            market_menu: {id: "", parent: "projects"},
            project_menu_tab: "",
            allow_show: !0,
        };
    },
    computed: {
        currentRouteName() {
            return this.$route.name;
        }
    },
    created() {
        this.getMenuItems();
        this.filterArr = [{ value: '1d', name: '24h'}, { value: '7d', name: '7D'}, { value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}];
        this.project_menu_tab = "metrics";

        if(this.currentRouteName == "analysis") {
            this.$router.push({ name: 'analysis-project', params: { slug: this.menu_item.coin_id } }).catch( () => {} );
        } else if(this.currentRouteName === "analysis-market-overview" && this.$route.params.slug) {
            let slug = this.$route.params.slug;

            this.$set(this.menu_item, 'parent', 'market')
            this.$set(this.menu_item, 'id', slug)
            this.$store.commit('setMenuItem', this.menu_item);

            this.$set(this.market_menu, 'parent', 'market')
            this.$set(this.market_menu, 'id', slug)
            this.$store.commit('setMarketMenuItem', this.market_menu);
        }
    },
    mounted() {
        this.date_range_object = this.filterArr[0];
        this.$store.commit("setDateRange", this.date_range_object.value);
        this.fillData();
    },
    directives: {
        ClickOutside
    },
    methods: {
        getMenuItems() {
            axios.get("".concat(window.apiUrl, "/v2/coins/all")).then(response => {
                const menus = response.data
                this.projects_menu = menus
                this.bitcoin_menu = menus[0]
                if(this.currentRouteName == "analysis-project" && this.$route.params.slug) {
                    const menuItem = this.projects_menu.filter(menu_item => {
                        return menu_item.coin_id === this.$route.params.slug;
                    })[0];
                    this.$store.commit('setProjectMenuItem', menuItem);

                    this.$set(this.menu_item, 'parent', 'projects')
                    this.$set(this.menu_item, 'coin_id', this.$route.params.slug)

                    this.$store.commit('setMenuItem', this.menu_item);
                } else {
                    this.$set(this.menu_item, 'parent', 'market')
                    this.$set(this.menu_item, 'coin_id', this.projects_menu[0].coin_id)
                    this.$store.commit('setMenuItem', this.menu_item);

                    //this.menu_item = this.projects_menu[0]

                    this.$store.commit('setProjectMenuItem', this.projects_menu[0]);
                }
                this.loadProject(this.menu_item.coin_id)
            });
        },
        selectProjectMenu(event) {
            let menu_id = event.currentTarget.getAttribute("data-menu");
            const menuItem = this.projects_menu.filter(menu_item => {
                return menu_item.coin_id === menu_id;
            })[0];
            let menu_parent = event.currentTarget.getAttribute("data-menu_parent");
            this.$set(this.menu_item, 'parent', menu_parent)

            //this.menu_item = menuItem
            //this.menu_item.parent = menu_parent
            //this.$store.commit('setMenuItem', this.menu_item);
            this.$set(this.menu_item, 'coin_id', menuItem.coin_id)
            this.$store.commit('setProjectMenuItem', menuItem);

            if(menu_parent === "projects") {
                this.$router.push({ name: 'analysis-project', params: { slug: menu_id } }).catch( () => {} );
                this.fillData();
            } else if(menu_parent === "market") {
                this.$router.push({ name: 'analysis-market-overview', params: { slug: menu_id } }).catch( () => {} );
            }
            this.loadProject(this.menu_item.coin_id)
        },
        selectMarketMenu(event) {
            let menu_id = event.currentTarget.getAttribute("data-menu");
            let menu_parent = event.currentTarget.getAttribute("data-menu_parent");
            this.$set(this.menu_item, 'parent', menu_parent)

            //this.menu_item = menuItem
            //this.menu_item.parent = menu_parent
            //this.$store.commit('setMenuItem', this.menu_item);
            const market_menu = { id: menu_id, parent: menu_parent };
            this.market_menu = market_menu
            this.$store.commit('setMarketMenuItem', this.market_menu);

            this.$router.push({ name: 'analysis-market-overview', params: { slug: menu_id } }).catch( () => {} );
        },
        selectTab(event) {
            let tab_name = event.currentTarget.getAttribute("data-tab");
            this.menu_item.tab = tab_name;
            this.$store.commit('setMenuItem', this.menu_item);
        },
        toggleDropdown() {
            this.active = !this.active
            this.opened = !this.opened
        },
        hideDropdown() {
            this.opened = false
        },
        loadProject(coin_id) {
            axios.get("https://pro.coingen.net/api/v3/coins/"+coin_id).then(response => {
                const data = response.data
                this.coin_object = data
                //console.log(this.coin_object)
            }).catch(e => {
                console.log(e)
            });
        },
        fillData() {
            if(this.menu_item.parent == "projects") {
                setTimeout(() => this.$refs.monthly_token_release.getData(), 300);
                setTimeout(() => this.$refs.mcap_metrics.getData(), 600);
                setTimeout(() => this.$refs.dom_metrics.getData(), 900);
            } else if (this.menu_item.parent == "market") {
                if(this.menu_item.id == "marco-event") setTimeout(() => this.$refs.price_metrics.getData(), 300);
            }
        },
        selectDateRange(d) {
            this.date_range_object = d;
            this.$store.commit("setDateRange", d.value);
            this.fillData();
        },
        mcapData(d) {
            this.mcap_data = d.data;
            this.last_mcap = d.coin;
            this.last_mcap.circulating_supply_pt = Math.round(this.last_mcap.circulating_supply / this.last_mcap.total_supply * 100);
            this.last_mcap.total_stake_pt = Math.round(this.last_mcap.total_stake / this.last_mcap.total_supply * 100);
        },
        selectTabB(event) {
            this.project_menu_tab = event.currentTarget.getAttribute("data-tab")
        },
    },
}
</script>
<style scoped>
.two-charts {
    display: flex;
    flex-wrap: wrap;
}
#market-overview iframe {
    width: 100%;
}
.desktop_layout__rightcol .key-metrics .title > span {
    margin-right: 8px;
    font-size: 0.875rem;
    padding: 6px 15px;
    -webkit-border-radius: 1rem;
    -moz-border-radius: 1rem;
    border-radius: 1rem;
    cursor: pointer;
}
.desktop_layout__rightcol .key-metrics .title > span.active,
.desktop_layout__rightcol .key-metrics .title > span:hover {
    background: #16151a;
}
.desktop_layout__rightcol .content .tabs {
    margin-top: 10px;
    margin-bottom: 10px;
}
.technology pre,
.orientation pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: inherit;
}
.roadmap table {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #f2f2f2;
}
.roadmap td, .roadmap th {
    padding: 8px;
    border: 1px solid #f2f2f2;
    color: #fff;
}
.roadmap tr:nth-child(even){
    background-color: #222227;
}
.roadmap th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #2D2D2D;
}
.team-backers {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-column-gap: 20px;
    grid-row-gap: 20px;
}
.team-member {
    color: #fff;
    height: 100%;
    display: flex;
    padding: 12px;
    align-items: center;
    border-radius: 4px;
    justify-content: stretch;
    text-decoration: none;
    background-color: #16181d;
}
.team-member .avatar {
    width: 70px;
    height: 70px;
    margin-right: 16px;
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.25rem;
    line-height: 1;
    border-radius: 50%;
    overflow: hidden;
    user-select: none;
}
.team-member .avatar img {
    width: 100%;
    height: 100%;
    text-align: center;
    object-fit: cover;
    color: transparent;
    text-indent: 10000px;
}
.team-member .title {
    height: auto;
    padding: 4px 8px;
    overflow: hidden;
    font-size: .875rem;
    min-height: 30px;
    align-items: center;
    font-weight: 500;
    line-height: 1.5;
    white-space: nowrap;
    margin-right: 8px;
    border-radius: 4px;
    text-overflow: ellipsis;
    background-color: #38404A;
}
</style>
