<!--
 * 页面头部header
-->
<template>
  <div
    class="nt-header clear-both"
    :class="{ 'nt-header--fixed': isHeaderFixed }"
  >
    <el-row>
      <el-col :xs="6" :sm="12" :md="12" :lg="12" :xl="12" is-guttered>
        <div class="float-left">
          <div class="menu-fold" @click="handleSwitchMenuFold">
            <i v-if="!isNavMenuFold" class="ri-menu-fold-line"></i>
            <i v-else class="ri-menu-unfold-line"></i>
          </div>

          <el-breadcrumb :separator-icon="ArrowRight">
            <el-breadcrumb-item
              v-for="(breadCrumb, index) in breadCrumbList"
              :key="index"
              :to="{ path: breadCrumb.path }"
            >
              {{ breadCrumb?.meta?.title }}
            </el-breadcrumb-item>
          </el-breadcrumb>
        </div>
      </el-col>
      <el-col :xs="18" :sm="12" :md="12" :lg="12" :xl="12" is-guttered>
        <div class="float-right">
          <el-menu
            mode="horizontal"
            :ellipsis="false"
            :collapse-transition="false"
          >
            <el-menu-item index="1" @click="handleSwitchFullScreen">
              <i v-if="!isFullScreen" class="ri-fullscreen-line"></i>
              <i v-else class="ri-fullscreen-exit-line"></i>
            </el-menu-item>
            <el-sub-menu index="2">
              <template #title>
                <img
                  src="@/assets/images/avatar.png"
                  class="w-[40px] mr-[4px] rounded-[50%]"
                />
                <span>{{ userInfo?.name }}</span>
              </template>
              <el-menu-item index="2-1" @click="handleLoginOut">
                <el-space wrap :size="8">
                  <i class="ri-shut-down-line"></i>
                  <span>退出登录</span>
                </el-space>
              </el-menu-item>
            </el-sub-menu>
          </el-menu>
        </div>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { computed, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import useLayoutStore from '@/store/modules/layout'
import useUserStore from '@/store/modules/user'
import useAppStore from '@/store/modules/app'
import { ArrowRight } from '@element-plus/icons-vue'
import screenfull from 'screenfull'
import { ElMessage, ElMessageBox } from 'element-plus'
import { LOGIN_PAGE_ROUTE_PATH } from '@/settings/config/router'

export default {
  emits: ['show-download-dialog'],
  setup() {
    const route = useRoute()
    const router = useRouter()
    const layoutStore = useLayoutStore()
    const userStore = useUserStore()
    const appStore = useAppStore()

    // 面包屑导航
    const breadCrumbList = computed(() => {
      return route.matched.filter((item) => {
        return item.path !== '/'
      })
    })

    // 用户信息
    const userInfo = computed(() => {
      return userStore.userInfo || {}
    })

    const isHeaderFixed = computed(() => {
      return layoutStore.isHeaderFixed
    })

    // 折叠、展开切换菜单操作
    const isNavMenuFold = computed(() => {
      return layoutStore.isNavMenuFold
    })
    const handleSwitchMenuFold = () => {
      layoutStore.updateNavMenuFoldStatus(!layoutStore.isNavMenuFold)
    }

    // 全屏切换操作
    const isFullScreen = computed(() => {
      return layoutStore.isFullScreen
    })

    const fullScreenhandler = () => {
      layoutStore.updateFullScreenStatus(screenfull.isFullscreen)
    }

    const initFullScreen = () => {
      if (screenfull.isEnabled) {
        screenfull.on('change', fullScreenhandler)
      }
    }

    const destroyFullScreen = () => {
      if (screenfull.isEnabled) {
        screenfull.off('change', fullScreenhandler)
      }
    }

    const handleSwitchFullScreen = () => {
      if (!screenfull.isEnabled) {
        ElMessage.warning({
          message: '您的浏览器暂不支持全屏',
          type: 'warning',
        })
      } else {
        screenfull.toggle()
      }
    }
    onMounted(() => {
      initFullScreen()
    })
    onBeforeUnmount(() => {
      destroyFullScreen()
    })

    // 退出登录
    const handleLoginOut = () => {
      ElMessageBox.confirm('确定退出系统吗?', '提示', { type: 'warning' })
        .then(async () => {
          userStore
            .loginOut()
            .then((message) => {
              ElMessage.success({
                message,
              })
              appStore.clear()
              router.replace(LOGIN_PAGE_ROUTE_PATH)
            })
            .catch(() => {
              ElMessage.error({
                message: '退出登录失败！',
              })
            })
        })
        .catch(() => {})
    }

    return {
      ArrowRight,
      breadCrumbList,
      userInfo,
      isHeaderFixed,
      isNavMenuFold,
      handleSwitchMenuFold,
      isFullScreen,
      handleSwitchFullScreen,
      handleLoginOut,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-header {
  position: relative;
  height: 60px;
  padding: 0 20px;
  overflow: hidden;
  user-select: none;
  background: #fff;
  box-shadow: 0 1px 4px rgb(0 21 41 / 1%);

  &--fixed {
    position: fixed;
    left: calc(var(--nt-layout-menu-width) + 1px);
    right: 0;
    top: 0;
    z-index: 998;
    border-bottom: 1px solid #f6f6f6;
  }

  .float-left {
    display: flex;
    align-items: center;
    justify-items: center;
    height: 60px;

    .menu-fold {
      padding-right: 10px;
      [class^='ri-'] {
        color: var(--el-color-grey);
        cursor: pointer;
      }
    }

    :deep(.el-breadcrumb) {
      .el-breadcrumb__item {
        .el-breadcrumb__inner {
          &.is-link {
            font-weight: 500;
          }
        }
      }
    }
  }

  .float-right {
    .el-menu--horizontal {
      border-bottom: 0;
      .el-menu-item {
        font-size: 16px;
        height: var(--el-menu-item-height);
        border-bottom: 0;
        &:not(.is-disabled) {
          &:hover,
          &:focus {
            color: var(--el-menu-text-color) !important;
            background-color: transparent;
          }
        }
        &.is-active {
          color: var(--el-menu-text-color) !important;
          border-bottom: 0;
        }
      }
      .el-sub-menu {
        &:hover,
        &.is-active {
          :deep(.el-sub-menu__title) {
            color: var(--el-menu-text-color) !important;
          }
        }

        &.is-active {
          :deep(.el-sub-menu__title) {
            color: var(--el-menu-text-color) !important;
            border-bottom: 0;
          }
        }

        :deep(.el-sub-menu__title) {
          padding-left: 0;
          transition: none;
        }
      }
    }
  }
}

@media only screen and (max-width: 768px) {
  .float-left {
    .el-breadcrumb {
      display: none;
    }
  }
}
</style>
