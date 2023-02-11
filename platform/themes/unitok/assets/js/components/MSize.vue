<template>
    <div class="m-size-grid">
        <div class="m-size">
            <div class="heading">Price <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_126_2432)">
                    <path d="M9.33 11.5H11.5C12.6935 11.5 13.8381 11.9741 14.682 12.818C15.5259 13.6619 16 14.8065 16 16H8.999L9 17H17V16C16.9968 14.936 16.6894 13.895 16.114 13H19C19.9453 12.9997 20.8712 13.2674 21.6705 13.772C22.4698 14.2767 23.1097 14.9975 23.516 15.851C21.151 18.972 17.322 21 13 21C10.239 21 7.9 20.41 6 19.375V10.071C7.21661 10.2453 8.36547 10.7383 9.33 11.5ZM5 19C5 19.2652 4.89464 19.5196 4.70711 19.7071C4.51957 19.8946 4.26522 20 4 20H2C1.73478 20 1.48043 19.8946 1.29289 19.7071C1.10536 19.5196 1 19.2652 1 19V10C1 9.73478 1.10536 9.48043 1.29289 9.29289C1.48043 9.10536 1.73478 9 2 9H4C4.26522 9 4.51957 9.10536 4.70711 9.29289C4.89464 9.48043 5 9.73478 5 10V19ZM18 5C18.7956 5 19.5587 5.31607 20.1213 5.87868C20.6839 6.44129 21 7.20435 21 8C21 8.79565 20.6839 9.55871 20.1213 10.1213C19.5587 10.6839 18.7956 11 18 11C17.2044 11 16.4413 10.6839 15.8787 10.1213C15.3161 9.55871 15 8.79565 15 8C15 7.20435 15.3161 6.44129 15.8787 5.87868C16.4413 5.31607 17.2044 5 18 5ZM11 2C11.7956 2 12.5587 2.31607 13.1213 2.87868C13.6839 3.44129 14 4.20435 14 5C14 5.79565 13.6839 6.55871 13.1213 7.12132C12.5587 7.68393 11.7956 8 11 8C10.2044 8 9.44129 7.68393 8.87868 7.12132C8.31607 6.55871 8 5.79565 8 5C8 4.20435 8.31607 3.44129 8.87868 2.87868C9.44129 2.31607 10.2044 2 11 2Z" fill="white"/>
                </g>
                <defs>
                    <clipPath id="clip0_126_2432">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg></div>
            <div class="price">
                <span class="value">${{ coin_object.price }}</span>
                <span class="positive" :class="{positive: coin_object.price_change_percentage_24h >= 0, negative: coin_object.price_change_percentage_24h < 0}"><i class="fas"></i> {{ coin_object.price_change_percentage_24h | formatNumber }}%</span>
            </div>
            <div class="price-slide">
                <div class="low">
                    <span class="name">Low:</span>
                    <span class="value">{{ (date_range_object.value == 1 ? coin_object.low_24h : coin_object.atl) | formatNumber }}</span>
                </div>
                <VueCustomTooltip :label="ath_change" class="slider">
                    <span class="progress-bar">
                        <span :style="{ 'width': pt + '%' }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24px" width="24px" viewBox="0 0 24 24" class="sc-16r8icm-0 jZwKai sc-1s4r656-1 hMoBok"><path d="M18.0566 16H5.94336C5.10459 16 4.68455 14.9782 5.27763 14.3806L11.3343 8.27783C11.7019 7.90739 12.2981 7.90739 12.6657 8.27783L18.7223 14.3806C19.3155 14.9782 18.8954 16 18.0566 16Z"></path></svg>
                        </span>
                    </span>
                </VueCustomTooltip>
                <div class="high">
                    <span class="name">High:</span>
                    <span class="value">{{ (date_range_object.value == 1 ? coin_object.high_24h : coin_object.ath) | formatNumber }}</span>
                </div>
                <div class="filter">
                    <div class="dropdown-select" v-click-outside="hideDropdown" @click="toggleDropdown">
                        <div class="select">
                            <div class="my-select">{{ date_range_object.shortname }}</div>
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
            </div>
        </div>
        <div class="m-size">
            <div class="heading">Markets <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_126_2432)">
                    <path d="M9.33 11.5H11.5C12.6935 11.5 13.8381 11.9741 14.682 12.818C15.5259 13.6619 16 14.8065 16 16H8.999L9 17H17V16C16.9968 14.936 16.6894 13.895 16.114 13H19C19.9453 12.9997 20.8712 13.2674 21.6705 13.772C22.4698 14.2767 23.1097 14.9975 23.516 15.851C21.151 18.972 17.322 21 13 21C10.239 21 7.9 20.41 6 19.375V10.071C7.21661 10.2453 8.36547 10.7383 9.33 11.5ZM5 19C5 19.2652 4.89464 19.5196 4.70711 19.7071C4.51957 19.8946 4.26522 20 4 20H2C1.73478 20 1.48043 19.8946 1.29289 19.7071C1.10536 19.5196 1 19.2652 1 19V10C1 9.73478 1.10536 9.48043 1.29289 9.29289C1.48043 9.10536 1.73478 9 2 9H4C4.26522 9 4.51957 9.10536 4.70711 9.29289C4.89464 9.48043 5 9.73478 5 10V19ZM18 5C18.7956 5 19.5587 5.31607 20.1213 5.87868C20.6839 6.44129 21 7.20435 21 8C21 8.79565 20.6839 9.55871 20.1213 10.1213C19.5587 10.6839 18.7956 11 18 11C17.2044 11 16.4413 10.6839 15.8787 10.1213C15.3161 9.55871 15 8.79565 15 8C15 7.20435 15.3161 6.44129 15.8787 5.87868C16.4413 5.31607 17.2044 5 18 5ZM11 2C11.7956 2 12.5587 2.31607 13.1213 2.87868C13.6839 3.44129 14 4.20435 14 5C14 5.79565 13.6839 6.55871 13.1213 7.12132C12.5587 7.68393 11.7956 8 11 8C10.2044 8 9.44129 7.68393 8.87868 7.12132C8.31607 6.55871 8 5.79565 8 5C8 4.20435 8.31607 3.44129 8.87868 2.87868C9.44129 2.31607 10.2044 2 11 2Z" fill="white"/>
                </g>
                <defs>
                    <clipPath id="clip0_126_2432">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg></div>
            <div class="price">
                <span class="value">{{ coin_object.markets }}</span>
                <span class="positive" :class="{positive: coin_object.markets_change_24h_pt >= 0, negative: coin_object.markets_change_24h_pt < 0}"><i class="fas"></i> {{ coin_object.markets_change_24h_pt | formatNumber }}%</span>
            </div>
            <p>Compared last day</p>
        </div>
        <div class="m-size">
            <div class="heading">Exchanges <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_126_2432)">
                    <path d="M9.33 11.5H11.5C12.6935 11.5 13.8381 11.9741 14.682 12.818C15.5259 13.6619 16 14.8065 16 16H8.999L9 17H17V16C16.9968 14.936 16.6894 13.895 16.114 13H19C19.9453 12.9997 20.8712 13.2674 21.6705 13.772C22.4698 14.2767 23.1097 14.9975 23.516 15.851C21.151 18.972 17.322 21 13 21C10.239 21 7.9 20.41 6 19.375V10.071C7.21661 10.2453 8.36547 10.7383 9.33 11.5ZM5 19C5 19.2652 4.89464 19.5196 4.70711 19.7071C4.51957 19.8946 4.26522 20 4 20H2C1.73478 20 1.48043 19.8946 1.29289 19.7071C1.10536 19.5196 1 19.2652 1 19V10C1 9.73478 1.10536 9.48043 1.29289 9.29289C1.48043 9.10536 1.73478 9 2 9H4C4.26522 9 4.51957 9.10536 4.70711 9.29289C4.89464 9.48043 5 9.73478 5 10V19ZM18 5C18.7956 5 19.5587 5.31607 20.1213 5.87868C20.6839 6.44129 21 7.20435 21 8C21 8.79565 20.6839 9.55871 20.1213 10.1213C19.5587 10.6839 18.7956 11 18 11C17.2044 11 16.4413 10.6839 15.8787 10.1213C15.3161 9.55871 15 8.79565 15 8C15 7.20435 15.3161 6.44129 15.8787 5.87868C16.4413 5.31607 17.2044 5 18 5ZM11 2C11.7956 2 12.5587 2.31607 13.1213 2.87868C13.6839 3.44129 14 4.20435 14 5C14 5.79565 13.6839 6.55871 13.1213 7.12132C12.5587 7.68393 11.7956 8 11 8C10.2044 8 9.44129 7.68393 8.87868 7.12132C8.31607 6.55871 8 5.79565 8 5C8 4.20435 8.31607 3.44129 8.87868 2.87868C9.44129 2.31607 10.2044 2 11 2Z" fill="white"/>
                </g>
                <defs>
                    <clipPath id="clip0_126_2432">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg></div>
            <div class="price">
                <span class="value">{{ coin_object.exchanges }}</span>
                <span class="positive" :class="{positive: coin_object.exchanges_change_24h_pt >= 0, negative: coin_object.exchanges_change_24h_pt < 0}"><i class="fas"></i> {{ coin_object.exchanges_change_24h_pt | formatNumber }}%</span>
            </div>
            <p>Compared last day</p>
        </div>
        <div class="m-size">
            <div class="heading">Pairs <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_126_2432)">
                    <path d="M9.33 11.5H11.5C12.6935 11.5 13.8381 11.9741 14.682 12.818C15.5259 13.6619 16 14.8065 16 16H8.999L9 17H17V16C16.9968 14.936 16.6894 13.895 16.114 13H19C19.9453 12.9997 20.8712 13.2674 21.6705 13.772C22.4698 14.2767 23.1097 14.9975 23.516 15.851C21.151 18.972 17.322 21 13 21C10.239 21 7.9 20.41 6 19.375V10.071C7.21661 10.2453 8.36547 10.7383 9.33 11.5ZM5 19C5 19.2652 4.89464 19.5196 4.70711 19.7071C4.51957 19.8946 4.26522 20 4 20H2C1.73478 20 1.48043 19.8946 1.29289 19.7071C1.10536 19.5196 1 19.2652 1 19V10C1 9.73478 1.10536 9.48043 1.29289 9.29289C1.48043 9.10536 1.73478 9 2 9H4C4.26522 9 4.51957 9.10536 4.70711 9.29289C4.89464 9.48043 5 9.73478 5 10V19ZM18 5C18.7956 5 19.5587 5.31607 20.1213 5.87868C20.6839 6.44129 21 7.20435 21 8C21 8.79565 20.6839 9.55871 20.1213 10.1213C19.5587 10.6839 18.7956 11 18 11C17.2044 11 16.4413 10.6839 15.8787 10.1213C15.3161 9.55871 15 8.79565 15 8C15 7.20435 15.3161 6.44129 15.8787 5.87868C16.4413 5.31607 17.2044 5 18 5ZM11 2C11.7956 2 12.5587 2.31607 13.1213 2.87868C13.6839 3.44129 14 4.20435 14 5C14 5.79565 13.6839 6.55871 13.1213 7.12132C12.5587 7.68393 11.7956 8 11 8C10.2044 8 9.44129 7.68393 8.87868 7.12132C8.31607 6.55871 8 5.79565 8 5C8 4.20435 8.31607 3.44129 8.87868 2.87868C9.44129 2.31607 10.2044 2 11 2Z" fill="white"/>
                </g>
                <defs>
                    <clipPath id="clip0_126_2432">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg></div>
            <div class="price">
                <span class="value">{{ coin_object.pairs }}</span>
                <span class="positive" :class="{positive: coin_object.pairs_change_24h_pt >= 0, negative: coin_object.pairs_change_24h_pt < 0}"><i class="fas"></i> {{ coin_object.pairs_change_24h_pt | formatNumber }}%</span>
            </div>
            <p>Compared last day</p>
        </div>
    </div>
