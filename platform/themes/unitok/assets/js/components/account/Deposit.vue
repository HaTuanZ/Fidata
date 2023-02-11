<template>
  <div class="my-diamonds-container">
    <h3 class="font-bold text-2xl md:text-3xl lg:text-4xl mb-2">Deposit</h3>
    <ul class="nav nav-tabs main__tabs" id="main__tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-deposit" role="tab" aria-controls="tab-deposit" aria-selected="true">Deposit form</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-history" role="tab" aria-controls="tab-history" aria-selected="false">History</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-deposit" role="tabpanel">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-8 lg:gap-10 mt-3">
          <div class="_basis-1/2">
            <div class="mb-6">
              <label for="currency_symbol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Coin</label>
              <select id="currency_symbol" v-model="currency_symbol" v-on:change="selectCurrencySymbol($event)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option
                    v-for="(cs, index) in currency_symbols"
                    :key="cs.id"
                    v-bind:value="cs.id"
                    selected="currency_symbol === cs.symbol"
                >{{ cs.symbol }} ({{ cs.name }})</option>
              </select>
            </div>
            <div class="mb-6">
              <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
              <input type="number" id="amount" v-model="amount" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <div class="mb-6">
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
              <input type="email" id="email" v-model="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">if you don't have an account before.</p>
            </div>
            <div class="mb-6">
              <label for="note" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Which wallet address did you use to send the fund? Let any delay be handled immediately</label>
              <textarea id="note" rows="4" v-model="note" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
            </div>
            <div class="mb-6 bg-stone-100 dark:bg-zinc-400 rounded p-2.5 text-sm">
              <div class="d-flex align-items-center mb-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 4.791a.723.723 0 00.716-.729V2.729c0-.402-.32-.729-.716-.729a.723.723 0 00-.716.73v1.332c0 .402.32.73.716.73zM6.884 6.51a.713.713 0 01-.716.72.733.733 0 01-.508-.2l-.936-.94a.713.713 0 01-.212-.515c0-.197.076-.385.212-.515a.734.734 0 011.016 0l.932.934c.136.13.212.319.212.516zm4.436 14.032h1.336c.396 0 .716.326.716.729 0 .402-.32.729-.716.729h-1.332a.723.723 0 01-.716-.73c0-.38.32-.707.712-.729zM2.716 10.268h1.332c.388 0 .716.335.716.73 0 .401-.32.728-.716.728H2.716A.723.723 0 012 10.998c0-.394.328-.73.716-.73zm16.776-4.694a.696.696 0 00-.212-.511.701.701 0 00-1.02 0l-.932.934a.713.713 0 00-.212.516c0 .197.076.386.212.515.14.135.324.202.508.202a.719.719 0 00.508-.206l.932-.934a.73.73 0 00.216-.516zm.46 4.694h1.332c.388 0 .716.335.716.73 0 .401-.32.728-.716.728h-1.332a.723.723 0 01-.716-.729c0-.402.32-.73.716-.73zm-5.964 8.294h-3.976a.723.723 0 00-.716.73c0 .402.32.729.716.729h3.976a.723.723 0 00.716-.73c0-.402-.32-.729-.716-.729zM12 5.981c1.612 0 3.124.625 4.26 1.76A5.984 5.984 0 0118.024 12c0 1.61-.628 3.122-1.764 4.258a5.982 5.982 0 01-4.26 1.76 5.982 5.982 0 01-4.26-1.76A5.984 5.984 0 015.976 12c0-1.61.628-3.123 1.764-4.258A5.982 5.982 0 0112 5.982z" fill="currentColor"></path></svg><span>Tips</span></div>
              <p>If you have deposited, please pay attention to the text messages, site letters and emails we send to you. Coins will be deposited after 12 network confirmations.</p>
            </div>
          </div>
          <div class="_basis-1/2">
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700" v-if="wallets.length">
              <ul class="flex flex-wrap -mb-px">
                <li
                    class="mr-2"
                    v-for="(row, index) in wallets"
                    :key="row.id"
                >
                  <a
                      href="#"
                      class="inline-block p-4 border-b-2"
                      :class="{ 'text-blue-600  border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500': (row.id == wallet_id), 'border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': (row.id != wallet_id) }"
                  >
                    {{ row.name }}
                  </a>
                </li>
              </ul>
            </div>
            <div class="mt-5 text-center" v-if="wallet_obj.address">
              <h4 class="font-semibold text-xl md:text-2xl lg:text-2.5xl mb-2">{{ wallet_obj.name }} address</h4>
              <img :src="getQrWaller(wallet_obj.address)" class="mx-auto mb-3" />
              <div class="flex align-items-center justify-content-center mb-4">
                <strong class="text-md" id="wallet-address">{{ wallet_obj.address }}</strong>
                <span v-on:click="copyAddress()" class="ml-2 cursor-pointer"><i class="fa-light fa-copy"></i></span>
              </div>
              <div class="flex align-items-center text-left">
                <div class="pr-5">
                  <p class="font-semibold">Send only {{ currency_symbol_obj.symbol }} to this deposit address.</p>
                  <p class="text-md">Sending coin or token other than USDT to this address may result in the loss of your deposit.</p>
                </div>
                <div class="ml-auto">
                  <img :src="getMediaSrc(currency_symbol_obj.image)" class="max-w-[60px]" />
                </div>
              </div>
              <div class="my-4 text-left">
                <button type="button" v-on:click="submitDeposit($event)" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full">Deposit now</button>
              </div>
            </div>
          </div>
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
              <th>Exchange</th>
              <th>Note</th>
              <th>Email</th>
              <th>status</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(log, index) in logs"
                :key="index"
            >
              <td>{{ log.created_at | moment("MMM DD, YYYY HH:mm:ss") }}</td>
              <td>{{ log.amount }} {{ log.currency_symbol }}</td>
              <td>{{ log.note }}</td>
              <td>{{ log.email_account }}</td>
              <td>{{ deposit_status(log.status) }}</td>
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
export default {
  name: 'Account-Deposit',
  data() {
    return {
      windowWidth: window.innerWidth,
      currency_symbols: [],
      currency_symbol: null,
      currency_symbol_obj: {},
      wallets: [],
      wallet_id: 0,
      wallet_obj: {},
      amount: "",
      email: "",
      note: "",
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
  created(){
      this.getCurrencySymbols()
  },
  mounted() {
    this.getLogsData()
  },
  methods: {
    getCurrencySymbols() {
      axios.post(window.apiUrl+"/currency-symbols", {
        type: 'deposit'
      }).then(response => {
        this.currency_symbols = response.data
        this.currency_symbol_obj = this.currency_symbols[0]
        this.currency_symbol = this.currency_symbol_obj.id

        setTimeout(() => this.getWallets(), 300)
      }).catch(e => {
        console.log(e)
      });
    },
    selectCurrencySymbol(event) {
      let value = event.target.value
      this.currency_symbol = value
      this.currency_symbol_obj = this.currency_symbols.find(d => d.id == value)
      this.getWallets()
    },
    getWallets() {
      axios.post(window.apiUrl+"/wallets", {
        currency_symbol_id: this.currency_symbol_obj.id
      }).then(response => {
        this.wallets = response.data
        this.wallet_obj = this.wallets[0]
        this.wallet_id = this.wallet_obj.id
      }).catch(e => {
        console.log(e)
      });
    },
    getQrWaller(address) {
      return 'https://chart.googleapis.com/chart?chs=240x240&cht=qr&chl='+address+'&choe=UTF-8'
    },
    getMediaSrc(src) {
      return 'https://media.coingen.net/upload/coins/'+src
    },
    copyAddress() {
      let text = this.wallet_obj.address
      try {
        var r = document.createRange();
        r.selectNode(document.getElementById('wallet-address'));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(r);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();

        this.$Simplert.open({
          type: "success",
          message: 'Copied: '+text
        });
      } catch (err) {
        this.$Simplert.open({
          type: "error",
          message: "Oops, unable to copy"
        });
      }
    },
    submitDeposit(event) {
      let deposit_amount = Number(amount.value)
      if(deposit_amount > 0) {
        this.$store.commit('setLastLoading', !0)
        axios.post(window.siteUrl+"/account/submit-deposit", {
          amount: deposit_amount,
          currency_symbol: this.currency_symbol_obj.symbol,
          email: email.value,
          note: note.value,
          wallet_id: this.wallet_id,
        }).then(response => {
          this.$store.commit('setLastLoading', !1)
          const json = response.data
          if(json.error) {
            this.$Simplert.open({
              title: "Deposit",
              type: "error",
              message: json.msg
            });
          } else {
            this.$Simplert.open({
              title: "Deposit",
              type: "success",
              message: json.msg
            });
            this.amount = ""
            this.email = ""
            this.note = ""
          }
        }).catch(e => {
          console.log(e)
        });
      } else {
        this.$Simplert.open({
          title: "Deposit",
          type: "warning",
          message: "Invalid deposit amount."
        });
      }
    },
    getLogsData(parameters) {
      let page = parameters !== undefined ? parameters.page : 1

      this.$store.commit('setLastLoading', !0)
      axios.post(window.siteUrl+"/account/deposits", {
        page: page
      }).then(response => {
        this.pagination_data = response.data
        this.logs = this.pagination_data.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    deposit_status(status) {
      if(status == 3) {
        return "Cancelled";
      } else if (status == 5) {
        return "Confirmed";
      } else {
        return "Pending";
      }
    }
  },
}
</script>