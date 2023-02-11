<template>
  <div class="flex justify-center">
    <div tabindex="-1" aria-hidden="true" :class="{ hidden: !$store.state.isRedeem }" class="fixed top-0 left-0 right-0 z-[200] w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
      <div class="relative w-full h-full max-w-[640px] md:h-auto mx-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <!-- Modal header -->
          <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Redeem Now</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" v-on:click="closeModalRedeem($event)">
              <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="p-6 space-y-6">
            <img :src="package_data.image">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ package_data.name }}</h4>
            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400" v-if="package_data.description" v-html="nl2br(package_data.description)"></p>
            <div class="flex justify-between">
              <div class="flex align-items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">Price</span>
                <span class="flex justify-items-center rounded-lg p-2 ml-1.5 bg-white dark:bg-slate-800 ring-1 ring-slate-900/10 hover:ring-slate-300 dark:ring-0">
                  <i class="fa-regular fa-dollar-sign"></i>
                  <span class="pl-1" v-if="!discount">{{ this.package_data.price | formatInteger }}</span>
                  <span class="pl-1" v-if="discount">{{ this.discount | formatInteger }}</span>
                </span>
              </div>
              <div class="flex align-items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">Your balance</span>
                <span class="flex justify-items-center rounded-lg p-2 ml-1.5 bg-white dark:bg-slate-800 ring-1 ring-slate-900/10 hover:ring-slate-300 dark:ring-0">
                  <i class="fa-regular fa-dollar-sign"></i>
                  <span class="pl-1">{{ user_data.balance | formatInteger }}</span>
                </span>
              </div>
            </div>
            <div>
              <label for="hs-trailing-button-add-on" class="sr-only">Label</label>
              <div class="flex rounded-md shadow-sm">
                <input type="text" v-model="coupon_code" ref="coupon_code" placeholder="Enter your coupon code" class="py-2.5 px-4 block w-full border-gray-200 shadow-sm rounded-l-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                <button type="button" v-on:click="applyCoupon($event)" class="py-2.5 px-4 inline-flex flex-shrink-0 justify-center items-center gap-2 rounded-r-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                  Apply
                </button>
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="button" v-on:click="submitPackage($event)" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
            <button v-on:click="closeModalRedeem($event)" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Account-Modal-Redeem',
  data() {
    return {
      windowWidth: window.innerWidth,
      coupon_code: '',
      coupon_data: null,
      discount: null,
    };
  },
  props: {
    package_data: Object,
    user_data: Object,
  },
  computed: {
    package_price() {
      return this.package_data.price
    },
  },
  mounted() {

  },
  methods: {
    closeModalRedeem() {
      this.$store.commit('setLastRedeem', !1)
    },
    nl2br(str, is_xhtml) {
      if (typeof str === 'undefined' || str === null) {
        return '';
      }
      var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    },
    applyCoupon(event) {
      this.coupon_code = this.$refs.coupon_code.value
      if(this.coupon_code) {
        this.$store.commit('setLastLoading', !0)
        axios.post(window.siteUrl+"/account/apply-coupon", {
          coupon_code: this.coupon_code,
          package_id: this.package_data.id
        }).then(response => {
          this.$store.commit('setLastLoading', !1)
          const json = response.data
          if(json.error) {
            this.$Simplert.open({
              title: "Apply Coupon",
              type: "error",
              message: json.msg
            });
            this.coupon_data = null
            this.discount = null
          } else {
            this.$Simplert.open({
              title: "Apply Coupon",
              type: "success",
              message: json.msg
            });
            this.coupon_data = json.coupon_data
            this.discount = "-"+json.discount
          }
        }).catch(e => {
          console.log(e)
        });
      }
    },
    submitPackage(event) {
      if(this.package_data.id) {
        this.$store.commit('setLastLoading', !0)
        axios.post(window.siteUrl+"/account/submit-package", {
          package_id: this.package_data.id
        }).then(response => {
          this.$store.commit('setLastLoading', !1)
          const json = response.data
          if(json.error) {
            this.$Simplert.open({
              title: "Apply Package",
              type: "error",
              message: json.msg
            });
          } else {
            this.$Simplert.open({
              title: "Apply Package",
              type: "success",
              message: json.msg
            });
            this.user_data.balance = this.user_data.balance - Number(json.redeemed)
            this.$store.commit('setLastRedeem', !1)
          }
        }).catch(e => {
          console.log(e)
        });
      }
    },
  }
}
</script>