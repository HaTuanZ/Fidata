<template>
    <div class="dom-metrics-chart">
        <div class="chart-title">{{ coin_symbol }}.Dom vs {{ chart_title }} <VueCustomTooltip label="A measure of token's value (ratio of token's market cap) in the context of the larger cryptocurrency market that is another tokens in the same sector." :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
        <div class="highcharts-filter">
            <div class="dropdown-select" v-click-outside="hideDropdown" @click="toggleDropdown">
                <div class="select">
                    <div class="my-select">{{ date_range_object.name }}</div>
                    <ul v-show="opened" class="dropdown">
                        <li
                            v-for="(filter, index) in filterArr"
                            :key="filter.id"
                            :id="filter.id"
                            v-on:click="selectDateRange(filter)"
                            v-bind:class="{ active: (filter.id === $store.state.filterData.date_range) }"
                        >
                            {{ filter.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <highcharts class="hc" :options="chartOptions" ref="test_metrics_chart"></highcharts>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSdS_RcYkoTreta7xS1lH3e2m4GMcailQBkn0LlqQ6evfzF0LQ/viewform" target="_blank" class="more"><i class="fal fa-comments"></i> Feedback</a>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside';
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';
exportingInit(Highcharts);

export default {
    name: 'DomMetrics',
    components: {
        VueCustomTooltip
    },
    computed: {
      currentRouteName() {
        return this.$route.name;
      },
      isMobile: function () {
        return this.detectMob();
      },
      isDesktop: function () {
        return this.windowWidth >= 1025
      },
    },
    created() {
        this.createFilter()
    },
    data() {
        return {
            windowWidth: window.innerWidth,
            multiline: !0,
            date_range_object: {},
            opened: false,
            coin_obj: {},
            categories: [],
            prices: [],
            token_release: [],
            block_rewards: [],
            burn: [],
            rebated: [],
            chartOptions: {
                chart: {
                    zoomType: 'xy',
                    spacingTop: 20,
                    //spacingBottom: 20,
                    backgroundColor: '#2E2E33',
                    maxWidth: 1920,
                    height: 540,
                    //spacing: [30, 15, 30, 15],
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
                        }
                    },
                },
                colors: ['#3699FF','#BEFE0A','#D91E18','#4CF1D7'],
                title: {
                    //text: 'Near.Dom vs Sector.Dom',
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    },
                    useHTML: true
                },
                xAxis: [{
                    categories: [],
                    //crosshair: true,
                    lineWidth: 0,
                    gridLineWidth: 0,
                    gridLineColor: "#FFF000",
                    labels: {
                        style: {
                            fontSize: '13px',
                            color: '#89969C',
                            font: '400 12px "IBM Plex Sans", Verdana, sans-serif'
                        },
                        step: 3,
                    }
                }],
                lang: {
                    numericSymbols: [' K', ' M']
                },
                yAxis: [{ // Inflation yAxis
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    title: {
                        text: 'Sector.Dom',
                        style: {
                            color: "#FFFFFF"
                        }
                    },
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
                        }
                    }
                }, { // Price yAxis
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    title: {
                        text: 'Near.Dom',
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    labels: {
                        formatter: function () {
                            var num = this.value;
                            if (Math.abs(num) < 1000) {
                                return num+"%";
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

                            return sign + shortNumber;
                        },
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    opposite: true
                }],
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
                    formatter: function () {
                        return this.points.reduce(function (s, point) {
                            if(point.series.name == "Sector.Dom") {
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
                                return s + '<br/>' + point.series.name + ': $' + shortNumber;
                            } else {
                                return s + '<br/>' + point.series.name + ': ' + point.y + '%';
                            }
                        }, '<b>' + this.x + '</b>');
                    },
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'top',
                    x: 90,
                    y: 10,
                    floating: true,
                    backgroundColor: 'rgba(0,0,0,0.25)',
                    title: {
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    itemStyle: {"color": "#89969C", "fontWeight": "400"},
                    itemHoverStyle: {"color": "#ffffff", "fontWeight": "500"},
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                    },
                    series: {
                        borderWidth: 0,
                        pointWidth: 16,
                        colors: ['#8853F9','#BEFE0A','#D91E18','#4CF1D7'],
                    },
                    area: {
                        /*fillColor: {
                            linearGradient: [0, 0, 0, 540],
                            stops: [
                                [0, Highcharts.getOptions().colors[0]],
                                [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                            ]
                        },
                        fillColor: {
                            linearGradient: [0, 0, 0, 540],
                            stops: [
                                [0, '#6164ff'],
                                [1, '#6164ff']
                            ]
                        },*/
                        marker: {
                            radius: 1
                        },
                        lineWidth: 0,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                series: [{
                    name: 'Near.Dom',
                    type: 'spline',
                    yAxis: 1,
                    data: [],
                    color: '#F6BD16F2',
                    marker: {
                        enabled: false
                    },
                    zIndex: 2,
                },{
                    name: 'Sector.Dom',
                    type: 'area',
                    yAxis: 0,
                    data: [],
                    color: '#3699FF',
                    //color: '#6164ff',
                    zIndex: 1,
                    fillOpacity: 1,
                    /*fillColor: {
                        linearGradient: [0, 0, 0, 540],
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0.15).get('rgba')]
                        ]
                    },*/
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0, Highcharts.Color('rgb(97, 100, 255)').setOpacity(1).get('rgba')],
                            [1, Highcharts.Color('rgb(97, 100, 255)').setOpacity(0.12).get('rgba')]
                        ]
                    },
                }],
                credits: false
            },
            chart_title: "Sector.Dom",
            coin_symbol: null,
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
    mounted() {
        this.selectDateRange(this.date_range_object);
        window.addEventListener('resize', () => {
          this.windowWidth = window.innerWidth
        })
    },
    methods: {
        detectMob: function () {
          return [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i].some(function (t) {
            return navigator.userAgent.match(t);
          });
        },
        getCoin() {
            this.coin_obj = this.coin_object
            setTimeout(() => this.chartOptions.title.text = this.coin_object.symbol.toUpperCase()+".Dom vs Sector.Dom", 300);
            setTimeout(() => this.chartOptions.series[0].name = this.coin_object.symbol.toUpperCase()+".Dom", 300);
            setTimeout(() => this.chartOptions.yAxis[1].title.text = this.coin_object.symbol.toUpperCase()+".Dom", 300);
            if(this.coin_obj.coin_id == "bitcoin") {
                this.chart_title = "Total Market cap";
            }
            this.coin_symbol = this.coin_obj.symbol.toUpperCase() || null
        },
        createFilter() {
            this.filterArr = [{ value: '7d', name: '7D'}, { value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}, { value: 'all', name: 'All'}];
            this.date_range_object = this.filterArr[2];
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
            this.$store.commit('setLastLoading', true);
            let coin_id = this.$store.state.menuItem.coin_id
            if(coin_id) {
              axios.post("https://pro.coingen.net/api/v3/coin/single", { coin_id: coin_id, type: this.date_range_object.value }).then(response => {
                this.fillData(response.data);

                //console.log(this.date_range_object.value)
                //console.log(this.windowWidth)

                if(this.date_range_object.value === "7d") {
                  this.chartOptions.xAxis[0].labels.step = 1;
                } else if(this.date_range_object.value === "30d") {
                  this.chartOptions.xAxis[0].labels.step = 3;
                } else if(this.date_range_object.value === "90d") {
                  if(this.windowWidth >= 1920) {
                    this.chartOptions.xAxis[0].labels.step = 7
                    console.log("1")
                  } else if(this.windowWidth >= 991 && this.windowWidth < 1920) {
                    this.chartOptions.xAxis[0].labels.step = 14
                    console.log("2")
                  } else {
                    console.log("3")
                    this.chartOptions.xAxis[0].labels.step = 30
                  }
                } else if(this.date_range_object.value === "180d") {
                  if(this.windowWidth >= 1920) {
                    this.chartOptions.xAxis[0].labels.step = 14;
                  } else if(this.windowWidth >= 991 && this.windowWidth < 1920) {
                    this.chartOptions.xAxis[0].labels.step = 21;
                  } else {
                    this.chartOptions.xAxis[0].labels.step = 30
                  }
                } else if(this.date_range_object.value === "1y") {
                  if(this.windowWidth >= 1920) {
                    this.chartOptions.xAxis[0].labels.step = 45;
                  } else if(this.windowWidth >= 991 && this.windowWidth < 1920) {
                    this.chartOptions.xAxis[0].labels.step = 60;
                  } else {
                    this.chartOptions.xAxis[0].labels.step = 90
                  }
                } else {
                  this.chartOptions.xAxis[0].labels.step = 90;
                }
                this.$store.commit('setLastLoading', false);
              }).catch(e => {
                console.log(e)
              });
            }
        },
        fillData(data) {
            this.categories = [];
            this.prices = [];
            this.token_release = [];
            for (var i = 0; i < data.length; i++) {
                this.categories.push(data[i].date);
                this.prices.push(data[i].dom_pt);
                this.token_release.push(data[i].market_cap_scp);
            }
            this.chartOptions.xAxis[0].categories = this.categories;
            this.chartOptions.series[0].data = this.prices;
            this.chartOptions.series[1].data = this.token_release;
        }
    },
};
</script>
<style scoped>
.dom-metrics-chart {
    position: relative;
    /*width: calc(100% - 360px + 1em);*/
    width: 100%;
    padding: 1rem 0.25em;
    background: #2E2E33;
    margin-top: 1em;
    margin-bottom: 1em;
}
.dom-metrics-chart .more {
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
</style>
