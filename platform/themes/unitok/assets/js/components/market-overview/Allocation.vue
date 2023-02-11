<template>
    <div class="mt-4 sm:mt-5 md:mt-6">
        <h2 class="sr-only">Allocation</h2>
        <div class="mb-6 flex w-full flex-col md:mb-8 lg:flex-row lg:space-y-0">
            <div class="min-w-0 lg:w-2/3 lg:grow">
                <div class="lg:w-1/1">
                    <section class="rounded-2xl p-4 dark:bg-zinc-800">
                        <h1 class="mb-4 text-xl font-bold">Token Allocation</h1>
                        <div class="flex flex-row">
                          <div class="space-y-4 sm:flex-row sm:space-y-0 lg:flex-col lg:space-y-4 lg:w-1/3 md:w-1/2 mr-20">
                              <div class="h-45 relative flex justify-center">
                                  <highcharts class="hc" :options="chartOptions" ref="token_allocation_chart"></highcharts>
                              </div>
                              <div class="mx-auto flex flex-col space-y-2">
                                  <div class="flex h-6 items-center justify-between gap-x-2 overflow-hidden rounded-md px-2 hover:bg-slate-200 dark:hover:bg-zinc-700">
                                      <span class="flex items-center space-x-2 overflow-hidden">
                                      <span class="inline-block h-3 w-3 shrink-0 rounded-full" style="background-color:#3b82f6"></span>
                                      <span class="truncate text-sm font-medium text-gray-400 dark:text-zinc-500">Initial supply</span>
                                      </span>
                                      <span class="whitespace-nowrap text-right text-sm font-medium text-gray-700 dark:text-zinc-300">{{ this.coin_object.initial_distribution.initial_supply }}</span>
                                  </div >
                                  <div class="flex h-6 items-center justify-between gap-x-2 overflow-hidden rounded-md px-2 hover:bg-slate-200 dark:hover:bg-zinc-700">
                                      <span class="flex items-center space-x-2 overflow-hidden">
                                      <span class="inline-block h-3 w-3 shrink-0 rounded-full" style="background-color:#6366f1"></span>
                                      <span class="truncate text-sm font-medium text-gray-400 dark:text-zinc-500">Investors</span>
                                      </span>
                                      <span class="whitespace-nowrap text-right text-sm font-medium text-gray-700 dark:text-zinc-300">{{ this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_investors_percentage }}%</span>
                                  </div>
                                  <div class="flex h-6 items-center justify-between gap-x-2 overflow-hidden rounded-md px-2 hover:bg-slate-200 dark:hover:bg-zinc-700">
                                      <span class="flex items-center space-x-2 overflow-hidden">
                                      <span class="inline-block h-3 w-3 shrink-0 rounded-full" style="background-color:#fb923c"></span>
                                      <span class="truncate text-sm font-medium text-gray-400 dark:text-zinc-500">Organization or founders</span>
                                      </span>
                                      <span class="whitespace-nowrap text-right text-sm font-medium text-gray-700 dark:text-zinc-300">{{ this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_organization_or_founders_percentage }}%</span>
                                  </div>
                                  <div class="flex h-6 items-center justify-between gap-x-2 overflow-hidden rounded-md px-2 hover:bg-slate-200 dark:hover:bg-zinc-700">
                                      <span class="flex items-center space-x-2 overflow-hidden">
                                      <span class="inline-block h-3 w-3 shrink-0 rounded-full" style="background-color:#ec4899"></span>
                                      <span class="truncate text-sm font-medium text-gray-400 dark:text-zinc-500">Premined rewards or airdrops_percentage</span>
                                      </span>
                                      <span class="whitespace-nowrap text-right text-sm font-medium text-gray-700 dark:text-zinc-300">{{ this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_premined_rewards_or_airdrops_percentage }}%</span>
                                  </div>
                              </div>
                          </div>
                          <div class="space-y-4 sm:space-y-0 lg:space-y-4 lg:w-1/2 md:w-1/2">
                            <div v-html="coin_object.allocation"></div>
                          </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'market-overview-allocation',
    data() {
        return {
            site_url: window.siteUrl,
            sales_rounds: [],
            chartOptions: {
                chart: {
                    type: 'pie',
                    backgroundColor: '#27272A',
                    spacingTop: 10,
                    spacingBottom: 0,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    width: 370,
                    height: 200,
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
                title: false,
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        //allowPointSelect: true,
                        //cursor: 'pointer',
                        size: 180,
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: false,
                        borderWidth: 0,
                        center: ['50%', '50%']
                    }
                },
                series: [{
                    name: 'Total',
                    data: [{
                        name: 'Allocated to investors',
                        color: "#6366f1",
                        y: 83.47
                    },{
                        name: 'Allocated to organization or founders',
                        color: "#fb923c",
                        y: 16.53
                    },{
                        name: 'Allocated to premined rewards or airdrops',
                        color: "#ec4899",
                        y: 0
                    }]
                }],
                exporting: false,
                credits: false
            },
            sales_round: {},
            raised: 0,
            last_sale_round: null,
            total_tokens_sold: 0
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
    filters: {
        abbr: function(num) {
            if (String(num).length < 7) {
                return (num/1000).toFixed(2).replace(/(\.0+|0+)$/, '') + 'K';
            } else {
                return (num/1000000).toFixed(2).replace(/(\.0+|0+)$/, '') + 'M';
            }
        }
    },
    mounted() {
        setTimeout(() => this.getCoin(), 1100);
        this.$nextTick(() => {
            //this.getCoin()
        })
    },
    methods: {
        getIconsUrl(filename) {
            return this.site_url+"/storage/icons/"+filename
        },
        getCoin() {
            this.sales_rounds = this.coin_object.fundraising.sales_rounds || []
            this.sales_round = this.sales_rounds[0] || null
            if(this.sales_rounds.length) {
                let sum = 0;
                this.sales_rounds.forEach(function (item){
                    sum += item.amount_collected_in_usd
                });
                this.raised = sum
            }
            this.last_sale_round = this.sales_rounds.slice(-1)[0]
            this.total_tokens_sold = this.coin_object.initial_distribution.initial_supply * this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_investors_percentage / 100;
            setTimeout(() => this.test(), 1100);
        },
        test() {
            this.chartOptions.series[0].data[0].y = Number(this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_investors_percentage) || 0
            this.chartOptions.series[0].data[1].y = Number(this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_organization_or_founders_percentage) || 0
            this.chartOptions.series[0].data[2].y = Number(this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_premined_rewards_or_airdrops_percentage) || 0
            //console.log(this.chartOptions.series[0].data)
        }
    },
}
</script>
