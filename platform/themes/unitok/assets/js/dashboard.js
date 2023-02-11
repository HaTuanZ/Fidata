import sanitizeHTML from 'sanitize-html';
import Vue from 'vue';
import Vuex from 'vuex';
import VuexStore from "./store";
import VueRouter from "vue-router";

var numeral = require("numeral");
Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,0.00");
});
import VueCustomTooltip from '@adamdehaven/vue-custom-tooltip';

import { Simplert } from 'vue2-simplert-plugin'
import 'vue2-simplert-plugin/dist/vue2-simplert-plugin.min.css'
import HighchartsVue from "highcharts-vue";
import router from "./routes";
import ReadMore from 'vue-read-more';
import ECharts from 'vue-echarts'
import { use } from 'echarts/core'
import Pagination from 'vue-laravel-paginex'

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('dashboard', require('./components/Dashboard.vue').default);

Vue.prototype.__ = key => {
    return window.trans[key] !== 'undefined' ? window.trans[key] : key;
};

Vue.prototype.themeUrl = url => {
    return window.themeUrl + '/' + url;
}

Vue.prototype.$sanitize = sanitizeHTML;

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(HighchartsVue);
Vue.use(VueCustomTooltip);
Vue.use(Simplert);
Vue.use(require('vue-moment'));
Vue.use(ReadMore);
Vue.use(ECharts)
Vue.component('Pagination', Pagination)

const store = new Vuex.Store(VuexStore);

const app = new Vue({
    el: '#app',
    router,
    store
});