<template>
    <div class="mt-4 sm:mt-5 md:mt-6">
        <h2 class="sr-only">Fundraising</h2>
        <div class="mb-6 flex w-full flex-col md:mb-8 lg:flex-row lg:space-y-0">
            <div class="min-w-0 lg:w-2/3 lg:grow">
                <div class="rounded-2xl p-4 bg-dark text-white mb-4" v-if="coin_object.launch_style">
                    <div class="flex w-full items-center justify-between">
                        <div class="mr-2 flex min-w-0 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-1 h-5 w-5 shrink-0 text-blue-400">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="min-w-0 font-semibold">{{ coin_object.launch_style }}</span>
                        </div>
                    </div>
                    <div v-html="coin_object.launch_details" class="mt-2 text-sm leading-6"></div>
                </div>
                <div class="mb-6 flex flex-col space-y-4 md:mb-8 md:space-y-6">
                    <div
                        class="group relative rounded-2xl shadow-md hover:bg-gray-900 active:bg-gray-200 dark:bg-zinc-800 dark:shadow-black dark:hover:bg-zinc-700 dark:active:bg-zinc-600"
                        v-if="sales_rounds.length"
                        v-for="(sales_round, index) in sales_rounds"
                        :key="index"
                    >
                        <button class="w-full" type="button">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="hidden shrink-0 sm:block">
                                            <span class="inline-block bg-contain bg-center bg-no-repeat h-8 w-8 rounded-full bg-gray-400 dark:bg-zinc-500" style="mask-repeat: no-repeat; mask-position: 50% 50%;" :style="{ 'mask-image': 'url('+getIconsUrl('seed-round-logo.svg')+')' }"></span>
                                        </div>
                                        <div>
                                            <div class="mb-2 flex items-center space-x-2">
                                                <h1 class="text-left text-xl font-bold">{{ sales_round.title }}</h1>
                                            </div>
                                            <div class="mr-18 flex flex-wrap gap-x-4 gap-y-2">
                                                <span class="flex items-center space-x-1 text-sm font-medium">
                                                    <span class="text-gray-400 dark:text-zinc-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" class="h-5 w-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </span>
                                                    <span class="text-gray-600 dark:text-zinc-300">{{ sales_round.date | moment("MMM DD, YYYY") }}</span>
                                                </span>
                                                <span class="flex items-center space-x-1 text-sm font-medium">
                                                    <span class="text-gray-400 dark:text-zinc-500">Price</span>
                                                    <span class="text-gray-600 dark:text-zinc-300">{{ sales_round.price || null }}</span>
                                                </span>
                                                <span class="flex items-center space-x-1 text-sm font-medium">
                                                    <span class="text-gray-400 dark:text-zinc-500">Raised</span>
                                                    <span class="text-gray-600 dark:text-zinc-300">{{ sales_round.raised | abbr }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" class="h-5 w-5 shrink-0 text-gray-400 transition-transform will-change-transform dark:text-zinc-500 ">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="space-y-4 md:space-y-6 lg:ml-5 lg:flex lg:w-1/2 md:w-1/1 lg:min-w-0 lg:max-w-xs lg:flex-col lg:self-start xl:ml-6">
                <section class="rounded-2xl bg-zinc-800 p-4 dark:bg-zinc-800">
                    <header class="mb-4">
                        <a href="#" target="_blank" rel="noopener noreferrer nofollow" class="group">
                            <h1 class="flex items-center space-x-2">
                                <span class="inline-block bg-contain bg-center bg-no-repeat h-6 w-24"></span>
                                <span class="text-xl font-bold">Rating</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" class="h-4 w-4 text-gray-400 group-hover:text-gray-500 group-active:text-gray-600 dark:text-zinc-500 dark:group-hover:text-zinc-700 dark:group-active:text-zinc-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </h1>
                        </a>
                    </header>
                    <dl>
                        <div class="my-3 flex items-center justify-between font-medium first:mt-0 last:mb-0">
                            <dt class="flex items-center space-x-1.5 text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">
                                <span>Launch style</span>
                                <span class="styles_info__M6UC0" tabindex="0" aria-hidden="true"></span>
                            </dt>
                            <dd class="text-base text-white-800 dark:text-white">{{ coin_object.launch_style}}</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between font-medium first:mt-0 last:mb-0">
                            <dt class="flex items-center space-x-1.5 text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">
                                <span>Sector</span>
                                <span class="styles_info__M6UC0" tabindex="0" aria-hidden="true"></span>
                            </dt>
                            <dd class="text-base text-white-800 dark:text-white">{{ coin_object.sector }}</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between font-medium first:mt-0 last:mb-0">
                            <dt class="flex items-center space-x-1.5 text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">
                                <span>Consensus Algorithm</span>
                                <span class="styles_info__M6UC0" tabindex="0" aria-hidden="true"></span>
                            </dt>
                            <dd class="text-base text-white-800 dark:text-white">{{ coin_object.consensus.general_consensus_mechanism }}</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between font-medium first:mt-0 last:mb-0">
                            <dt class="flex items-center space-x-1.5 text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">
                                <span>ROI Rate</span>
                                <span class="styles_info__M6UC0" tabindex="0" aria-hidden="true"></span>
                            </dt>
                            <dd class="text-base text-white-800 dark:text-white">Not rated</dd>
                        </div>
                    </dl>
                </section>
                <section class="rounded-2xl bg-zinc-800 p-4 dark:bg-zinc-800">
                    <h1 class="mb-4 text-xl font-bold">Total Statistics</h1>
                    <dl>
                        <div class="my-3 flex items-center justify-between space-x-2 font-medium first:mt-0 last:mb-0">
                            <dt class="whitespace-nowrap text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">Total Tokens Sold</dt>
                            <dd class="truncate text-base text-white-800 dark:text-white">{{ total_tokens_sold | abbr }} {{ this.coin_object.symbol.toUpperCase() }}</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between space-x-2 font-medium first:mt-0 last:mb-0">
                            <dt class="whitespace-nowrap text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">Total Sale %</dt>
                            <dd class="truncate text-base text-white-800 dark:text-white">{{ this.coin_object.initial_distribution.initial_supply_repartition.allocated_to_investors_percentage }}%</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between space-x-2 font-medium first:mt-0 last:mb-0">
                            <dt class="whitespace-nowrap text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">Raised</dt>
                            <dd class="truncate text-base text-white-800 dark:text-white">{{ raised | abbr }}</dd>
                        </div>
                        <hr class="h-px border-0 bg-gray-200 dark:bg-zinc-700">
                        <div class="my-3 flex items-center justify-between space-x-2 font-medium first:mt-0 last:mb-0">
                            <dt class="whitespace-nowrap text-sm text-gray-400 dark:text-zinc-500" aria-describedby="ico-drops-score">Price</dt>
                            <dd class="truncate text-base text-white-800 dark:text-white">{{ last_sale_round.price || null }}</dd>
                        </div>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'market-overview-fundraising',
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
            this.sales_rounds = this.coin_object.fund_raising || []
            this.sales_round = this.sales_rounds[0] || null
            if(this.sales_rounds.length) {
                let sum = 0;
                this.sales_rounds.forEach(function (item){
                    sum += item.raised
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
