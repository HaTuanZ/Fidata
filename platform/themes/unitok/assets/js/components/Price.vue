<template>
    <div>
        <div class="price-metrics-chart">
            <div class="chart-heading">
              <div class="chart-title">{{ coin_name }}</div>
            </div>
            <highcharts :constructorType="'stockChart'" class="hc" :options="chartOptions" ref="marco_event_chart"></highcharts>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdS_RcYkoTreta7xS1lH3e2m4GMcailQBkn0LlqQ6evfzF0LQ/viewform" target="_blank" class="more"><i class="fal fa-comments"></i> Feedback</a>
        </div>
        <div class="marco-event">
          <div class="nagivation">
            <span :class="{ active: tab == 'marco' }" v-on:click="selectTab('marco')">Marco</span>
            <span :class="{ active: (tab == 'crypto' && !is_favourite) }" v-on:click="selectTab('crypto')">Crypto</span>
            <span :class="{ active: (tab == 'crypto' && is_favourite) }" v-on:click="selectFavourite()" v-if="tab == 'crypto'">Favourite</span>
          </div>
          <div class="marco" v-if="tab == 'marco'">
            <flat-pickr
                v-model="date"
                :config="flatpickr_config"
                placeholder="Select a date"
                class="form-control"
                @on-change="getMarcoEventsData"
            ></flat-pickr>
            <table
                v-if="marco_events.length"
            >
              <thead>
                <tr>
                  <th>Time</th>
                  <th>Event</th>
                  <th>Image</th>
                  <th>Actual</th>
                  <th>Forecast</th>
                  <th>Previous</th>
                </tr>
              </thead>
              <tbody>
                <tr
                    v-for="(event, index) in marco_events"
                    :key="event.id"
                >
                  <td>{{ event.date }} {{ event.time }}</td>
                  <td>{{ event.name }}</td>
                  <td><img :src="event.image" style="max-width: 150px;" /></td>
                  <td>{{ event.actual }}{{ event.type }}</td>
                  <td>{{ event.forecast }}{{ event.type }}</td>
                  <td>{{ event.previous }}{{ event.type }}</td>
                </tr>
              </tbody>
            </table>
            <iframe
                src="https://coingen.net/home/economicCalendarWidget"
                frameborder="0"
                height="800"
                allowtransparency
                v-if="!marco_events.length"
            ></iframe>
          </div>
          <div class="crypto" v-if="tab == 'crypto'">
            <div class="block-container">
              <div class="block-find">
                <div class="filter-left">
                  <div class="filters">
                    <div class="filter-column">
                      <date-picker
                          v-model="date_range"
                          range
                          v-on:change="getEventsData"
                          placeholder="Select date range"
                          valueType="format"
                      ></date-picker>
                    </div>
                    <div class="filter-column">
                      <div class="elem-select select-no-search" :class="{ 'elem-select-focus': opened_category }" id="select_cats" v-click-outside="hideDropdownCategories">
                        <div class="select-caption" id="select_cats_cap" @click="toggleDropdownCategories">{{ selectedCategory }}</div>
                        <transition name="slide-up-down" tag="div">
                          <div class="elem-list" id="elems_cats" v-show="opened_category">
                            <div class="arrow"></div>
                            <div class="list clusterize-scroll" id="list_cats">
                            <span
                                v-for="(tag, index) in tags"
                                :key="index"
                                :id="tag.id"
                                v-on:click="selectTag(tag.id)"
                                v-bind:class="{ active: categories.includes(tag.id) }"
                            >
                              {{ tag.name }}
                            </span>
                            </div>
                          </div>
                        </transition>
                      </div>
                    </div>
                    <div class="filter-column">
                      <div class="elem-select select-no-search" :class="{ 'elem-select-focus': opened_coin }" id="select_coins" v-click-outside="hideDropdownCoins">
                        <div class="select-caption" id="select_coins_cap" @click="toggleDropdownCoins">{{ selectedCoin }}</div>
                        <transition name="slide-up-down" tag="div">
                          <div class="elem-list" id="elems_coins" v-show="opened_coin">
                            <div class="arrow"></div>
                            <div class="list clusterize-scroll" id="list_coins">
                            <span
                                v-for="(coin, index) in coins"
                                :key="index"
                                :id="coin.id"
                                v-on:click="selectCoin(coin.id)"
                                v-bind:class="{ active: symbols.includes(coin.id) }"
                            >
                              {{ coin.name }} <span class="gray">{{ coin.symbol }}</span>
                            </span>
                            </div>
                          </div>
                        </transition>
                      </div>
                    </div>
                    <div class="filter-column">
                      <div class="elem-select select-no-search" :class="{ 'elem-select-focus': opened_important }" id="select_important" v-click-outside="hideDropdownImportant">
                        <div class="select-caption" id="select_important_cap" @click="toggleDropdownImportant">{{ selectedImportant }}</div>
                        <transition name="slide-up-down" tag="div">
                          <div class="elem-list" id="elems_important" v-show="opened_important">
                            <div class="arrow"></div>
                            <div class="list clusterize-scroll" id="list_important">
                              <span
                                  v-for="(important, index) in impacts"
                                  :key="index"
                                  :id="important.id"
                                  v-on:click="selectImpact(important.id)"
                                  v-bind:class="{ active: importants.includes(important.id) }"
                              >
                                {{ important.name }}
                              </span>
                            </div>
                          </div>
                        </transition>
                      </div>
                    </div>
                    <div class="filter-column">
                      <button
                          type="button"
                          class="px-4 py-2.5 font-semibold text-sm text-black hover:text-white rounded shadow-sm bg-gray-100 hover:bg-blue-500"
                          v-on:click="resetFilter($event)"
                      >Reset</button>
                    </div>
                    <div class="filter-column sort-by">
                      <button
                          type="button"
                          class="sort-item px-4 py-2.5 font-semibold text-sm text-white rounded shadow-sm bg-gray-500"
                          id="coin_price_changes"
                          v-on:click="cryptoSort($event)"
                          :class="{ asc: (crypto_sort_by == 'coin_price_changes' && crypto_sort_dir == 'asc'), desc: (crypto_sort_by == 'coin_price_changes' && crypto_sort_dir == 'desc') }"
                      >Changes</button>
                    </div>
                  </div>
                </div>
                <div class="sort-by" style="display: none;">
                  <div class="sort">
                    <span
                        class="sort-item"
                        id="date_public"
                        v-on:click="cryptoSort($event)"
                        :class="{ asc: (crypto_sort_by == 'date_public' && crypto_sort_dir == 'asc'), desc: (crypto_sort_by == 'date_public' && crypto_sort_dir == 'desc') }"
                    >Event Date</span>
                    <span
                        class="sort-item"
                        id="coin_price_changes"
                        v-on:click="cryptoSort($event)"
                        :class="{ asc: (crypto_sort_by == 'coin_price_changes' && crypto_sort_dir == 'asc'), desc: (crypto_sort_by == 'coin_price_changes' && crypto_sort_dir == 'desc') }"
                    >Changes</span>
                  </div>
                </div>
              </div>
              <div class="block-list">
                <table
                    v-if="events.length"
                >
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Time</th>
                      <th class="text-center">Impact</th>
                      <th>Event</th>
                      <th>Chg%</th>
                      <th>Categories</th>
                      <th>Graph</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                        v-for="(event, index) in events"
                        :key="index"
                        :id="event._id"
                        :data-date_start="event.date_start"
                        :data-date_sort="event.date_start_sort"
                    >
                      <td>{{ event.date_start | moment_date }}</td>
                      <td>
                        <div class="date">
                          <span class="start" v-html="moment_compare(event.date_public)" style="display: none;"></span>
                          <span class="public">{{ event.start_time }}</span>
                        </div>
                      </td>
                      <td class="text-center"><span class="bookmark" :class="{ important: event.important === 'true' }"><i class="fas fa-bookmark"></i></span></td>
                      <td>
                        <div class="event">
                          <img :src="coin_image(event.coin)" width="26" height="26">
                          <h4><a :href="event.source" target="_blank">{{ event.caption }}</a></h4>
                        </div>
                        <div class="added">Added {{ event.date_public | moment_from_now }}</div>
                      </td>
                      <td><span :class="{ positive: event.coin_price_changes > 0, negative: event.coin_price_changes <= 0 }">{{ event.coin_price_changes }}</span></td>
                      <td><a href="#" class="category" :class="tag_obj(event.tags).name.toLowerCase()">{{ tag_obj(event.tags).name }}</a></td>
                      <td>
                        <div class="graph">
                            <div class="value"><span v-on:click="setChart(event._id)" :class="{ clicked: charted.includes(event._id) }"><i class="fa-thin fa-chart-simple"></i></span></div>
                            <div class="value"><span v-on:click="setBookmark(event._id)" :class="{ clicked: bookmarked.includes(event._id) }"><i class="fa-thin fa-bookmark"></i></span></div>
                            <div class="value"><span v-on:click="setNotify(event._id)" :class="{ clicked: notified.includes(event._id)}"><i class="fa-thin fa-bell"></i></span></div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="pagination-container"><Pagination :changePage="getEventsData" :data="pagination_data"/></div>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import ClickOutside from 'vue-click-outside'
