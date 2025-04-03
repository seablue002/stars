<template>
  <div id="appContainer">
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useStore } from 'vuex'
import { GlobalDataProps } from '@/store/index'
import { HTTP_CONFIG } from '@/config/http'
import { APP_NAME } from '@/config/app'
import { systemConfigApi } from '@/api/common'
export default defineComponent({
  setup () {
    const store = useStore<GlobalDataProps>()
    store.commit('app/initSidebarIsCloseVal')
    store.commit('app/initSidebarWVal')
    // 读取localStorage登录态数据到store
    store.commit('user/initAdminUserInfoVal')

    const appName = computed(() => {
      return store.state.common.config?.systemConfig?.app_name?.value || APP_NAME
    })

    // 获取系统配置
    const getSystemConfig = async () => {
      const params = {
        config_keys: [
          'app_name',
          'copyright'
        ]
      }
      const { status, data } = await systemConfigApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        store.commit('common/SET_SYSTEM_CONFIG', data)

        const webTitle = document.querySelector('title') as HTMLTitleElement | null
        webTitle && (webTitle.innerText = appName.value)
      }
    }

    getSystemConfig()
  }
})
</script>

<style lang="scss">
@import '~@/assets/iconfont/iconfont.css';
@import '~@/assets/style/app';
</style>
