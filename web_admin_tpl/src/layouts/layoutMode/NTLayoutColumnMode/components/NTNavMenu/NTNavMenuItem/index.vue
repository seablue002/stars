<!--
 * 菜单item组件
-->
<template>
  <div class="nt-nav-menu-item">
    <template v-for="(item, index) in menuList" :key="item.path + index">
      <el-sub-menu
        v-if="
          item.children &&
          hasNoneHiddenChildrenMenu(item.children) &&
          item?.meta?.hidden !== true
        "
        :index="item.path"
      >
        <template #title>
          <i class="menu__icon mr-[4px]" :class="item?.meta?.icon"></i>
          <span>{{ item?.meta?.title }}</span>
        </template>
        <NTNavMenuItem :menuList="item.children"></NTNavMenuItem>
      </el-sub-menu>
      <el-menu-item
        v-else-if="item?.meta?.hidden !== true"
        :index="item.path"
        :class="{ 'is-active': defaultSelectedJudgement(item) }"
        :disabled="true"
      >
        <template #title>
          <div
            class="menu-link__wrapper"
            @click.prevent="handleRouterMenuLink(item.path)"
          >
            <i
              v-if="item.children.length"
              class="menu__icon mr-[4px]"
              :class="item?.meta?.icon"
            >
            </i>
            <span>{{ item?.meta?.title }}</span>
          </div>
        </template>
      </el-menu-item>
    </template>
  </div>
</template>
<script>
import { nextTick } from 'vue'
import { ElMessageBox } from 'element-plus'
import useLayoutStore from '@/store/modules/layout'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { NEED_CONFIRM_PAGE_ROUTE_PATH_LIST } from '@/settings/config/router'

export default {
  name: 'NTNavMenuItem',
  props: {
    menuList: {
      type: Array,
      default: () => [],
    },
  },
  setup() {
    const layoutStore = useLayoutStore()
    const { route, router, $bus } = useCurrentInstance()

    // 是否有未隐藏的children子菜单
    const hasNoneHiddenChildrenMenu = (childrenMenu) => {
      return childrenMenu.some((menu) => {
        return menu.meta.hidden !== true
      })
    }

    // 判断是否默认添加选中样式is-active类名
    const defaultSelectedJudgement = (tag) => {
      const matchedPathArr = route.matched.map((item) => {
        return item.path
      })
      return matchedPathArr.includes(tag.path)
    }

    // 关闭el-menu route属性后，手动触发菜单跳转路由
    const handleRouterMenuLink = async (path) => {
      if (NEED_CONFIRM_PAGE_ROUTE_PATH_LIST.includes(route.path)) {
        const isSure = await new Promise((resolve) => {
          setTimeout(() => {
            ElMessageBox.confirm('内容未保存，确认退出？', '提示', {
              type: 'warning',
            })
              .then(() => {
                layoutStore.updateIsSureConfirmLeave(true)
                resolve(true)
              })
              .catch(() => {
                layoutStore.updateIsSureConfirmLeave(false)
                resolve(false)
              })
          }, 200)
        })

        if (!isSure) {
          return
        }
      }

      nextTick(() => {
        router.push(path)

        setTimeout(() => {
          $bus.ntVisitedTagsSwitchBus.emit(true)
        }, 300)
      })
    }

    return {
      hasNoneHiddenChildrenMenu,
      defaultSelectedJudgement,
      handleRouterMenuLink,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-nav-menu-item {
  .el-menu-item {
    padding: 0 !important;
    &:hover,
    &.is-active {
      color: var(--el-color-primary);
      background-color: var(--el-color-primary-light-9) !important;
      border-radius: 5px;
    }
    &.is-disabled {
      opacity: 1;
      cursor: pointer;
      background-color: var(--el-color-primary-light-9);
    }

    .menu-link__wrapper {
      width: 100%;
      padding: 0 20px;
    }
  }

  .menu__icon {
    font-size: 18px;
  }
}
</style>
