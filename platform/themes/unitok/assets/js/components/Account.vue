<template>
  <div class="account-my-gems">
    <div class="container">
      <div class="row mt-3">
        <div class="col-lg-2 tw-hidden d-lg-block">
          <div class="text-2xl font-weight-bold">Account Details</div>
        </div>
        <div class="col-lg-2">
          <div class="breadcrumb text-muted">
          </div>
        </div>
      </div>
      <div class="row row--grid">
        <div class="col-12 col-xl-2">
          <div class="account-links">
            <ul>
              <li class="mt-4"><a v-on:click="setPage('account-activity-referral')" :class="{ active: ( currentRouteName == 'account-activity' && $route.params.slug == 'referral' ) }"><span><svg width="28" height="28" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.3333 1.77778H12.4444V0.666667C12.4444 0.297222 12.1472 0 11.7778 0H4.22222C3.85278 0 3.55556 0.297222 3.55556 0.666667V1.77778H0.666667C0.297222 1.77778 0 2.075 0 2.44444V4C0 4.99167 0.625 6.01111 1.71944 6.79722C2.59444 7.42778 3.65833 7.82778 4.775 7.95556C5.64722 9.40278 6.66667 10 6.66667 10V12H5.33333C4.35278 12 3.55556 12.575 3.55556 13.5556V13.8889C3.55556 14.0722 3.70556 14.2222 3.88889 14.2222H12.1111C12.2944 14.2222 12.4444 14.0722 12.4444 13.8889V13.5556C12.4444 12.575 11.6472 12 10.6667 12H9.33333V10C9.33333 10 10.3528 9.40278 11.225 7.95556C12.3444 7.82778 13.4083 7.42778 14.2806 6.79722C15.3722 6.01111 16 4.99167 16 4V2.44444C16 2.075 15.7028 1.77778 15.3333 1.77778ZM2.75833 5.35556C2.08056 4.86667 1.77778 4.32222 1.77778 4V3.55556H3.56111C3.58889 4.46111 3.72222 5.25556 3.91667 5.95C3.49722 5.80556 3.10556 5.60556 2.75833 5.35556ZM14.2222 4C14.2222 4.44722 13.7306 5.00278 13.2417 5.35556C12.8944 5.60556 12.5 5.80556 12.0806 5.95C12.275 5.25556 12.4083 4.46111 12.4361 3.55556H14.2222V4Z" fill="currentColor"></path></svg>My Referral</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-my-gems')" :class="{ active: currentRouteName == 'account-my-gems' }"><span><svg width="28" height="28" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5718 2.33333L14.6667 5.875H12.3264L10.7246 2.33333H12.5718ZM9.60884 2.33333L11.2107 5.875H4.78939L6.39124 2.33333H9.60884ZM3.42828 2.33333H5.2755L3.67365 5.875H1.33337L3.42828 2.33333ZM1.33337 6.58333H3.66439L6.51161 12.1548C6.54634 12.2234 6.44911 12.2854 6.39587 12.2279L1.33337 6.58333ZM4.76393 6.58333H11.2362L8.06486 13.6268C8.04171 13.6799 7.96069 13.6799 7.93754 13.6268L4.76393 6.58333ZM9.48847 12.1548L12.3357 6.58333H14.6667L9.60421 12.2256C9.55097 12.2854 9.45374 12.2234 9.48847 12.1548Z" fill="currentColor"></path></svg>My GEMs</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-gem-rewards')" :class="{ active: currentRouteName == 'account-gem-rewards' }"><span><svg width="14" height="13" viewBox="0 0 14 13" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.99998 2.49961C2.99998 1.17413 4.07449 0.0996094 5.39998 0.0996094C6.01466 0.0996094 6.57537 0.330693 6.99998 0.710726C7.42458 0.330693 7.98529 0.0996094 8.59998 0.0996094C9.92546 0.0996094 11 1.17413 11 2.49961C11 2.78012 10.9519 3.04939 10.8634 3.29961H11.8C12.6836 3.29961 13.4 4.01595 13.4 4.89961C13.4 5.78326 12.6836 6.49961 11.8 6.49961H7.79998V5.69961C7.79998 5.25778 7.4418 4.89961 6.99998 4.89961C6.55815 4.89961 6.19998 5.25778 6.19998 5.69961V6.49961H2.19998C1.31632 6.49961 0.599976 5.78326 0.599976 4.89961C0.599976 4.01595 1.31632 3.29961 2.19998 3.29961H3.13654C3.0481 3.04939 2.99998 2.78012 2.99998 2.49961ZM6.19998 3.29961V2.49961C6.19998 2.05778 5.8418 1.69961 5.39998 1.69961C4.95815 1.69961 4.59998 2.05778 4.59998 2.49961C4.59998 2.94144 4.95815 3.29961 5.39998 3.29961H6.19998ZM8.59998 3.29961C9.0418 3.29961 9.39997 2.94144 9.39997 2.49961C9.39997 2.05778 9.0418 1.69961 8.59998 1.69961C8.15815 1.69961 7.79998 2.05778 7.79998 2.49961V3.29961H8.59998Z" fill="currentColor"></path><path d="M6.19998 7.29961H1.39998V11.2996C1.39998 12.1833 2.11632 12.8996 2.99998 12.8996H6.19998V7.29961Z" fill="currentColor"></path><path d="M7.79998 12.8996H11C11.8836 12.8996 12.6 12.1833 12.6 11.2996V7.29961H7.79998V12.8996Z" fill="currentColor"></path></svg>GEM Rewards</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-deposit')" :class="{ active: currentRouteName == 'account-deposit' }"><span>Deposit</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-transfer')" :class="{ active: currentRouteName == 'account-transfer' }"><span>Transfer</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-withdrawal')" :class="{ active: currentRouteName == 'account-withdrawal' }"><span>Withdrawal</span></a></li>
              <li class="mt-4"><a v-on:click="setPage('account-balance')" :class="{ active: currentRouteName == 'account-balance' }"><span>Balances</span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-12 col-xl-10">
          <div class="row row--grid">
            <div class="col-12 col-sm-12 col-lg-12">
              <my-gems v-if="currentRouteName == 'account-my-gems'"></my-gems>
              <my-referral
                  v-if="currentRouteName == 'account-activity' && $route.params.slug == 'referral'"
                  @setCurrentUser="getCurrentUser"
              ></my-referral>
              <gem-rewards
                  v-if="currentRouteName == 'account-gem-rewards'"
                  @setPackageRedeem="getPackageRedeem"
                  @setCurrentUser="getCurrentUser"
              ></gem-rewards>
              <deposit
                  v-if="currentRouteName == 'account-deposit'"
              >
              </deposit>
              <balance
                  v-if="currentRouteName == 'account-balance'"
              >
              </balance>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="vld-parent">
      <loading
          :active.sync="$store.state.isLoading"
          loader="dots"
          color="#8853f9"
          v-if="$store.state.isLoading"
      />
    </div>
    <simplert />
    <modal-redeem
        v-show="$store.state.isRedeem"
        v-bind:package_data="package_data"
        v-bind:user_data="user_data"
    ></modal-redeem>
  </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import ICountUp from 'vue-countup-v2'