import Highcharts from "highcharts"
import exportingInit from "highcharts/modules/exporting"
import timelineInit from "highcharts/modules/timeline"
import stockInit from 'highcharts/modules/stock'
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip'

import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import 'flatpickr/dist/themes/dark.css'

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import Pagination from 'vue-laravel-paginex';

exportingInit(Highcharts);
timelineInit(Highcharts);
stockInit(Highcharts);

export default {
    name: 'PriceMetrics',
    components: {
      Loading,
      VueCustomTooltip,
      flatPickr,
      vSelect,
      DatePicker,
      Pagination
    },
    data() {
        return {
            multiline: !0,
            date_range_object: {},
            opened: false,
            coin_objt: {},
            categories: [],
            prices: [],
            date: null,
            flatpickr_config: {
                altFormat: "F j, Y",
                dateFormat: 'Y-m-d',
                altInput: false,
                mode: "range",
            },
            chartOptions: {
                chart: {
                    spacingTop: 20,
                    backgroundColor: '#2E2E33',
                    maxWidth: 1920,
                    height: 540,
                    events: {
                        load: function() {
                            let x = this.chartWidth / 2 - 356 / 2;
                            let y = this.chartHeight / 2 - 200 / 2;
                            this.renderer.image('https://media.coingen.net/logo-transparency0.png', x, y, 356, 200)
                                .attr({
                                    opacity: 0.98,
                                    zIndex: 9999
                                })
                                .add();

                          const xAxis = this.xAxis[0];
                          xAxis.setExtremes(xAxis.dataMin, xAxis.dataMin + (xAxis.max - xAxis.min), true, false);
                        }
                    },
                },
                rangeSelector: {
                  //enabled: false,
                  allButtonsEnabled: true,
                  labelStyle: {
                    color: '#666',
                    fontWeight: 'bold'
                  },
                  selected: 5,
                  //inputBoxBorderColor: '#f1f1f1',
                  inputStyle : {
                    color : '#FFFFFF',
                    backgroundColor: '#6164ff',
                    fontWeight: 400
                  },
                  inputEnabled: true,
                  buttons: [{
                    type: 'day',
                    count: 14,
                    text: '14d'
                  }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                  }, {
                    type: 'month',
                    count: 3,
                    text: '3m'
                  }, {
                    type: 'ytd',
                    text: 'YTD'
                  }, {
                    type: 'year',
                    count: 1,
                    text: '1y'
                  }, {
                    type: 'all',
                    text: 'All'
                  }],
                  buttonTheme: { // styles for the buttons
                    fill: '#545454',
                    stroke: 'none',
                    'stroke-width': 0,
                    r: 5,
                    style: {
                      color: '#f1f1f1',
                      fontWeight: '500'
                    },
                    states: {
                      hover: {
                        fill: '#222227',
                        style: {
                          color: '#f1f1f1'
                        }
                      },
                      select: {
                        fill: '#222',
                        style: {
                          color: '#fff'
                        }
                      }
                    },
                    width: 60
                  },
                },
                colors: ['#6164ff','#F6BD16F2','#4CF1D7','#3699FF','#BEFE0A','#D91E18'],
                title: {
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    },
                    useHTML: true
                },
                xAxis: {
                    /*categories: [],
                    lineWidth: 0,
                    gridLineWidth: 0,
                    gridLineColor: "#FFF000",
                    labels: {
                        style: {
                            fontSize: '13px',
                            color: '#89969C',
                            font: '400 12px "IBM Plex Sans", Verdana, sans-serif'
                        }
                    },
                    type: 'datetime',
                    labels: {
                        formatter: function(){
                            //return moment(new Date(this.value)).format('DD');
                            //return this.value;
                        //},
                    },*/
                    //min: + new Date,
                    //max: + new Date + (1 * 14 * 24 * 3600 * 1000),
                    //minRange: 1
                },
                yAxis: {
                    //min: 0,
                    title: false,
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    labels: {
                      style: {
                        color: "#FFFFFF"
                      },
                      //format: '{value}',
                      formatter: function () {
                        var num = this.value;
                        if (Math.abs(num) < 1000) {
                          return "$"+ num;
                        }
                        var shortNumber;
                        var exponent;
                        var size;
                        var sign = num < 0 ? '-' : '$';
                        var suffixes = {
                          'K': 6,
                          'M': 9,
                          'B': 12,
                          'T': 16
                        };

                        num = Math.abs(num);
                        size = Math.floor(num).toString().length;
                        exponent = size % 3 === 0 ? size - 3 : size - (size % 3);
                        shortNumber = Math.round(10 * (num / Math.pow(10, exponent))) / 10;
                        for (var suffix in suffixes) {
                          if (exponent < suffixes[suffix]) {
                            shortNumber += suffix;
                            break;
                          }
                        }

                        return sign + shortNumber;
                      },
                      align: "left",
                      x: 0,
                      y: -2
                    },
                    opposite: true,
                },
                tooltip: {
                  shared: true,
                  backgroundColor: '#222227',
                  //borderColor: '#FFFFFF',
                  style: {
                    fontSize: '13px',
                    color: '#FFFFFF',
                    font: '400 13px "IBM Plex Sans", Verdana, sans-serif'
                  },
                  useHTML: true,
                  /*tooltip: {
                      valueDecimals: 2
                  },*/
                  xDateFormat: '%B %d, %Y %H:%M',
                  /*formatter: function () {
                    return this.points.reduce(function (s, point) {
                        var num = point.y;
                        if (Math.abs(num) < 1000) {
                          return num;
                        }
                        var shortNumber;
                        var exponent;
                        var size;
                        var sign = num < 0 ? '-' : '';
                        var suffixes = {
                          'K': 6,
                          'M': 9,
                          'B': 12,
                          'T': 16
                        };
                        num = Math.abs(num);
                        size = Math.floor(num).toString().length;
                        exponent = size % 3 === 0 ? size - 3 : size - (size % 3);
                        shortNumber = Math.round(10 * (num / Math.pow(10, exponent))) / 10;
                        for (var suffix in suffixes) {
                          if (exponent < suffixes[suffix]) {
                            shortNumber += suffix;
                            break;
                          }
                        }

                        var date = new Date(Number(s));
                        var month = date.getMonth() + 1;
                        var day = date.getDate();
                        var hour = date.getHours();
                        var min = date.getMinutes();
                        var sec = date.getSeconds();

                        month = (month < 10 ? "0" : "") + month;
                        day = (day < 10 ? "0" : "") + day;
                        hour = (hour < 10 ? "0" : "") + hour;
                        min = (min < 10 ? "0" : "") + min;
                        sec = (sec < 10 ? "0" : "") + sec;

                        let str = date.getFullYear() + "-" + month + "-" + day + " " +  hour + ":" + min + ":" + sec;

                        return str + '<br/>' + point.series.name + ': $' + shortNumber;
                    }, this.x);
                  },*/
                },
                plotOptions: {
                    flags: {
                        useHTML: true,
                        dataLabels: {
                          borderRadius: 5
                        },
                        tooltip: {
                            backgroundColor: null,
                            borderWidth: 0,
                            shadow: false,
                            useHTML: true,
                            style: {
                                padding: 0
                            }
                        },
                        style: {
                            fontSize: '13px',
                            color: '#fff',
                            font: '400 12px "IBM Plex Sans", Verdana, sans-serif'
                        },
                        color: "#FFFFFF",
                        /*accessibility: {
                            exposeAsGroupOnly: true,
                            description: 'Flagged events.'
                        }*/
                    }
                },
                series: [{
                    name: 'Prices',
                    //type: 'line',
                    id: 'dataseries',
                    tooltip: {
                        valueDecimals: 4
                    },
                    data: [],
                    zIndex: 9
                },{
                    type: 'flags',
                    onSeries: 'dataseries',
                    //shape: 'flag',
                    className: 'aaa',
                    data: [],
                    useHTML: true,
                    //color : Highcharts.getOptions().colors[4],
                    //fillColor : Highcharts.getOptions().colors[4],
                    style : {// text style
                        color : 'white',
                        fontWeight: 400
                    },
                    borderRadius: 5,
                    states : {
                        hover : {
                            fillColor : '#222227' // darker
                        }
                    },
                    y: -45,
                    zIndex: 10
                }],
                credits: false
            },
            marco_events: [],
            tab: "marco",
            date_range: null,
            tags: window.tags,
            opened_category: !1,
            blank_category: "Categories",
            selected_category: null,
            coins: window.coins,
            opened_coin: !1,
            blank_coin: "Coins",
            selected_coin: null,
            symbols: [],
            events: [],
            pagination_data: null,
            crypto_sort_by: null,
            crypto_sort_dir: null,
            impacts: [],
            importants: [],
            opened_important: !1,
            blank_important: "Impact",
            selected_important: null,
            charted: [],
            bookmarked: [],
            notified: [],
            is_favourite: !1,
        };
    },
    directives: {
        ClickOutside
    },
    props: {
        coin_object: Object,
    },
    watch: {
        coin_object: [{
            handler: 'getCoin'
        }]
    },
    filters: {
      moment_date: function (date) {
        return date ? moment(date).format('MMM Do YYYY') : null
      },
      moment_time: function (date) {
        return date ? moment(date).format('H:mm:ss a') : null
      },
      moment_from_now(date) {
        return moment(date).fromNow();
      },
      _moment_compare: function (date_public) {
        let date = moment(date_public)
        let now = moment().utc()
        if (now <= date) {
          return '<i class="fa-solid fa-play"></i>'
        }
      },
    },
    computed: {
      max_date() {
        return moment()
      },
      coin_name() {
        return this.coin_object.name
      },
      selectedCategory() {
        if(this.categories.length) {
          let tag_name = this.tag_obj(this.categories[0]).name
          if(this.categories.length > 1) {
            return tag_name + " and " + (this.categories.length-1);
          } else {
            return tag_name
          }
        } else {
          return this.blank_category
        }
      },
      selectedCoin() {
        if(this.symbols.length) {
          let coin_name = this.coin_obj(this.symbols[0]).name
          if(this.symbols.length > 1) {
            return coin_name + " and " + (this.symbols.length-1);
          } else {
            return coin_name
          }
        } else {
          return this.blank_coin
        }
      },
      selectedImportant() {
        if(this.importants.length) {
          let impact_name = this.impacts[0].name
          if(this.importants.length > 1) {
            return impact_name + " and " + (this.importants.length-1);
          } else {
            return impact_name
          }
        } else {
          return this.blank_important
        }
      },
    },
    created() {
      this.createFilter()
      this.charted = JSON.parse(localStorage.getItem("charted") || '[]');
      this.bookmarked = JSON.parse(localStorage.getItem("bookmarked") || '[]');
      this.notified = JSON.parse(localStorage.getItem("notified") || '[]');
    },
    mounted() {
      this.selectDateRange(this.date_range_object);
      this.getMarcoEventsData();
      this.getEventsData()
      this.$nextTick(() => {
        //this.chartOptions.rangeSelector.selected = 2
        //chart.rangeSelector.buttons[X].element.onclick();
        //this.$refs.marco_event_chart.rangeSelector.clickButton(2)
      })
    },
    methods: {
        momentDate : function (date) {
          return moment(date, 'YYYY-MM-DD').format('MM/DD/YYYY');
        },
        momentTime : function (date) {
          return moment(date, 'YYYY-MM-DD h:mm:ss').format('h:mm:ss');
        },
        getCoin() {
            this.coin_objt = this.coin_object
        },
        createFilter() {
            this.filterArr = [{ value: '1d', name: '24H'}, { value: '2d', name: '48H'}, { value: '7d', name: '7D'}, { value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}];
            this.date_range_object = this.filterArr[6];
            this.impacts = [{id: 1, name: 'Yes'},{id: 0, name: 'No'}]
        },
        toggleDropdown() {
            this.active = !this.active
            this.opened = !this.opened
        },
        hideDropdown() {
            this.opened = false
        },
        selectDateRange(d) {
            this.date_range_object = d;
            this.getData();
        },
        getData() {
            let coin_id = this.$store.state.menuItem.coin_id
            let days = this.date_range_object.value

            axios.get("https://pro.coingen.net/api/v3/coin/prices?coin_id="+coin_id).then(response => {
                this.fillData(response.data);

                //setTimeout(() => this.chartOptions.rangeSelector.clickButton(2), 1100);
                //setTimeout(() => this.chartOptions.rangeSelector.selected = 6, 1100);

                /*if(this.date_range_object.value === "1d") {
                    this.chartOptions.xAxis[0].labels.step = 15;
                } else if(this.date_range_object.value === "2d") {
                    this.chartOptions.xAxis[0].labels.step = 30;
                } else if(this.date_range_object.value === "30d") {
                    this.chartOptions.xAxis[0].labels.step = 3;
                } else if(this.date_range_object.value === "90d") {
                    this.chartOptions.xAxis[0].labels.step = 9;
                } else if(this.date_range_object.value === "180d") {
                    this.chartOptions.xAxis[0].labels.step = 18;
                } else if(this.date_range_object.value === "1y") {
                    this.chartOptions.xAxis[0].labels.step = 36;
                } else {
                    this.chartOptions.xAxis[0].labels.step = 1;
                }*/
            }).catch(e => {
                console.log(e)
            });
        },
        fillData(data) {
            //this.categories = [];
            //this.prices = [];
            this.timeline = [];
            //for (var i = 0; i < data.length; i++) {
                //this.categories.push(data[i].date);
                //this.prices.push(data[i].price);
                //this.timeline.push({x: data[i].time, name: "test", label: "testaaa"})
            //}
            //console.log(this.timeline);
            //this.chartOptions.xAxis.categories = this.categories;
            //this.chartOptions.series[0].data = this.prices;
            this.chartOptions.series[0].data = data;

            const marco_event_arr = this.marco_events
            for (var i = 0; i < marco_event_arr.length; i++) {
                let date = Date.UTC(marco_event_arr[i].y, marco_event_arr[i].m, marco_event_arr[i].d);
                this.timeline.push({x: marco_event_arr[i].timestamp, title: marco_event_arr[i].name, text: marco_event_arr[i].text, color: marco_event_arr[i].color, fillColor: marco_event_arr[i].color })
            }
            this.chartOptions.series[1].data = this.timeline;
        },
        getMarcoEventsData() {
            axios.get("https://pro.coingen.net/api/marco-events", { params: { date_range: this.date } }).then(response => {
                this.marco_events = response.data
            }).catch(e => {
                console.log(e)
            });
        },
        getFormattedDate(d) {
          var date = new Date(d);

          var month = date.getMonth() + 1;
          var day = date.getDate();
          var hour = date.getHours();
          var min = date.getMinutes();
          var sec = date.getSeconds();

          month = (month < 10 ? "0" : "") + month;
          day = (day < 10 ? "0" : "") + day;
          hour = (hour < 10 ? "0" : "") + hour;
          min = (min < 10 ? "0" : "") + min;
          sec = (sec < 10 ? "0" : "") + sec;

          return date.getFullYear() + "-" + month + "-" + day + "_" +  hour + ":" + min + ":" + sec;
        },
        selectTab(menu) {
          this.tab = menu
          if(this.tab == "crypto") {
            this.is_favourite = !1
            this.getEventsData()
          }
        },
      toggleDropdownCategories() {
        this.opened_category = !this.opened_category
      },
      hideDropdownCategories() {
        this.opened_category = !1
      },
      selectTag(tag_id) {
        const exists = this.categories.includes(tag_id)
        if (exists) {
          const result = this.categories.filter((category_id) => {
            return Number(category_id) !== Number(tag_id)
          })
          this.categories = result
        } else {
          this.categories.push(tag_id)
        }
        setTimeout( () => { this.getEventsData() }, 1100)
      },
      toggleDropdownCoins() {
        this.opened_coin = !this.opened_coin
      },
      hideDropdownCoins() {
        this.opened_coin = !1
      },
      selectCoin(coin_id) {
        const exists = this.symbols.includes(coin_id)
        if (exists) {
          const result = this.symbols.filter((symbol_id) => {
            return Number(symbol_id) !== Number(coin_id)
          })
          this.symbols = result
        } else {
          this.symbols.push(coin_id)
        }

        setTimeout( () => { this.getEventsData() }, 1100)
      },
      selectImpact(id) {
        const exists = this.importants.includes(id)
        if (exists) {
          const result = this.importants.filter((important_id) => {
            return Number(important_id) !== Number(id)
          })
          this.importants = result
        } else {
          this.importants.push(id)
        }

        setTimeout( () => { this.getEventsData() }, 1100)
      },
      cryptoSort(event) {
        let sort_by = event.currentTarget.id
        this.crypto_sort_by = sort_by
        let sort_dir = this.crypto_sort_dir
        this.crypto_sort_dir = (sort_dir == "desc") ? "asc" : "desc"
        //console.log(this.crypto_sort_by)
        //console.log(this.crypto_sort_dir)
        setTimeout( () => { this.getEventsData() }, 600)
      },
      toggleDropdownImportant() {
        this.opened_important = !this.opened_important
      },
      hideDropdownImportant() {
        this.opened_important = !1
      },
      getEventsData(parameters) {
        let page = parameters !== undefined ? parameters.page : 1
        const category_arr = [];
        if(this.categories.length) {
          this.categories.forEach(function(item, index, arr){
            category_arr.push(item.id)
          });
        }
        const coin_arr = [];
        if(this.symbols.length) {
          this.symbols.forEach(function(item, index, arr){
            coin_arr.push(item.id)
          });
        }
        const important_arr = [];
        if(this.importants.length) {
          this.importants.forEach(function(item, index, arr){
            important_arr.push(item)
          });
        }

        this.$store.commit('setLastLoading', !0)
        axios.get("https://pro.coingen.net/api/v3/events",
            {
              params: {
                date_range: this.date_range,
                categories: this.categories,
                coins: this.symbols,
                impact: important_arr,
                is_favourite: this.is_favourite,
                favourite: this.bookmarked,
                page: page,
                order_by: this.crypto_sort_by,
                order: this.crypto_sort_dir
              }
            }).then(response => {
          this.pagination_data = response.data;
          this.events = this.pagination_data.data
          this.$store.commit('setLastLoading', !1)
        }).catch(e => {
          console.log(e)
        });
      },
      coin_obj(coin_id) {
        let coin = this.coins.filter(
            (coin) => coin.id == coin_id
        )[0];
        return coin;
      },
      coin_data(coin) {
        const myobj = JSON.parse(coin)
        return myobj
      },
      coin_image(coin) {
        const myobj = JSON.parse(coin)
        return myobj !== null ? myobj.image_64 : null
      },
      tag_obj(tag_id) {
        let tag = this.tags.filter(
            (tag) => tag.id == tag_id
        )[0];
        return tag;
      },
      moment_time(datetime) {
        let date = moment(datetime)
        let now = moment();
        if (now <= date) {
          return '<i class="fa-solid fa-play"></i>';
        }
      },
      setChart(id) {
        let index = this.charted.indexOf(id)
        if (index === -1) {
          this.charted.push(id);
        } else {
          this.charted.splice(index, 1);
        }
        localStorage.setItem("charted", JSON.stringify(this.charted));
      },
      setBookmark(id) {
        let index = this.bookmarked.indexOf(id)
        if (index === -1) {
          this.bookmarked.push(id);
        } else {
          this.bookmarked.splice(index, 1);
        }
        localStorage.setItem("bookmarked", JSON.stringify(this.bookmarked));
        if(this.is_favourite) {
          setTimeout( () => { this.getEventsData() }, 1100)
        }
      },
      setNotify(id) {
        let index = this.notified.indexOf(id)
        if (index === -1) {
          this.notified.push(id);
        } else {
          this.notified.splice(index, 1);
        }
        localStorage.setItem("notified", JSON.stringify(this.notified));
      },
      moment_compare(date_public) {
        let date = moment(date_public).format("YYYY-MM-DD HH:mm")
        //let now = moment().utc().format("YYYY-MM-DD HH:mm")
        let now = moment().utcOffset('-0200').format("YYYY-MM-DD HH:mm")
        console.log(now)
        console.log(date)

        if (now <= date) {
          return '<i class="fa-solid fa-play"></i>'
        } 
      },
      selectFavourite() {
        //this.is_favourite = !this.is_favourite
        this.is_favourite = !0
        if(this.bookmarked.length) this.getEventsData()
      },
      resetFilter() {
        this.symbols = []
        this.events = []
        this.categories = []
        this.crypto_sort_by = null
        this.crypto_sort_dir = null
        this.impacts = []
        this.importants = []
        this.selected_important = null
        this.charted = []
        this.bookmarked = []
        this.notified = []
        this.is_favourite = !1
        this.getEventsData()
      }
    },
};
</script>
<style scoped>
.price-metrics-chart {
    position: relative;
    width: 100%;
    padding: 1rem 0.25em;
    background: #2E2E33;
    margin-top: 1em;
    margin-bottom: 1em;
}
.price-metrics-chart .more {
    display: block;
    text-align: right;
    padding-right: 20px;
}
.chart-title {
    display: flex;
    align-items: center;
    justify-content: center;
}
.chart-title i {
    margin-left: 10px;
}
.highcharts-filter {
    display: flex;
    position: absolute;
    z-index: 9;
    left: 10px;
    top: 10px;
}
.highcharts-filter button {
    padding: 0.1rem 1rem;
    border: 1px solid #fff;
    color: #fff;
    margin-right: 5px;
    font-size: 12px;
}
.highcharts-filter button:hover,
.highcharts-filter button.active {
    background: #fff;
    color: #222227;
}
iframe {
    width: 100%;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
  color: #fff;
}
table thead {
  background: #545454;
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  border-radius: 10px;
}
table th {
  border: 0;
  padding: 10px 15px;
  font-weight: bold;
  font-size: 1rem;
  line-height: 24px;
}
table td {
    padding: 8px 15px;
}
table tbody tr:nth-child(odd),
table tbody tr:nth-child(even) {
  background-color: transparent
}
table tbody tr:nth-child(even) {
  border: 1px solid #545454;
}
table .event {
  display: flex;
}
table .event img {
  width: 24px;
  height: 24px;
  margin-right: 10px;
}
table .event h4 {
  font-size: 1rem;
  line-height: 24px;
  font-weight: 600;
}
table .added {
  color: rgba(255,255,255,.5);
  font-size: 13px;
  padding-top: 1px;
  padding-left: 34px;
}
table .bookmark {
  color: #f6ef41;
  font-size: 24px;
}
table .bookmark.important {
  color: #ff1616;
}
table .bookmark i {
  transform: rotate(90deg);
}
table .graph {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 24px;
}
table .date {
  display: flex;
  align-items: center;
}
table .date .start {
  margin-right: 5px;
  color: #5EFF5A;
}
.flatpickr-input {
    background: #3f4458;
    border: 1px solid #a2a3b7;
    color: #a2a3b7;
    font-size: 0.875rem;
    margin-bottom: 10px;
    padding: 4px 8px;
}
table .graph .value span {
  cursor: pointer;
}
table .graph .value .clicked .fa-thin {
  font-weight: 600;
}
</style>
