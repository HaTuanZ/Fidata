<template>
  <div class="container-iframe">
    <iframe class="responsive-iframe" src="https://market.coingen.net/btc-performance"></iframe>
  </div>
</template>
<script>
export default {
  name: 'market-overview-btc-performance',
  data() {
    return {
      site_url: window.siteUrl,
    };
  },
  mounted() {
    this.$nextTick(() => {

    })
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