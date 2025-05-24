<!--
 * 导航-菜单
-->
<template>
  <div class="nt-nav-menu">
    <el-menu
      ref="elMenuRef"
      :default-active="defaultActive"
      :default-openeds="defaultOpeneds"
      :collapse="isNavMenuFold"
      active-text-color="#409eff"
      background-color="#3d3e3e"
      text-color="#fff"
      :hide-timeout="0"
      :collapse-transition="false"
    >
      <NTNavMenuItem :menuList="menuList"></NTNavMenuItem>
    </el-menu>
  </div>
</template>
<script>
import { ref, computed, watch } from 'vue'
import { isArray } from '@/utils/helper/is'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useLayoutStore from '@/store/modules/layout'
import NTNavMenuItem from './NTNavMenuItem/index.vue'

export default {
  components: {
    NTNavMenuItem,
  },
  props: {
    menuList: {
      type: Array,
      default: () => [],
    },
  },
  setup(props) {
    const { route, $tree } = useCurrentInstance()
    const layoutStore = useLayoutStore()

    const elMenuRef = ref(null)

    const defaultActive = ref('')

    // 默认打开sub-menu index数组
    const defaultOpeneds = computed(() => {
      const pathArr = []
      $tree.recursionMachine(props.menuList, (menu) => {
        if (
          isArray(menu.children) &&
          menu.children.length > 0 &&
          menu.meta?.defaultOpen
        ) {
          pathArr.push(menu.path)
        }
      })
      return pathArr
    })

    // 折叠、展开切换菜单操作
    const isNavMenuFold = computed(() => {
      return layoutStore.isNavMenuFold
    })

    // 监测route变化，动态el-menu defaultActive, 解决点击后的菜单active状态，无法通过tag切换更新
    watch(route, (nVal) => {
      const matchedMenus = []
      $tree.recursionMachine(props.menuList, (node) => {
        const matchedPathArr = nVal.matched.map((item) => {
          return item.path
        })

        const matched = matchedPathArr.includes(node.path)
        if (matched) {
          matchedMenus.push(node)
        }
      })

      const matchedMenusWithoutHidden = matchedMenus.filter((menu) => {
        return menu.meta.hidden !== true
      })

      defaultActive.value =
        matchedMenusWithoutHidden[matchedMenusWithoutHidden.length - 1]?.path
    })

    return {
      elMenuRef,
      defaultActive,
      defaultOpeneds,
      isNavMenuFold,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-nav-menu {
  // padding: 0 10px;
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
