<template>
    <div class="supply-metrics-chart">
        <div class="chart-title">Supply Metrics <VueCustomTooltip label="The actual liquidity supply in the market and the ratio between these below data. The chart will not show a circle  of max supply for tokens has no max supply." :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
        <highcharts class="hc" :options="chartOptions" ref="supply_metrics_chart"></highcharts>
        <div class="reports">
            <ul>
                <li class="max"><VueCustomTooltip :label="max_supply"><span class="bullet"></span><span class="title">Max</span> <span class="progress-bar"><span class="current":style="{ width: `${max_width_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="total"><VueCustomTooltip :label="coin_object.total_supply | formatNumber"><span class="bullet"></span><span class="title">Total</span> <span class="progress-bar"><span class="current":style="{ width: `${total_width_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="circulating"><VueCustomTooltip :label="coin_object.circulating_supply | formatNumber"><span class="bullet"></span><span class="title">Circulating</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.circulating_supply_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="stake"><VueCustomTooltip :label="coin_object.stake | formatNumber"><span class="bullet"></span><span class="title">Stake</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.stake_pt}%`}"></span></span></VueCustomTooltip></li>
            </ul>
        </div>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSdS_RcYkoTreta7xS1lH3e2m4GMcailQBkn0LlqQ6evfzF0LQ/viewform" target="_blank" class="more"><i class="fal fa-comments"></i> Feedback</a>
    </div>
</template>

<script>
import Highcharts from "highcharts";
import highchartsMore from 'highcharts/highcharts-more';
import solidGauge from "highcharts/modules/solid-gauge";
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';

highchartsMore(Highcharts);
solidGauge(Highcharts);
var numeral = require("numeral");

