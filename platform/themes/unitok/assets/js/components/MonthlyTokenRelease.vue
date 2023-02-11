<template>
    <div class="tdm-metrics-chart">
        <div class="chart-title">Monthly Token Release <VueCustomTooltip label="This metric include the actual amount of tokens put into the market: tokens unlocked for investors, team, activity, ecosytem... and block rewards as well. The price is the hgighest price in the month." :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
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
        <highcharts class="hc" :options="chartOptions" ref="tdm_metrics_chart"></highcharts>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside';
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import accessibility from "highcharts/modules/accessibility";
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';
exportingInit(Highcharts);
accessibility(Highcharts)

export default {
    name: 'tdm-metrics-chart',
    components: {
        VueCustomTooltip
    },
    created() {
        this.createFilter()
    },
    data() {
        return {
            coin_obj: {},
            multiline: !0,
            categories: [],
            mcap: [],
            date_range_object: {},
            opened: false,
            chartOptions: {
                chart: {
                    zoomType: 'xy',
                    spacingTop: 30,
                    //spacingBottom: 20,
                    backgroundColor: '#2E2E33',
                    maxWidth: 1920,
                    height: 440,
                    //spacing: [45, 15, 15, 15],
                    //margin: [30, 15, 15, 15],
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
                colors: ['#1EADFC','#FB09E3'],
                title: {
                    text: 'Monthly total token release',
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    }
                },
                xAxis: [{
                    categories: [],
                    crosshair: true,
                    lineWidth: 0,
                    gridLineWidth: 0,
                    gridLineColor: "#FFF000",
                    labels: {
                        style: {
                            fontSize: '13px',
                            color: '#89969C',
                            font: '400 12px "IBM Plex Sans", Verdana, sans-serif'
                        },
                        step: 2,
                    }
                }],
                lang: {
                    numericSymbols: [' K', ' M']
                },
                yAxis: [{ // yAxis
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    title: {
                        text: 'Token Release',
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    labels: {
                        style: {
                            color: "#FFFFFF"
                        },
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

                            return sign + shortNumber;
                        }
                    },
                    min: 0,
                }, { // yAxis right
                    gridLineColor: 'rgba(78, 76, 76, 0.42)',
                    gridLineWidth: 1,
                    title: {
                        text: 'Price',
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    labels: {
                        formatter: function () {
                            var num = this.value;
                            if (Math.abs(num) < 1000) {
                                return "$"+num;
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

                            return sign + shortNumber + "$";
                        },
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    opposite: true,
                    min: 0,
                }],
                tooltip: {
                    shared: true,
                    split: true,
                    useHTML: true,
                    backgroundColor: '#222227',
                    /*borderColor: '#FFFFFF',*/
                    style: {
                        fontSize: '13px',
                        color: '#FFFFFF',
                        font: '400 13px "IBM Plex Sans", Verdana, sans-serif'
                    },
                    //pointFormat: '<b>{point.x}</b>' + '<br>' + '<b>{point.y}</b>',
                    formatter: function () {
                        return [this.x].concat(
                            this.points ?
                                this.points.map(function (point) {
                                    if(point.series.name == "Price") {
                                        return point.series.name + ': $' + point.y;
                                    } else {
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
                                        return point.series.name + ': ' + shortNumber;
                                    }
                                }) : []
                        );
                    },
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 100,
                    verticalAlign: 'top',
                    y: 10,
                    floating: true,
                    backgroundColor: 'rgba(0,0,0,0.25)',
                    title: {
                        style: {
                            color: "#FFFFFF"
                        }
                    },
                    itemStyle: {"color": "#89969C", "fontWeight": "400"},
                    itemHoverStyle: {"color": "#ffffff", "fontWeight": "500"}
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                    },
                    series: {
                        borderWidth: 0,
                        pointWidth: 16
                    }
                },
                series: [{
                    name: 'Price',
                    type: 'spline',
                    yAxis: 1,
                    data: [],
                    color: '#F6BD16F2',
                    marker: {
                        enabled: false
                    },
                    zIndex: 2,
                },{
                    name: 'Token Release',
                    type: 'column',
                    yAxis: 0,
                    data: [],
                    color: '#6164ff',
                    zIndex: 1
                }],
                credits: false
            }
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
        setTimeout(() => this.selectDateRange(this.date_range_object), 3000);
    },
    methods: {
        getCoin: function () {
            this.coin_obj = this.coin_object
        },
        createFilter() {
            this.filterArr = [{ value: '30d', name: '30D'}, { value: '90d', name: '90D'}, { value: '180d', name: '180D'}, { value: '1y', name: '1Y'}, { value: 'all', name: 'All'}];
            this.date_range_object = this.filterArr[4];
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
            //console.log("A"+this.$store.state.menuItem)
            axios.post("https://pro.coingen.net/api/v3/coin/monthly", { coin_id: this.$store.state.projectMenuItem.coin_id, type: this.date_range_object.value }).then(response => {
                const data = response.data;
                this.fillData(data);
                this.$store.commit('setLastLoading', false);
            }).catch(e => {
                console.log(e)
            });
        },
        fillData(data) {
            this.chartOptions.yAxis[0].title.text = "Token Release ("+this.coin_object.symbol.toUpperCase()+")";

            this.categories = [];
            this.price = [];
            this.amount_release = [];
            for (var i = 0; i < data.length; i++) {
                if(i > 0) {
                    this.categories.push(data[i].label);
                    this.price.push(data[i].price);
                    this.amount_release.push(data[i].amount_release);
                }
            }
            this.chartOptions.xAxis[0].categories = this.categories
            this.chartOptions.series[0].data = this.price
            this.chartOptions.series[1].data = this.amount_release
        },
    },
};
</script>
<style scoped>
.tdm-metrics-chart {
    position: relative;
    width: calc(100% - 360px - 1em);
    /*width: 100%;*/
    background: #2E2E33;
    padding: 1rem 0.25em;
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
