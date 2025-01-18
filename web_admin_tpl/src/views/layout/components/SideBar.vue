<template>
  <div class="sidebar">
    <el-scrollbar class="scrollbar">
      <el-menu
        :collapse="sidebarIsClose"
        :default-active="$route.fullPath"
        :collapse-transition="false"
        :default-openeds="['/base']"
        router
        unique-opened>
        <side-bar-item :menu="sidebarMenu"></side-bar-item>
      </el-menu>
    </el-scrollbar>
  </div>
</template>

<script lang="ts">
import { baseRoutes, asyncPermissionRoutes, matchPermissionRoutes } from '@/router/routes'
import SideBarItem from './SideBarItem.vue'
import useSideBar from '@/hooks/useSideBar'
import { defineComponent, computed } from 'vue'

export default defineComponent({
  components: {
    SideBarItem
  },
  setup () {
    const { sidebarIsClose } = useSideBar()
    const sidebarMenu = computed(() => {
      matchPermissionRoutes(asyncPermissionRoutes)
      return (baseRoutes.filter((route) => {
        return route.path === '/'
      })[0].children || []).concat(asyncPermissionRoutes)
    })

    return {
      sidebarIsClose,
      sidebarMenu
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
.sidebar {
  background-color: $menuBg;
  user-select: none;
  :deep(.scrollbar) {
    height: 100%;
    .is-horizontal {
      display: none;
    }
    .el-scrollbar__wrap {
      overflow-x: hidden!important;
    }
  }

  :deep(.el-menu) {
    border-right: 0;
    background-color: $menuBg;

    &:deep(--inline) {
      .el-sub-menu {
        .el-sub-menu__title {
          background-color: $subMenuBg;

          &:hover {
            background-color: $subMenuHover;
          }
        }
      }
    }
    .el-menu-item {
      color: $menuText;
      background-color: $menuBg;

      &:hover {
        background-color: $menuHover;
      }

      &.is-active {
        color: $menuActiveText;
        background-color: $menuActive;

        i {
          color: $menuActiveText;
        }
      }
    }
  }

  :deep(.el-sub-menu) {
    .el-sub-menu__title {
      color: $menuText;
      background-color: $menuBg;
    }

    .el-menu-item {
      background-color: $subMenuBg;

      &:hover {
        background-color: $subMenuHover;
      }
    }

    &:hover {
      background-color: $menuHover;
    }

    &.is-active {
      &>.el-sub-menu__title {
        color: $subMenuActiveText;

        &>i {
          color: $menuActiveText;
        }
      }
      .el-menu-item {
        &.is-active {
          color: #{$subMenuActiveText} !important;
          background-color: $subMenuActive;

          &>i {
            color: $menuActiveText;
          }
        }
      }
    }
  }

  .el-menu--collapse {
    :deep(.el-menu-item span), :deep(.el-sub-menu>.el-sub-menu__title span) {
      height: 0;
      width: 0;
      overflow: hidden;
      visibility: hidden;
      display: inline-block;
    }
    :deep(.el-menu-item .el-sub-menu__icon-arrow), :deep(.el-sub-menu>.el-sub-menu__title .el-sub-menu__icon-arrow) {
      display: none;
    }
  }
}
</style>