export default {
    name: 'supply-metrics-chart',
    components: {
        VueCustomTooltip
    },
    data() {
        return {
            coin_obj: {},
            multiline: !0,
            max_supply: "",
            infinity: "∞",
            colors: ["#1EADFC", "#BEFE0A", "#FB09E3", "#4CF1D7"],
            max_width_pt: 100,
            total_width_pt: 100,
            chartOptions: {
                chart: {
                    type: 'solidgauge',
                    backgroundColor: '#2E2E33',
                    spacingTop: 10,
                    spacingBottom: 0,
                    height: 320,
                    events: {
                        load: function() {
                            let x = this.chartWidth / 2 - 130 / 2;
                            let y = this.chartHeight / 2 - 73 / 2;
                            this.renderer.image('https://media.coingen.net/logo-transparency0.png', x, y+24, 130, 73)
                                .attr({
                                    opacity: 0.88,
                                    zIndex: 9999
                                })
                                .add();
                        }
                    },
                },
                title: {
                    text: 'Supply Metrics',
                    align: 'center',
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    }
                },
                tooltip: {
                    borderWidth: 0,
                    backgroundColor: 'none',
                    shadow: false,
                    style: {
                        fontSize: '14px'
                    },
                    valueSuffix: '%',
                    pointFormat: '<span style="color:#fff;">{series.name}</span><br><span style="font-size:1.875rem; color: {point.color}; font-weight: bold">{point.y}</span>',
                    positioner: function (labelWidth) {
                        return {
                            x: (this.chart.chartWidth - labelWidth) / 2,
                            y: (this.chart.plotHeight / 2) - 30
                        };
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Total Supply
                        outerRadius: '100%',
                        innerRadius: '90%',
                        backgroundColor: "#2E2E33",
                        borderWidth: 0
                    },{ // Circulating Supply
                        outerRadius: '80%',
                        innerRadius: '70%',
                        backgroundColor: "#1F1F24",
                        borderWidth: 0
                    },{ // Stake
                        outerRadius: '60%',
                        innerRadius: '50%',
                        backgroundColor: "#1F1F24",
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },

                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            enabled: false
                        },
                        linecap: 'round',
                        stickyTracking: false,
                        rounded: true
                    }
                },
                series: [{
                  name: 'Max',
                  data: [{
                    color: "#1EADFC",
                    radius: '110%',
                    innerRadius: '100%',
                    y: 100
                  }]
                },{
                    name: 'Total',
                    data: [{
                        //color: "#BEFE0A",
                        color: "#6164ff",
                        radius: '90%',
                        innerRadius: '80%',
                        y: 100
                    }]
                },{
                    name: 'Circulating',
                    data: [{
                        color: "#FB08E3",
                        radius: '70%',
                        innerRadius: '60%',
                        y: 0
                    }]
                },{
                    name: 'Stake',
                    data: [{
                        color: "#4CF1D7",
                        radius: '50%',
                        innerRadius: '40%',
                        y: 10
                    }]
                }],
                credits: false
            }
        };
    },
    props: {
        coin_object: Object,
    },
    watch: {
        coin_object: [{
            handler: 'getCoin'
        }]
    },
    methods: {
        getCoin() {
            this.coin_obj = this.coin_object

            this.max_supply = this.coin_obj.max_supply ? numeral(this.coin_obj.max_supply).format("0,0.00") : this.infinity;
            if(this.max_supply === this.infinity) {
              this.chartOptions.series[0].data[0].color = "#2E2E33"
            } else {
              this.chartOptions.series[0].data[0].color = "#1EADFC"
            }
            this.chartOptions.series[1].data[0].y = Number(this.coin_obj.total_supply_pt)
            let circulating_supply_pt = Math.floor(this.coin_obj.circulating_supply_pt).toFixed(2)
            this.chartOptions.series[2].data[0].y = Number(circulating_supply_pt)
            let stake_pt = Math.floor(this.coin_obj.stake_pt).toFixed(2)
            this.chartOptions.series[3].data[0].y = Number(stake_pt)

            //setTimeout(() => this.chartOptions.series[2].data[0].y = this.coin_obj.circulating_supply_pt, 300);
            //setTimeout(() => this.chartOptions.series[3].data[0].y = this.coin_obj.stake_pt, 300);
            //console.log(Math.floor(this.coin_obj.stake_pt).toFixed(2));
            //this.chartOptions.series[1].data[0].y = this.coin_obj.total_supply_pt
            //this.chartOptions.series[2].data[0].y = Math.floor(this.coin_obj.circulating_supply_pt).toFixed(2)
            //setTimeout(() => this.chartOptions.series[3].data[0].y = stake_pt,100);
        },
    },
};
</script>
<style scoped>
.supply-metrics-chart {
    padding: 1rem 0.25em 0 0.25em;
    position: relative;
    background: #2E2E33;
    width: 360px;
    margin-top: 1em;
    margin-right: 1em;
    margin-bottom: 1em;
}
.supply-metrics-chart .more {
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
    position: absolute;
    z-index: 9;
    top: 20px;
    left: 15px;
    max-width: 70px;
}
.reports {
    margin-top: 1em;
    padding: 0 15px;
}
.reports ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.reports .vue-custom-tooltip {
    display: flex;
    flex-wrap: nowrap;
    margin-bottom: 10px;
    align-items: center;
}
.reports ul li .bullet {
    width: 10px;
    height: 8px;
    margin-right: 10px;
    border: 2px solid #1EADFC;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
}
.reports ul li .vue-custom-tooltip span:last-child {
    margin-left: auto;
}
.reports ul li.max span.bullet {
    border-color: #1EADFC;
}
.reports ul li.total span.bullet {
    border-color: #6164ff;
}
.reports ul li.circulating span.bullet {
    border-color: #FB09E3;
}
.reports ul li.stake span.bullet {
    border-color: #4CF1D7;
}
.reports ul li .title {
    width: 150px;
    font-size: 0.875rem;
}
.reports ul li .progress-bar {
    width: 100%;
    height: 4px;
    background: #17171B;
    border-radius: 89.7585px;
    position: relative;
}
.reports ul li .progress-bar .current {
    height: 5.44px;
    position: absolute;
    border: 0;
    border-radius: 89.7585px;
}
.reports .max .progress-bar .current {
    background: #1EADFC;
}
.reports .total .progress-bar .current {
    background: #6164ff;
}
.reports .circulating .progress-bar .current {
    background: #FB09E3;
}
.reports .stake .progress-bar .current {
    background: #4CF1D7;
}
</style>
