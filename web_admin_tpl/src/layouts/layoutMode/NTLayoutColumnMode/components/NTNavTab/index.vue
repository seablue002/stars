<!--
 * 导航-tab
-->
<template>
  <div class="nt-nav-tab">
    <el-tabs
      v-model="activeNavTabName"
      tab-position="left"
      @tab-change="handleNavTabChange"
      @tab-click="handleNavTabClick"
    >
      <el-tab-pane
        v-for="tab in navTabLst"
        :key="tab.name"
        :label="tab.label"
        :name="tab.name"
      >
        <template #label>
          <i class="tab__icon" :class="tab?.meta?.icon"></i>
          <span>{{ tab.label }}</span>
        </template>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import { ref, computed, watch, toRefs, nextTick } from 'vue'
import useLayoutStore from '@/store/modules/layout'
import { ElMessageBox } from 'element-plus'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { NEED_CONFIRM_PAGE_ROUTE_PATH_LIST } from '@/settings/config/router'

export default {
  props: {
    navTabLst: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['tab-change'],
  setup(props, { emit }) {
    const layoutStore = useLayoutStore()
    const { router, route, $bus } = useCurrentInstance()
    const { navTabLst } = toRefs(props)
    const activeNavTabName = ref('')

    // 当前tab索引
    const activeNavTabIndex = computed(() => {
      const index = navTabLst.value.findIndex((tab) => {
        return tab.name === activeNavTabName.value
      })
      return index
    })

    // 监测路由变化，动态设置一级菜单tab当前索引
    watch(
      () => route,
      (nVal) => {
        const index = navTabLst.value.findIndex((tab) => {
          return tab.name === nVal.matched[1].path
        })

        if (index !== -1) {
          activeNavTabName.value = navTabLst.value[index].name

          emit('tab-change', activeNavTabIndex.value)
        }
      },
      {
        immediate: true,
        deep: true,
      }
    )

    // tab菜单点击切换
    const handleNavTabClick = async () => {
      const activeNavTabNameCopy = activeNavTabName.value
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
          activeNavTabName.value = activeNavTabNameCopy
          return
        }
      }

      nextTick(() => {
        router.push(activeNavTabName.value)

        setTimeout(() => {
          $bus.ntVisitedTagsSwitchBus.emit(true)
        }, 300)
      })
    }

    // tab菜单切换
    const handleNavTabChange = () => {
      emit('tab-change', activeNavTabIndex.value)
    }

    return {
      activeNavTabName,
      activeNavTabIndex,
      handleNavTabChange,
      handleNavTabClick,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-nav-tab {
  .el-tabs {
    &--left {
      width: 100%;
    }
    :deep(.el-tabs__header) {
      float: none;
      margin-right: 0;
    }

    :deep(.el-tabs__nav-wrap) {
      height: calc(100vh - var(--nt-layout-logo-height));
      &::after {
        display: none;
      }
      &.is-left {
        margin-right: 0;
        background: #282c34;
      }

      .el-tabs__nav {
        &.is-left {
          align-items: center;
          width: 100%;
          padding-top: 12px;
        }
      }
    }
    :deep(.el-tabs__active-bar) {
      &.is-left {
        display: none;
      }
    }
    :deep(.el-tabs__item) {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 54px;
      color: var(--el-color-white);
      border-radius: 5px;
      margin: 5px 0;

      &.is-active {
        background: var(--el-color-primary);
      }

      .tab__icon {
        font-size: 18px;
      }
    }
  }
}
</style>
