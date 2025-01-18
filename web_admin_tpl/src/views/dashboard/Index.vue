<template>
  <div class="dashboard PAGE-MAIN-CONTENT">
  </div>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
export default defineComponent({
  components: {
  },
  setup () {
    useAutoMainContentHeight()
  }
})
</script>
<style lang="scss" scoped>
.dashboard {
}
</style>
