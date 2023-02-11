<template>
    <div class="mcap-metrics-chart">
        <div class="chart-title">Mcap Metrics <VueCustomTooltip label="This is the ratio between these datas: Volume, Market Cap (MC) and Fully Diluted Value (FDV)." :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
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
        <highcharts class="hc" :options="chartOptions" ref="mcap_metrics_chart"></highcharts>
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
    name: 'McapMetrics',
    data() {
        return {
            windowWidth: window.innerWidth,
            multiline: !0,
            date_range_object: {},
            opened: false,
            categories: [],
            mcap: [],
            volume: [],
            fdv: [],
            chartOptions: {
                chart: {
                    type: 'column',
                    backgroundColor: '#2E2E33',
                    maxWidth: 1920,
                    //spacing: [30, 15, 30, 15],
                    renderTo: 'container',
                    events: {
                        load: function() {
                            let x = this.chartWidth / 2 - 356 / 2;
                            let y = this.chartHeight / 2 - 200 / 2;
                            this.renderer.image('https://media.coingen.net/logo-transparency0.png', x, y-50, 356, 200)
                                .attr({
                                    opacity: 0.98,
                                    zIndex: 9999
                                })
                                .add();
                        }
                    },
                    spacingTop: 10,
                    spacingBottom: 10,
                    height: 440,
                },
                colors: ['#8C59D2', '#7333FD', '#4CFED0'],
                title: {
                    text: 'Mcap Metrics',
                    align: 'center',
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    }
                },
                xAxis: {
                    categories: [],
                    lineWidth: 0,
                    gridLineWidth: 0,
                    gridLineColor: "#FFF000",
                    labels: {
                        style: {
                            fontSize: '13px',
                            color: '#89969C',
                            font: '400 12px "IBM Plex Sans", Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: false,
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    labels: {
                        formatter: function () {
                            var num = this.value;
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

                            return "$" + sign + shortNumber;
                        }
                    }
                },
                tooltip: {
                    pointFormat: '<span>{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                    shared: true,
                    backgroundColor: '#222227',
                    //borderColor: '#FFFFFF',
                    style: {
                        fontSize: '13px',
                        color: '#FFFFFF',
                        font: '400 13px "IBM Plex Sans", Verdana, sans-serif'
                    },
                    useHTML: true,
                    formatter: function () {
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
                            return s + '<br/>' + point.series.name + ': $' + shortNumber;
                        }, '<b>' + this.x + '</b>');
                    },
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    },
                    series: {
                        borderWidth: 0,
                        pointWidth: 48
                    }
                },
                series: [{
                    name: 'FDV',
                    data: []
                }, {
                    name: 'Mcap',
                    data: []
                }, {
                    name: 'Volume',
                    data: []
                }],
                legend: {
                    align: 'center',
                    verticalAlign: 'top',
                    itemStyle: {
                        fontSize: '14px',
                        font: '400 14px IBM Plex Sans, Verdana, sans-serif',
                        color: '#FFFFFF'
                    },
                    itemHoverStyle: {
                        color: '#FFF'
                    },
                },
                rangeSelector: {
                    "enabled": false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 1140
                        },
                        chartOptions: {
                            chart: {
                                width: '100%'
                            }
                        }
                    }]
                },
                exporting: {
                    enableImages: true,
                    exporting: true,
                    width: 1920,
                    sourceHeight: 650,
                    sourceWidth: 1920,
                    scale: 1
                },
                credits: false
            }
        };
    },
    directives: {
        ClickOutside
    },
    created() {
        this.createFilter()
    },
    mounted() {
        setTimeout(() => this.selectDateRange(this.date_range_object), 2000);
        this.$nextTick(() => {

        })
        window.addEventListener('resize', () => {
          this.windowWidth = window.innerWidth
          this.selectDateRange(this.date_range_object)
        })
    },
    methods: {
        createFilter() {
            this.filterArr = [{ value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}];
            this.date_range_object = this.filterArr[0];
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
            if(coin_id) {
              this.$store.commit('setLastLoading', true);
              axios.post("https://pro.coingen.net/api/v3/coin/single", { coin_id: coin_id, type: this.date_range_object.value }).then(response => {
                const data = response.data;
                this.fillData(data);
                this.$store.commit('setLastLoading', false);
              });
            }

        },
        fillData(data) {
            //const data = this.mcap_data;
            this.categories = [];
            this.mcap = [];
            this.volume = [];
            this.fdv = [];

            for (var i = 0; i < data.length; i++) {
                this.categories.push(data[i].label);
                this.mcap.push(data[i].market_cap);
                this.volume.push(data[i].total_volume);
                this.fdv.push(data[i].fdv);
            }
            this.chartOptions.xAxis.categories = this.categories;
            this.chartOptions.series[0].data = this.fdv;
            this.chartOptions.series[1].data = this.mcap;
            this.chartOptions.series[2].data = this.volume;
            if(this.date_range_object.value == "1y") {
                var pointWidth = 45;
                if(this.windowWidth >= 991 && this.windowWidth <= 1280) {
                  var pointWidth = 1;
                }
            } else if(this.date_range_object.value == "180d") {
                var pointWidth = 3;
            } else if(this.date_range_object.value == "90d") {
                var pointWidth = 6;
            } else if(this.date_range_object.value == "30d") {
                var pointWidth = 18;
                if(this.windowWidth >= 991 && this.windowWidth <= 1280) {
                  var pointWidth = 9;
                }
            } else if(this.date_range_object.value == "7d" || this.date_range_object.value == "1d") {
                var pointWidth = 32;
            } else {
                var pointWidth = 52;
            }
            this.chartOptions.plotOptions.series.pointWidth = pointWidth;
            this.$store.commit('setLastLoading', false);
        }
    },
};
</script>
<style scoped>
.mcap-metrics-chart {
    position: relative;
    width: calc(100% - 360px - 1em);
    margin-top: 1em;
    margin-bottom: 1em;
    background: #2E2E33;
    padding: 1rem 0.25em;
}
.mcap-metrics-chart .more {
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
