<template>
  <div class="my-diamonds-container">
    <h3 class="font-bold text-2xl md:text-3xl lg:text-4xl mb-2">GEM Rewards</h3>
    <ul class="nav nav-tabs main__tabs" id="main__tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-browse-rewards" role="tab" aria-controls="tab-browse-rewards" aria-selected="true">Browse Rewards</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-my-rewards" role="tab" aria-controls="tab-my-rewards" aria-selected="false">My Rewards</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-browse-rewards" role="tabpanel">
        <div class="mt-3">
          <h4 class="font-semibold text-xl lg:text-2xl mb-2">Get started with Coingen Gems.</h4>
          <div class="packages-list" id="packages-list">
            <div
                class="package-item"
                v-for="(pkg, index) in packages"
                :key="pkg.id"
                :id="pkg.id"
            >
              <h1 class="package-name">{{ pkg.name }}</h1>
              <div class="package-image">
                <img :src="pkg.image" />
              </div>
              <div class="package-price">
                <i class="fa-regular fa-dollar-sign text-3xl"></i>
                <span>
                  <ICountUp
                    :endVal="pkg.price"
                   />
                </span>
              </div>
              <div class="package-access">
                <div v-if="pkg.access_length === 'specific'">
                  per {{ pkg.access_length_amount }} {{ pkg.access_length_period }}
                </div>
              </div>
              <div class="package-purchase">
                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" :data-package_id="pkg.id" v-on:click="popupRedeem($event)">Redeem now</button>
              </div>
              <div class="package-description" v-if="pkg.description" v-html="nl2br(pkg.description)">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="tab-my-rewards" role="tabpanel">
        <div class="mt-3">
          <h4 class="font-bold text-xl md:text-2xl lg:text-3xl mb-2">Your Commission History</h4>
          <p class="mb-4 text-gray-500">Latest Commission History (One week of commission history displayed by default)</p>
          <table
              class="table bordered"
              v-if="logs.length"
          >
            <thead>
            <tr>
              <th>Date</th>
              <th>Product</th>
              <th>Exchange</th>
              <th>UID Buyer</th>
              <th>Invested Fee</th>
              <th>Commissions Earned</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(log, index) in logs"
                :key="index"
            >
              <td>{{ log.created_at | moment("MMM DD, YYYY HH:mm:ss") }}</td>
              <td>{{ log.product }}</td>
              <td>{{ log.note }}</td>
              <td>{{ log.purchaser_id }}</td>
              <td>${{ log.fees }}</td>
              <td>${{ log.amount }}</td>
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
import ICountUp from 'vue-countup-v2'
import ClickOutside from 'vue-click-outside'

export default {
  name: 'Account-Gem-Rewards',
  components: {
    ICountUp
  },
  data() {
    return {
      windowWidth: window.innerWidth,
      packages: [],
      package: {},

      svg_gift: window.themeUrl+'/img/giftbox-gift.svg',
      user_info: {},
      friends: 0,
      pagination_data: null,
      users: [],
      logs: [],
      ref_url: null,
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
    this.getPackages()
  },
  directives: {
    ClickOutside
  },
  methods: {
    getPackages() {
      this.$store.commit('setLastLoading', !0)
      axios.get(window.apiUrl+"/packages").then(response => {
        this.packages = response.data.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    nl2br(str, is_xhtml) {
      if (typeof str === 'undefined' || str === null) {
        return '';
      }
      var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    },
    popupRedeem(event) {
      let package_id = event.target.getAttribute("data-package_id")
      this.package = this.packages.filter(function (pkg) {
        return pkg.id == package_id
      })[0];
      this.$store.commit('setLastRedeem', !0)
      /*this.$Simplert.open({
        type: "info",
        message: this.package.content
      });*/
      this.$emit('setPackageRedeem', this.package)
      console.log(this.package)
    },
    getCurrentUser() {
      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/get-current-user").then(response => {
        this.user_info = response.data
        this.$store.commit('setLastLoading', !1)
        this.$emit('setCurrentUser', this.user_info)
      }).catch(e => {
        console.log(e)
      });
    },



    getUsersData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/users", { params: { page: page } }).then(response => {
        this.users = response.data.users
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    getLogsData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.post(window.siteUrl+"/account/commission-history", {
        page: page
      }).then(response => {
        this.pagination_data = response.data
        this.logs = this.pagination_data.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    copyInput(event) {
      try {
        this.$refs.input_ref.focus();
        document.execCommand('copy');
        this.$Simplert.open({
          type: "success",
          message: 'Copied'
        });
      } catch (err) {
        this.$Simplert.open({
          type: "error",
          message: "Oops, unable to copy"
        });
      }
    },
  },
}
</script>