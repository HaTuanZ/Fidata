<template>
    <div class="vld-parent">
        <loading v-model:active="isLoading" />
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    data() {
        return {
            isLoading: false
        }
    },
    components: {
        Loading
    }
}
</script>
