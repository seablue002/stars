<!--
 * 页面内容显示区域
-->
<template>
  <div class="nt-content">
    <routerView v-slot="{ route, Component }">
      <KeepAlive
        :include="cacheRouteNameList"
        :exclude="excludeCacheRouteNameList"
        :max="20"
      >
        <component
          :is="Component"
          v-if="isRefresh"
          :key="route.fullPath"
        ></component>
      </KeepAlive>
    </routerView>

    <NTCopyright></NTCopyright>
  </div>
</template>
<script>
import { ref, defineAsyncComponent } from 'vue'
import { useRouter } from 'vue-router'

export default {
  components: {
    NTCopyright: defineAsyncComponent(() => import('../NTCopyright/index.vue')),
  },
  setup() {
    const router = useRouter()
    const routes = router.getRoutes()

    // 需要进行缓存的页面路由name集合
    const cacheRouteNameList = ref([])
    cacheRouteNameList.value = routes
      .filter((route) => {
        return route.meta.cache === true
      })
      .map((route) => {
        return route.name
      })

    // 不需要缓存的路由name集合，目前专门用于对keep-alive缓存的页面进行刷新配合处理
    const excludeCacheRouteNameList = ref([])

    // 刷新当前页
    const isRefresh = ref(true)
    const refresh = (route) => {
      excludeCacheRouteNameList.value = [route.name]
      isRefresh.value = false
      setTimeout(() => {
        isRefresh.value = true
        excludeCacheRouteNameList.value = []
      }, 0)
    }

    return {
      cacheRouteNameList,
      excludeCacheRouteNameList,
      isRefresh,
      refresh,
    }
  },
}
</script>
<style lang="scss" scoped></style>
