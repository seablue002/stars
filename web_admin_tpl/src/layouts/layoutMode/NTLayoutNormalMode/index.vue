<!--
 * 布局模式-分栏模式
-->
<template>
  <div class="nt-layout-column-mode">
    <div class="nt-layout-aside">
      <NTLogo></NTLogo>
      <!-- S 左侧菜单导航 -->
      <el-scrollbar class="nt-layout-menu-scroll">
        <NTNavMenu
          :menuList="ablePermissionRoutesFullTree?.children"
        ></NTNavMenu>
      </el-scrollbar>
      <!-- E 左侧菜单导航 -->
    </div>

    <!-- S main内容主体 -->
    <div
      class="nt-layout-main"
      :class="{ 'nt-layout-main--has-fixed-header': isHeaderFixed }"
    >
      <NTHeader></NTHeader>
      <NTVisitedTags @refresh="handleRefresh"></NTVisitedTags>
      <NTContent ref="ntContentRef"></NTContent>
    </div>
    <!-- E main内容主体 -->
  </div>
</template>
<script>
import { ref, computed } from 'vue'
import useLayoutStore from '@/store/modules/layout'
import useUserStore from '@/store/modules/user'
import NTHeader from '#LAYOUTS/NTHeader/index.vue'
import NTVisitedTags from '#LAYOUTS/NTVisitedTags/index.vue'
import NTContent from '#LAYOUTS/NTContent/index.vue'
import NTLogo from './components/NTLogo/index.vue'
import NTNavMenu from './components/NTNavMenu/index.vue'

export default {
  components: {
    NTLogo,
    NTNavMenu,
    NTHeader,
    NTVisitedTags,
    NTContent,
  },
  setup() {
    const layoutStore = useLayoutStore()
    const userStore = useUserStore()
    const { ablePermissionRoutesFullTree } = userStore

    const isHeaderFixed = computed(() => {
      return layoutStore.isHeaderFixed
    })

    // 刷新页面
    const ntContentRef = ref()
    const handleRefresh = (selectedTag) => {
      ntContentRef.value.refresh(selectedTag)
    }

    return {
      isHeaderFixed,
      ablePermissionRoutesFullTree,
      ntContentRef,
      handleRefresh,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-layout-column-mode {
  // 左侧菜单导航宽度
  --nt-layout-menu-width: 200px;
  // logo区域高度
  --nt-layout-logo-height: 60px;
  .nt-layout-aside {
    width: var(--nt-layout-menu-width);
    .nt-layout-menu-scroll {
      position: fixed;
      top: var(--nt-layout-logo-height);
      bottom: 0;
      left: 0;
      z-index: 998;
      width: var(--nt-layout-menu-width);
      height: calc(100vh - var(--nt-layout-logo-height));
      overflow: hidden;
      background: #3d3e3e;
      box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);

      :deep(.el-scrollbar__thumb) {
        opacity: 0.1;
      }

      .nt-nav-menu {
        position: relative;
        left: 0;
        width: calc(var(--nt-layout-menu-width) - 0);
      }
    }
  }

  .nt-layout-main {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 60px);
    margin-left: calc(var(--nt-layout-menu-width) + 1px);

    &--has-fixed-header {
      margin-top: 60px;
    }

    .nt-content {
      flex: 1;

      :deep(.page-container) {
        height: 100%;
      }
    }
  }
}
</style>
