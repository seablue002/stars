<template>
  <div @click="handleClick">
    <el-icon v-if="!isFullscreen" :size="50"><i class="iconfont icon-fullscreen-expand"></i></el-icon>
    <el-icon v-else :size="50"><i class="iconfont icon-fullscreen-shrink"></i></el-icon>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, onBeforeUnmount, Ref, ref, getCurrentInstance } from 'vue'
import screenfull from 'screenfull'
export default defineComponent({
  name: 'Screenfull',
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    const isFullscreen:Ref<boolean> = ref(false)

    const change = () => {
      isFullscreen.value = (screenfull as any).isFullscreen
    }

    const init = () => {
      if ((screenfull as any).enabled) {
        (screenfull as any).on('change', change)
      }
    }
    const destroy = () => {
      if ((screenfull as any).enabled) {
        (screenfull as any).off('change', change)
      }
    }

    const handleClick = () => {
      if (!(screenfull as any).enabled) {
        proxy.message({
          message: '您的浏览器暂不支持',
          type: 'warning'
        })
        return false
      }
      (screenfull as any).toggle()
    }

    onMounted(() => {
      init()
    })
    onBeforeUnmount(() => {
      destroy()
    })
    return {
      isFullscreen,
      handleClick
    }
  }
})
</script>

<style lang="scss" scoped>
.el-icon {
  width: auto;
}
.iconfont {
  font-size: 25px;
  cursor: pointer;
}
</style>
