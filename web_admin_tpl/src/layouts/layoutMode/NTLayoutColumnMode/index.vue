<!--
 * 布局模式-分栏模式
-->
<template>
  <div class="nt-layout-column-mode">
    <!-- S 左侧菜单导航 -->
    <el-scrollbar class="nt-layout-menu-scroll">
      <NTLogo></NTLogo>
      <NTNavTab :navTabLst="navTabLst" @tab-change="handleNavTabChange">
      </NTNavTab>
      <NTNavMenu :menuList="secondMenuList"></NTNavMenu>
    </el-scrollbar>
    <!-- E 左侧菜单导航 -->

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
import { ref, computed, watch, toRefs, inject } from 'vue'
import useLayoutStore from '@/store/modules/layout'
import useUserStore from '@/store/modules/user'
import NTHeader from '#LAYOUTS/NTHeader/index.vue'
import NTVisitedTags from '#LAYOUTS/NTVisitedTags/index.vue'
import NTContent from '#LAYOUTS/NTContent/index.vue'
import NTLogo from './components/NTLogo/index.vue'
import NTNavTab from './components/NTNavTab/index.vue'
import NTNavMenu from './components/NTNavMenu/index.vue'

export default {
  components: {
    NTLogo,
    NTNavTab,
    NTNavMenu,
    NTHeader,
    NTVisitedTags,
    NTContent,
  },
  setup() {
    const $is = inject('$is')
    const layoutStore = useLayoutStore()
    const userStore = useUserStore()
    const { ablePermissionRoutesFullTree } = toRefs(userStore)

    const isHeaderFixed = computed(() => {
      return layoutStore.isHeaderFixed
    })

    // 一级菜单
    const navTabLst = ref([])

    // 监测运行访问的整颗权限菜单树变化，动态生成一级菜单
    watch(
      () => ablePermissionRoutesFullTree.value,
      (nVal) => {
        if (!$is.isArray(nVal.children)) {
          return
        }
        navTabLst.value = nVal.children
          .map((node) => {
            const { children, ...rest } = node
            return rest
          })
          .map((node) => {
            return {
              name: node.path,
              label: node?.meta?.title,
              meta: node?.meta,
            }
          })
      },
      {
        immediate: true,
        deep: true,
      }
    )

    // 当前navTab索引
    const activeNavTabIndex = ref(0)

    // 二级菜单
    const secondMenuList = ref([])
    // 监测activeNavTabIndex变化，动态生成二级菜单
    watch(
      () => activeNavTabIndex.value,
      (nVal) => {
        secondMenuList.value =
          ablePermissionRoutesFullTree.value.children[nVal].children
      },
      {
        immediate: true,
      }
    )

    const handleNavTabChange = (index) => {
      activeNavTabIndex.value = index
    }

    // 刷新页面
    const ntContentRef = ref()
    const handleRefresh = (selectedTag) => {
      ntContentRef.value.refresh(selectedTag)
    }

    return {
      isHeaderFixed,
      navTabLst,
      activeNavTabIndex,
      secondMenuList,
      handleNavTabChange,
      ntContentRef,
      handleRefresh,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-layout-column-mode {
  // 左侧菜单导航宽度
  --nt-layout-menu-width: 266px;
  // 左侧菜单tab区高度
  --nt-layout-tab-width: 64px;
  // logo区域高度
  --nt-layout-logo-height: 60px;
  .nt-layout-menu-scroll {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 998;
    width: var(--nt-layout-menu-width);
    height: 100vh;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);

    :deep(.el-scrollbar__thumb) {
      opacity: 0.1;
    }

    .nt-nav-tab,
    .nt-nav-menu {
      height: 100vh;
    }
    .nt-nav-tab {
      position: fixed;
      width: var(--nt-layout-tab-width);
    }
    .nt-nav-menu {
      position: relative;
      left: var(--nt-layout-tab-width);
      width: calc(var(--nt-layout-menu-width) - var(--nt-layout-tab-width));
    }
  }

  .nt-layout-main {
    display: flex;
    flex-direction: column;
    height: 100vh;
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
