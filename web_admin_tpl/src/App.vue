<template>
  <div id="app">
    <router-view></router-view>

    <!-- S 滚动到顶部 -->
    <el-backtop></el-backtop>
    <!-- E 滚动到顶部 -->
  </div>
</template>

<script>
import '#ASSETS/style/app.scss'
import useCommonStore from '@/store/modules/common'

export default {
  setup() {
    const commonStore = useCommonStore()
    commonStore.getSystemConfig(['app_name', 'copyright'], 'systemConfig')
  },
}
</script>

<style lang="scss" scoped></style>
