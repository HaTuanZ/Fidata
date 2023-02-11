<template>
  <div class="my-diamonds-container">
    <h3 class="font-bold text-2xl md:text-3xl lg:text-4xl mb-2">My Coingen GEM</h3>
    <p class="mb-4 text-gray-500">Collect Coingen GEM and redeem them for exclusive rewards and special offers.</p>
    <div class="my-diamonds">
      <div class="my-diamonds-box">
        <p class="text">My Gem</p>
        <div class="number font-bold my-1 text-3xl">
          <ICountUp :endVal="user_gems"/>
        </div>
      </div>
      <div class="my-diamonds-bg">
        <img :src="svg_gift" style="width: 100%; max-width: 150px;">
      </div>
    </div>
    <ul class="nav nav-tabs main__tabs" id="main__tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-collect-diamonds" role="tab" aria-controls="tab-collect-diamonds" aria-selected="true">Collect GEM</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-history" role="tab" aria-controls="tab-history" aria-selected="false">History</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-collect-diamonds" role="tabpanel">
        <div class="tab-collect-diamonds">
          <div class="tab-collect-diamonds-heading">
            <div class="tab-collect-diamonds-title">
              <h2 class="font-semibold text-xl md:text-2xl lg:text-3xl mb-2">Collect your GEM every day</h2>
              <p class="mb-4 text-gray-500">Log in 7 days in a row, your rewards will grow.</p>
            </div>
            <button type="button" class="button primary" style="display: none;" id="btn-collect-gem">Collect GEM</button>
            <a href="https://coingen.com/user/deposit" class="button primary">Get more GEMs</a>
          </div>
          <div class="tab-collect-diamonds-range">
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 0 }" v-on:click="gemCollect($event)">
              <p>Day 1</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_1">
              </p>
              <p>+ 10</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 1 }" v-on:click="gemCollect($event)">
              <p>Day 2</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_2">
              </p>
              <p>+ 20</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 2 }" v-on:click="gemCollect($event)">
              <p>Day 3</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_3">
              </p>
              <p>+ 30</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 3 }" v-on:click="gemCollect($event)">
              <p>Day 4</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_4">
              </p>
              <p>+ 40</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 4 }" v-on:click="gemCollect($event)">
              <p>Day 5</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_5">
              </p>
              <p>+ 50</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 5 }" v-on:click="gemCollect($event)">
              <p>Day 6</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_6">
              </p>
              <p>+ 60</p>
            </a>
            <a href="javascript:;" class="day" :class="{ next: user_info.diff > 6 }" v-on:click="gemCollect($event)">
              <p>Day 7</p>
              <p>
                <img :src="svg_diamond">
                <img :src="svg_check" v-if="user_info.collected_day_7">
              </p>
              <p>+ 110</p>
            </a>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="tab-history" role="tabpanel">
        <div style="margin-top: 30px">
          <table
              class="table bordered"
              v-if="logs.length"
          >
            <thead>
            <tr>
              <th>Activity</th>
              <th>Amount</th>
              <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(log, index) in logs"
                :key="index"
            >
              <td>{{ log.note }}</td>
              <td>{{ log.operator }}{{ log.balance }}</td>
              <td>{{ log.created_at | moment("MMM DD, YYYY HH:mm:ss") }}</td>
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
import ClickOutside from 'vue-click-outside'
import ICountUp from 'vue-countup-v2'
export default {
  name: 'Account-My-Gems',
  components: {
    ICountUp,
  },
  data() {
    return {
      windowWidth: window.innerWidth,
      user_info: {},
      user_gems: 0,
      svg_gift: window.themeUrl+'/img/giftbox-gift.svg',
      svg_diamond: window.themeUrl+'/img/diamond.svg',
      svg_check: window.themeUrl+'/img/check.svg',
      pagination_data: null,
      logs: [],
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
  created() {
    this.getCurrentUser()
  },
  mounted() {
    if(this.currentRouteName == "account-my-gems") {
      this.getLogsData()
    }
  },
  directives: {
    ClickOutside
  },
  methods: {
    getCurrentUser() {
      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/get-current-user").then(response => {
        this.user_info = response.data
        this.user_gems = Number(this.user_info.gems)
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    gemCollect(event) {
      this.$store.commit('setLastLoading', !0)
      axios.post("".concat(window.siteUrl, "/account/set-gem-collect"), {
        user_id: this.user_info.user_id
      }).then(response => {
        const result = response.data
        if(result.error == 1) {
          this.$Simplert.open({
            title: "Collect GEM",
            type: "error",
            message: result.msg
          });
        } else {
          this.$Simplert.open({
            title: "Collect GEM",
            type: "success",
            message: result.msg
          });
        }
        this.user_gems = Number(result.balance)
        this.$store.commit('setLastLoading', !1)
      });
    },
    getLogsData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/history", { params: { page: page } }).then(response => {
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