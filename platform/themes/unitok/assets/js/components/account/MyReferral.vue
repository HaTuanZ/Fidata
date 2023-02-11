<template>
  <div class="my-diamonds-container">
    <h3 class="font-bold text-2xl md:text-3xl lg:text-4xl mb-2">Invite Friends.Earn Crypto Together</h3>
    <p class="mb-4 text-gray-500">Use your unique link to invite your friends over message or email. Your default invitation code can also be shared in real life or as a screenshot.</p>
    <div class="card card-bordered p-0 mb-5">
      <div class="nk-refwg">
        <div class="nk-refwg-invite card-inner p-6">
          <div class="nk-refwg-head g-3">
            <div class="nk-refwg-title"><h5 class="title">Refer Us &amp; Earn</h5>
              <div class="title-sub">Use the bellow link to invite your friends.</div>
            </div>
            <div class="nk-refwg-action"><a href="javascript:;" onclick="modal(this)" class="btn btn-primary">Invite</a></div>
          </div>
          <div class="nk-refwg-url">
            <div class="form-control-wrap">
              <div class="form-clip clipboard-init" data-clipboard-target="#refUrl" data-success="Copied" data-text="Copy Link" v-on:click="copyInput($event)"><i class="fa-light fa-copy"></i> <span class="clipboard-text">Copy</span></div>
              <div class="form-icon"><i class="fa-light fa-link"></i></div>
              <input type="text" class="form-control copy-text" id="refUrl" :value="ref_url" v-on:focus="$event.target.select()"
                     ref="input_ref"
                     readonly>
            </div>
          </div>
        </div>
        <div class="nk-refwg-stats card-inner">
          <div class="my-diamonds">
            <div class="my-diamonds-box">
              <p class="text">Total Joined</p>
              <div class="number font-bold my-1 text-3xl">
                <ICountUp
                    :endVal="friends"
                />
              </div>
            </div>
            <div class="my-diamonds-bg">
              <img :src="svg_gift" style="width: 100%; max-width: 150px;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <ul class="nav nav-tabs main__tabs" id="main__tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-collect-diamonds" role="tab" aria-controls="tab-collect-diamonds" aria-selected="true">Your Referral</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-history" role="tab" aria-controls="tab-history" aria-selected="false">Your Commission History</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-collect-diamonds" role="tabpanel">
        <div class="mt-3">
          <h4 class="font-bold text-xl md:text-2xl lg:text-3xl mb-2">Your Referral Links List</h4>
          <table
              class="table bordered"
              v-if="users.length"
          >
            <thead>
            <tr>
              <th>Referral ID</th>
              <th>You Receive</th>
              <th>Level</th>
              <th>Friends</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(user, index) in users"
                :key="index"
            >
              <td>{{ user.user_id }}</td>
              <td>{{ user.level.bonus }}%</td>
              <td>{{ user.level.level_name }}</td>
              <td>{{ user.friends.length }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="tab-history" role="tabpanel">
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
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import ICountUp from 'vue-countup-v2'
import ClickOutside from 'vue-click-outside';

export default {
  name: 'Account-My-Referral',
  components: {
    Loading,
    ICountUp,
  },
  data() {
    return {
      windowWidth: window.innerWidth,
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
    this.getUsersData()
    this.getLogsData()
  },
  directives: {
    ClickOutside
  },
  methods: {
    getCurrentUser() {
      this.$store.commit('setLastLoading', !0)
      axios.get(window.siteUrl+"/account/get-current-user-referral").then(response => {
        this.user_info = response.data
        this.friends = Number(this.user_info.friends)
        this.ref_url = window.siteUrl+"/register?ref="+this.user_info.affiliate_id
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