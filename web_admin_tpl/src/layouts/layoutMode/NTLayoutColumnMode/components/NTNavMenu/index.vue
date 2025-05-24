<!--
 * 导航-菜单
-->
<template>
  <div class="nt-nav-menu">
    <el-menu>
      <NTNavMenuItem :menuList="menuList"></NTNavMenuItem>
    </el-menu>
  </div>
</template>
<script setup>
import NTNavMenuItem from './NTNavMenuItem/index.vue'
</script>
<script>
export default {
  props: {
    menuList: {
      type: Array,
      default: () => [],
    },
  },
  setup() {
    return {}
  },
}
</script>
<style lang="scss" scoped>
.nt-nav-menu {
  padding: 0 10px;
  box-sizing: border-box;
  .el-menu {
    border-right: 0;
    .el-sub-menu,
    .el-menu-item {
      border-radius: 5px;
    }
  }
}
</style>
