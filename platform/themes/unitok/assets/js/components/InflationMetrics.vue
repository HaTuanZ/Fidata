<template>
    <div class="pie-metrics-chart">
        <div class="chart-title">Actual Inflation <VueCustomTooltip label="This metric aims to analyze the actual inflation rate through the data: Token Release, Block Rewards, Burn, Debate" :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
        <highcharts class="hc" :options="chartOptions" ref="pie_metrics_chart"></highcharts>
        <div class="reports">
            <ul>
                <li class="token_release"><VueCustomTooltip :label="coin_object.token_release | formatNumber"><span class="bullet"></span><span class="title">Token release</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.token_release_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="block_rewards"><VueCustomTooltip :label="coin_object.block_rewards | formatNumber"><span class="bullet"></span><span class="title">Token issued</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.block_rewards_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="burn"><VueCustomTooltip :label="coin_object.burn | formatNumber"><span class="bullet"></span><span class="title">Burn</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.burn_pt}%`}"></span></span></VueCustomTooltip></li>
            </ul>
        </div>
    </div>
</template>

<script>
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';
export default {
    name: 'pie-metrics-chart',
    components: {
        VueCustomTooltip
    },
    data() {
        return {
            coin_obj: {},
            multiline: !0,
            chartOptions: {
                chart: {
                    type: 'pie',
                    backgroundColor: '#2E2E33',
                    spacingTop: 10,
                    spacingBottom: 0,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    height: 280,
                    events: {
                        load: function() {
                            let x = this.chartWidth / 2 - 178 / 2;
                            let y = this.chartHeight / 2 - 100 / 2;
                            this.renderer.image('https://media.coingen.net/logo-transparency0.png', x, y+24, 175, 100)
                                .attr({
                                    opacity: 0.88,
                                    zIndex: 9999
                                })
                                .add();
                        }
                    },
                },
                title: {
                    text: 'Current inflation',
                    align: 'center',
                    style: {
                        color: '#fff',
                        font: '500 18px "IBM Plex Sans", Verdana, sans-serif',
                        display: 'none'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        //allowPointSelect: true,
                        //cursor: 'pointer',
                        size: 250,
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: false,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total',
                    data: [{
                        name: 'Token release',
                        color: "#6164ff"
                    },{
                        name: 'Block rewards',
                        color: "#F6BD16F2"
                    },{
                        name: 'Burn',
                        color: "#D91E18"
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

            this.chartOptions.title.text = "Current inflation: "+this.coin_obj.inflation_pt+"%";

            console.log(this.coin_obj.token_release);

            this.chartOptions.series[0].data[0].y = this.coin_obj.token_release
            this.chartOptions.series[0].data[1].y = this.coin_obj.block_rewards
            this.chartOptions.series[0].data[2].y = this.coin_obj.burn
            
            //setTimeout(() => this.chartOptions.series[0].data[0].y = this.coin_obj.token_release_pt, 300);
        },
    },
};
</script>
<style scoped>
.pie-metrics-chart {
    padding: 1rem 0.25em;
    position: relative;
    background: #2E2E33;
    width: 360px;
    margin-right: 1em;
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
.reports ul li.token_release span.bullet {
    border-color: #6164ff;
}
.reports ul li.block_rewards span.bullet {
    border-color: #F6BD16F2;
}
.reports ul li.burn span.bullet {
    border-color: #D91E18;
}
.reports ul li.rebated span.bullet {
    border-color: #4CF1D7;
}
.reports ul li .title {
    width: 160px;
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
.reports .token_release .progress-bar .current {
    background: #6164ff;
}
.reports .block_rewards .progress-bar .current {
    background: #F6BD16F2;
}
.reports .burn .progress-bar .current {
    background: #D91E18;
}
.reports .rebated .progress-bar .current {
    background: #4CF1D7;
}
</style>
