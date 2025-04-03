<template>
  <div class="copyright">
    <p>
      <span v-html="copyright"></span>
      &nbsp;<span>版本{{ appVersion }}</span>
    </p>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'
import { APP_VERSION } from '@/config/app'
import { useStore } from 'vuex'
import { GlobalDataProps } from '@/store/index'

export default defineComponent({
  setup () {
    const store = useStore<GlobalDataProps>()
    const appVersion = APP_VERSION

    const copyright = computed(() => {
      return store.state.common.config?.systemConfig?.copyright?.value
    })
    return {
      appVersion,
      copyright
    }
  }
})
</script>
<style lang="scss" scoped>
.copyright {
  display: flex;
  justify-content: center;
}
</style>
