<template>
  <div class="my-diamonds-container">
    <h3 class="font-bold text-2xl md:text-3xl lg:text-4xl mb-2">Balances</h3>
    <ul class="nav nav-tabs main__tabs" id="main__tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-balance" role="tab" aria-controls="tab-balance" aria-selected="true">Balances</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-history" role="tab" aria-controls="tab-history" aria-selected="false">History</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-balance" role="tabpanel">
        <div class="mt-3">
          <table
              class="table bordered"
              v-if="balances.length"
          >
            <thead>
            <tr>
              <th>Amount</th>
              <th>Currency symbol</th>
              <th>Updated at</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(row, index) in balances"
                :key="index"
            >
              <td>{{ row.balance }}</td>
              <td>{{ row.currency_symbol }}</td>
              <td>{{ row.updated_at | moment("MMM DD, YYYY HH:mm:ss") }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="tab-history" role="tabpanel">
        <div class="mt-3">
          <table
              class="table bordered"
              v-if="logs.length"
          >
            <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Exchange</th>
              <th>Note</th>
              <th>IP address</th>
              <th>Fees</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(log, index) in logs"
                :key="index"
            >
              <td>{{ log.created_at | moment("MMM DD, YYYY HH:mm:ss") }}</td>
              <td>{{ log.transaction_type }}</td>
              <td>{{ log.operator }}{{ log.transaction_amount }} {{ log.currency_symbol }}</td>
              <td>{{ log.note }}</td>
              <td>{{ log.ip_address }}</td>
              <td>${{ log.transaction_fees }}</td>
            </tr>
            </tbody>
          </table>
          <div class="pagination-container"><Pagination :changePage="getLogsData" :data="pagination_data"/></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  name: 'Account-Balance',
  components: {
    Loading,
  },
  data() {
    return {
      windowWidth: window.innerWidth,
      balances: [],
      logs: [],
      pagination_data: null,
    };
  },
  computed: {
    isDesktop: function () {
      return this.windowWidth >= 1025
    },
    currentRouteName() {
      return this.$route.name;
    },
  },
  mounted() {
    this.getBalanceData()
    this.getLogsData()
  },
  methods: {
    getBalanceData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/balances", { params: { page: page } }).then(response => {
        this.balances = response.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    getLogsData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.post(window.siteUrl+"/account/balance-logs", {
        page: page
      }).then(response => {
        this.pagination_data = response.data
        this.logs = this.pagination_data.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
  },
}
</script>