</template>
<script>
import ClickOutside from 'vue-click-outside';
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';

export default {
    name: 'm-size-grid',
    components: {
        VueCustomTooltip
    },
    data() {
        return {
            coin_obj: {},
            low: 0,
            high: 0,
            ath_change: "",
            date_range_object: {},
            opened: !1,
            pt: 0,
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
    created() {
        this.filterArr = [{ value: '1', name: '24h', shortname: '24h'}, { value: '365', name: 'ATL/ATH', shortname: 'All'}];
    },
    mounted() {
        this.date_range_object = this.filterArr[0]
        this.$nextTick(() => {
            //this.getData()
        })
    },
    directives: {
        ClickOutside
    },
    methods: {
        toggleDropdown() {
            this.active = !this.active
            this.opened = !this.opened
        },
        hideDropdown() {
            this.opened = false
        },
        selectDateRange(d) {
            this.$store.commit('setLastLoading', !0)
            this.date_range_object = d
            setTimeout(() => this.$store.commit('setLastLoading', !1), 300);
        },
        getCoin: function () {
            this.coin_obj = this.coin_object
            let date = this.coin_obj.ath_date;
            this.ath_change = this.coin_obj.ath_change_percentage.toFixed(2)+"% at " + this.moment(date).format('LL');

            let low = this.date_range_object.value == 1 ? this.coin_obj.low_24h : this.coin_obj.atl;
            let high = this.date_range_object.value == 1 ? this.coin_obj.high_24h : this.coin_obj.ath;
            this.pt = this.coin_obj.price * 100 / (low + high);
        },
        moment: function (date) {
            return this.$moment(date);
        },
    },
};
</script>
<style scoped>
.m-size-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-column-gap: 20px;
    grid-row-gap: 20px;
    width: 100%;
}
.m-size {
    background: #6164ff;
    color: #fff;
    border-radius: 5px;
    padding: 1rem 1.5rem;
}
.m-size .heading {
    font-size: 1rem;
    margin-bottom: 10px;
}
.m-size .price {
    display: flex;
    align-items: flex-end;
    margin-bottom: 10px;
}
.m-size .value {
    font-family: 'Open Sans';
    font-size: 2.125em;
    line-height: 1;
    font-weight: 800;
}
.m-size .positive,
.m-size .negative {

}
.m-size .positive {
    color: #5EFF5A;
}
.m-size .negative {
    color: #ff413c;
}
.m-size .positive i:before {
    content: "\f0de";
}
.m-size .negative i:before {
    content: "\f0dd";
}
.m-size span+span {
    margin-left: 10px;
}
.m-size p {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}
.price-slide {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: start;
    justify-content: flex-start;
}
.price-slide .low {
    margin-right: 16px;
    font-size: 12px;
}
.price-slide .slider {
    width: 100%;
    max-width: 145px;
}
.price-slide .slider .progress-bar {
    width: 100%;
    height: 6px;
    display: inline-block;
    position: relative;
    border-radius: 4px;
    background: #eff2f5;
}
.price-slide .slider .progress-bar > span:first-child {
    background-color: #2E2E33;
    height: 100%;
    position: absolute;
    left: 0px;
    top: 0px;
    border-top-left-radius: inherit;
    border-bottom-left-radius: inherit;
    color: #2E2E33;
    border-top-right-radius: 0px;
    border-bottom-right-radius: 0px;
    transition: width 3s ease-in-out 0s;
}
.price-slide .slider .progress-bar svg {
    position: absolute;
    top: -1px;
    right: -8px;
    width: 16px;
    color: #2E2E33;
}
.price-slide .high {
    margin-left: 16px;
    font-size: 12px;
}
.price-slide .value {
    font-weight: 600;
    font-size: 12px;
    margin: 0;
}
.price-slide .filter {
    z-index: 9;
    margin: 0 0 0 10px;
    padding: 0;
    font-size: .0875rem;
}
.price-slide .filter .dropdown {
    width: 120px;
}
.price-slide .filter .dropdown-select .select {
    padding: 1px 5px;
}
.price-slide .filter .dropdown-select .dropdown li {
    font-size: 13px;
}
.price-slide .filter button {
    padding: 0.1rem 1rem;
    border: 1px solid #fff;
    color: #fff;
    margin-right: 5px;
    font-size: 12px;
}
.price-slide .filter button:hover,
.price-slide .filter button.active {
    background: #fff;
    color: #222227;
}
</style>
