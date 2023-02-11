<template>
  <div class="container-iframe">
    <iframe class="responsive-iframe" src="https://market.coingen.net"></iframe>
  </div>
</template>
<script>
export default {
  name: 'market-overview-btc-overview',
  data() {
    return {
      site_url: window.siteUrl,
    };
  },
  props: {
    coin_object: Object,
  },
  watch: {
    coin_object: [{
      handler: 'getCoin'
    }]
  },
  mounted() {
    this.$nextTick(() => {

    })
  },
  methods: {
    getCoin() {

    },
  },
}
</script>
<style scoped>
.container-iframe {
  width: 100%;
  position: relative;
  height: 100%;
  margin: auto;
}
iframe {
  height: 100vh;
  width: calc(100vw - 260px);
}
</style>