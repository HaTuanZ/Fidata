export default {
    state: {
        isLoading: !1,
        isRedeem: !1,
        filterData: {},
        priceData: [],
        inflationData: [],
        menuItem: {},
        marketMenuItem: {},
        projectMenuItem: {},
    },
    mutations: {
        setLastLoading(state, isLoading) {
            state.isLoading = isLoading;
        },
        setLastRedeem(state, isRedeem) {
            state.isRedeem = isRedeem;
        },
        setDateRange: function (state, payload) {
            state.filterData.date_range = payload
        },
        setPriceData: function (state, payload) {
            state.priceData = payload
        },
        setInflationData: function (state, payload) {
            state.inflationData = payload
        },
        setMenuItem: function (state, payload) {
            state.menuItem = payload
        },
        setMarketMenuItem: function (state, payload) {
            state.marketMenuItem = payload
        },
        setProjectMenuItem: function (state, payload) {
            state.projectMenuItem = payload
        },
    },
};
