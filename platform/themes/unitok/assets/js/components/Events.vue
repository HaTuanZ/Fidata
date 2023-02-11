<template>
  <main class="main">
    <div class="container">
      <div class="block-container">
        <div class="block-find">
          <div class="filters">
            <div class="filter-column">
              <flat-pickr
                  v-model="date_range"
                  :config="flatpickr_config"
                  placeholder="Select a date"
                  class="form-control"
                  @on-change="getEventsData"
              ></flat-pickr>
            </div>
            <div class="filter-column">
              <div class="elem-select select-no-search" :class="{ 'elem-select-focus': opened_category }" id="select_cats" v-click-outside="hideDropdownCategories">
                <div class="select-caption" id="select_cats_cap" @click="toggleDropdownCategories">{{ selectedCategory }}</div>
                <transition name="slide-up-down" tag="div">
                  <div class="elem-list" id="elems_cats" v-show="opened_category">
                    <div class="arrow"></div>
                    <div class="list clusterize-scroll" id="list_cats">
                      <span
                          v-for="(tag, index) in tags"
                          :key="index"
                          :id="tag.id"
                          v-on:click="selectTag(tag.id)"
                          v-bind:class="{ active: categories.includes(tag.id) }"
                      >
                        {{ tag.name }}
                      </span>
                    </div>
                  </div>
                </transition>
              </div>

            </div>
            <div class="filter-column">
              <div class="elem-select select-no-search" :class="{ 'elem-select-focus': opened_coin }" id="select_coins" v-click-outside="hideDropdownCoins">
                <div class="select-caption" id="select_coins_cap" @click="toggleDropdownCoins">{{ selectedCoin }}</div>
                <transition name="slide-up-down" tag="div">
                  <div class="elem-list" id="elems_coins" v-show="opened_coin">
                    <div class="arrow"></div>
                    <div class="list clusterize-scroll" id="list_coins">
                      <span
                          v-for="(coin, index) in coins"
                          :key="index"
                          :id="coin.id"
                          v-on:click="selectCoin(coin.id)"
                          v-bind:class="{ active: symbols.includes(coin.id) }"
                      >
                        {{ coin.name }} <span class="gray">{{ coin.symbol }}</span>
                      </span>
                    </div>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </div>
        <div class="block-list">
          <div class="calendar">
            <div
                class="event"
                v-for="(event, index) in events"
                :key="index"
            >
                <div class="coin">
                  <img :src="coin_image(event.coin_id)" width="26" height="26" :alt="coin_obj(event.coin_id).name">
                  <h2>
                    <a href="#" :title="coin_obj(event.coin_id).name">{{ coin_obj(event.coin_id).name }}</a> <span>{{ coin_obj(event.coin_id).symbol }}</span>
                  </h2>
                </div>
                <div class="attrs">
                  <div class="icons">
                    <div class="cats">
                      <a href="#" class="category" :class="tag_obj(event.tags).name.toLowerCase()">{{ tag_obj(event.tags).name }}</a>
                    </div>
                    <i class="fa-solid" :class="{ 'fa-circle-check': event.source_reliable }" data-tooltip="Reliable Source"></i>
                  </div>
                  <div class="inds">
                    <div class="importance value1" data-tooltip="Low Importance">
                      <div class="value"></div>
                      <div class="value"></div>
                      <div class="value"></div>
                    </div>
                  </div>
                </div>
                <div class="caption">
                  <h3><a :href="event.source">{{ event.caption }}</a></h3>
                  <p v-if="event.important">Leo Messi’s triumphs are returning to Ethernity</p>
                  <div class="added">Added {{ event.date_public | moment("from", "now", true) }}</div>
                </div>
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
  </main>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import ClickOutside from 'vue-click-outside'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import 'flatpickr/dist/themes/dark.css'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
  name: 'Events',
  components: {
    Loading,
    flatPickr,
    vSelect
  },
  data() {
    return {
      flatpickr_config: {
        altFormat: "Y-m-d",
        dateFormat: "F j, Y",
        altInput: false,
        mode: "range",
      },
      date_range: null,
      tags: window.tags,
      opened_category: !1,
      blank_category: "Categories",
      selected_category: null,
      categories: [],
      coins: window.coins,
      opened_coin: !1,
      blank_coin: "Coins",
      selected_coin: null,
      symbols: [],
      events: [],
    };
  },
  computed: {
    selectedCategory() {
      if(this.categories.length) {
        let tag_name = this.tag_obj(this.categories[0]).name
        if(this.categories.length > 1) {
          return tag_name + " and " + (this.categories.length-1);
        } else {
          return tag_name
        }
      } else {
        return this.blank_category
      }
    },
    selectedCoin() {
      if(this.symbols.length) {
        let coin_name = this.coin_obj(this.symbols[0]).name
        if(this.symbols.length > 1) {
          return coin_name + " and " + (this.symbols.length-1);
        } else {
          return coin_name
        }
      } else {
        return this.blank_coin
      }
    }
  },
  created() {

  },
  mounted() {
    this.getEventsData()
  },
  directives: {
    ClickOutside
  },
  methods: {
    toggleDropdownCategories() {
      this.opened_category = !this.opened_category
    },
    hideDropdownCategories() {
      this.opened_category = !1
    },
    selectTag(tag_id) {
      const exists = this.categories.includes(tag_id)
      if (exists) {
        const result = this.categories.filter((category_id) => {
          return Number(category_id) !== Number(tag_id)
        })
        this.categories = result
      } else {
        this.categories.push(tag_id)
      }
      setTimeout( () => { this.getEventsData() }, 1100)
    },
    toggleDropdownCoins() {
      this.opened_coin = !this.opened_coin
    },
    hideDropdownCoins() {
      this.opened_coin = !1
    },
    selectCoin(coin_id) {
      const exists = this.symbols.includes(coin_id)
      if (exists) {
        const result = this.symbols.filter((symbol_id) => {
          return Number(symbol_id) !== Number(coin_id)
        })
        this.symbols = result
      } else {
        this.symbols.push(coin_id)
      }

      setTimeout( () => { this.getEventsData() }, 1100)
    },
    getEventsData() {
      const category_arr = [];
      if(this.categories.length) {
        this.categories.forEach(function(item, index, arr){
          category_arr.push(item.id)
        });
      }
      const coin_arr = [];
      if(this.symbols.length) {
        this.symbols.forEach(function(item, index, arr){
          coin_arr.push(item.id)
        });
      }
      this.$store.commit('setLastLoading', !0)
      axios.get("https://pro.coingen.net/api/v3/events", { params: { date_range: this.date_range, categories: this.categories, coins: this.symbols } }).then(response => {
        this.events = response.data
        this.$store.commit('setLastLoading', !1)
      }).catch(e => {
        console.log(e)
      });
    },
    coin_obj(coin_id) {
      let coin = this.coins.filter(
          (coin) => coin.id == coin_id
      )[0];
      return coin;
    },
    coin_image(coin_id) {
      return this.coin_obj(coin_id).image_64;
    },
    tag_obj(tag_id) {
      let tag = this.tags.filter(
          (tag) => tag.id == tag_id
      )[0];
      return tag;
    },
  },
}
</script>