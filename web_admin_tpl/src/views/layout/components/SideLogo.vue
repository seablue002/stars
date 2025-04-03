<template>
  <div class="side-logo">
    <a>
      <img src="~@/assets/images/logo.png" alt="logo">

      <h1 v-show="!sidebarIsClose">{{ appName }}</h1>
    </a>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { APP_NAME } from '@/config/app'
import useSideBar from '@/hooks/useSideBar'
import { useStore } from 'vuex'
import { GlobalDataProps } from '@/store/index'

export default defineComponent({
  setup () {
    const store = useStore<GlobalDataProps>()
    const { sidebarIsClose } = useSideBar()

    const appName = computed(() => {
      return store.state.common.config?.systemConfig?.app_name?.value || APP_NAME
    })
    return {
      appName,
      sidebarIsClose
    }
  }
})
</script>
<style lang="scss" scoped>
.side-logo {
  position: relative;
  display: flex;
  align-items: center;
  padding: 16px 10px;
  cursor: pointer;
  user-select: none;

  a {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 32px;

    img {
      display: inline-block;
      height: 32px;
      vertical-align: middle;
    }

    h1 {
      display: inline-block;
      height: 32px;
      margin: 0 0 0 6px;
      color: #fff;
      font-weight: 600;
      font-size: 16px;
      line-height: 32px;
      vertical-align: middle;
      animation: title-hide .3s;
    }

    span {
      color: #fff;
      font-size: 12px;
      margin-left: 4px;
    }
  }
}

@keyframes title-hide {
  0% {
    display: none;
    opacity: 0;
  }
  80% {
      display: none;
      opacity: 0;
  }
  100% {
      display: unset;
      opacity: 1;
  }
}
</style>