import ClickOutside from 'vue-click-outside';
import MyGems from "./account/MyGems";
import MyReferral from "./account/MyReferral";
import GemRewards from "./account/GemRewards";
import ModalRedeem from "./account/ModalRedeem";
import Deposit from "./account/Deposit";
import Balance from "./account/Balance";

export default {
  name: 'Account',
  components: {
    Loading,
    ICountUp,
    MyGems,
    MyReferral,
    GemRewards,
    ModalRedeem,
    Deposit,
    Balance,
  },
  data() {
    return {
      windowWidth: window.innerWidth,
      package_data: {},
      user_data: {},
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
  },
  mounted() {
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      themeToggleLightIcon.classList.remove('hidden');
    } else {
      themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');
    themeToggleBtn.addEventListener('click', function() {
      // toggle icons inside button
      themeToggleDarkIcon.classList.toggle('hidden');
      themeToggleLightIcon.classList.toggle('hidden');

      // if set via local storage previously
      if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
          document.documentElement.classList.add('dark');
          localStorage.setItem('color-theme', 'dark');
        } else {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('color-theme', 'light');
        }

        // if NOT set via local storage previously
      } else {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('color-theme', 'light');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('color-theme', 'dark');
        }
      }
    });
  },
  directives: {
    ClickOutside
  },
  methods: {
    setPage(name, target="_self") {
      //let routeData = this.$router.resolve({ name: name, query: {  } });
      //window.open(routeData.href, target);
      if(name == "account-activity-referral") {
        this.$router.push({ name: 'account-activity', params: { slug: 'referral' } }).catch( () => {} );
      } else if(name == "account-gem-rewards") {
        this.$router.push({ name: 'account-gem-rewards' }).catch( () => {} );
      } else {
        this.$router.push({ name: name, query: { } }).catch( () => {} );
      }
    },
    getPackageRedeem(package_data) {
      this.package_data = package_data
      console.log(this.package_data)
    },
    getCurrentUser(user_data) {
      this.user_data = user_data
    }
  },
}
</script>