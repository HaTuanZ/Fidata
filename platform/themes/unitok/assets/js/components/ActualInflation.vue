<template>
    <div class="actual-inflation-chart">
        <div class="chart-title">Actual Inflation <VueCustomTooltip label="This metric aims to analyze the actual inflation rate through the data: Token Release, Block Rewards, Burn, Debate" :multiline="multiline"><i class="fad fa-question-circle"></i></VueCustomTooltip></div>
        <highcharts class="hc" :options="chartOptions" ref="actual_inflation_chart"></highcharts>
        <div class="reports" style="display: none;">
            <ul>
                <li class="token_release"><VueCustomTooltip :label="coin_object.token_release | formatNumber"><span class="bullet"></span><span class="title">Token release</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.token_release_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="block_rewards"><VueCustomTooltip :label="coin_object.block_rewards | formatNumber"><span class="bullet"></span><span class="title">Token issued</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.block_rewards_pt}%`}"></span></span></VueCustomTooltip></li>
                <li class="burn"><VueCustomTooltip :label="coin_object.burn | formatNumber"><span class="bullet"></span><span class="title">Burn</span> <span class="progress-bar"><span class="current":style="{ width: `${coin_object.burn_pt}%`}"></span></span></VueCustomTooltip></li>
            </ul>
        </div>
        <div class="reports2">
          <div class="report"><span class="title">Annual Inflation: </span><span class="value" :class="{ positive: coin_object.annual_inflation > 0, negative: coin_object.annual_inflation <= 0 }"> {{ coin_object.annual_inflation | formatNumber }}%</span></div>
          <div class="report" v-if="general_emission_type"><span class="title">General Emission Type: </span><span class="value"> {{ general_emission_type }}</span></div>
          <div class="report" v-if="coin_object.precise_emission_type"><span class="title">Precise Emission Type: </span><span class="value"> {{ coin_object.precise_emission_type || null }}</span></div>
        </div>
    </div>
</template>

<script>
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';
export default {
    name: 'actual-inflation-chart',
    components: {
        VueCustomTooltip
    },
    data() {
        return {
            coin_obj: {},
            multiline: !0,
            chartOptions: {
                chart: {
                    type: 'column',
                    backgroundColor: '#2E2E33',
                    spacingTop: 30,
                    //spacingBottom: 0,
                    //plotBackgroundColor: null,
                    //plotBorderWidth: null,
                    //plotShadow: false,
                    //height: 280,
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
                xAxis: {
                    title: {
                        text: null
                    },
                    categories: [
                        'Token release',
                        'Token issued',
                        'Burn',
                    ],
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
                            return s + '<br/>' + point.series.name + ': ' + shortNumber;
                        }, '<b>' + this.x + '</b>');
                    },
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        pointWidth: 48
                    }
                },
                series: [{
                    name: 'Total',
                    data: [{
                        name: 'Token release',
                        color: "#6164ff",
                    },{
                        name: 'Token issued',
                        color: "#FB09E3"
                    },{
                        name: 'Burn',
                        color: "#D91E18"
                    }]
                }],
                legend: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                credits: false
            }
        };
    },
    props: {
        coin_object: Object,
    },
    computed: {
      general_emission_type() {
        if(this.coin_object) {
          return this.coin_obj.general_emission_type || null
        }
        return null;
      },
      token_release() {
        return this.coin_object.token_release
      },
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
            //setTimeout(() => this.chartOptions.series[0].data[0].y = this.coin_obj.token_release, 3000)
            this.chartOptions.series[0].data[0].y = this.coin_obj.token_release
            this.chartOptions.series[0].data[1].y = this.coin_obj.block_rewards
            this.chartOptions.series[0].data[2].y = this.coin_obj.burn
        },
    },
};
</script>
<style scoped>
.actual-inflation-chart {
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
    border-color: #FB09E3;
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
    background: #FB09E3;
}
.reports .burn .progress-bar .current {
    background: #D91E18;
}
.reports .rebated .progress-bar .current {
    background: #4CF1D7;
}
.reports2 {
  padding-left: 10px;
  padding-right: 10px;
}
.reports2 .report {
  font-size: 14px;
}
.reports2 .report .title {
  font-weight: bold;
}
.positive {
  color: #41bd56;
}
.negative {
  color: #ff413c;
}
</style>
