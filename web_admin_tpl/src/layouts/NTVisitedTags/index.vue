<template>
  <div ref="visitedTagsRef" class="nt-visited-tags">
    <div class="nt-visited-tags-container">
      <el-scrollbar ref="tagsScrollBar" class="scrollbar">
        <!-- <el-tabs
          :model-value="activeRouteName"
          type="card"
          :before-leave="handleBeforeLeave"
        > -->
        <el-tabs :model-value="activeRouteName" type="card">
          <!-- 非可关闭路由tab -->
          <el-tab-pane
            v-for="(tag, idx) in notCloseableVisitedTags"
            :key="tag.fullPath"
            :label="tag.meta.title"
            :name="tag.fullPath"
          >
            <template #label>
              <router-link
                :ref="`tagRef${idx + 1}`"
                :to="tag.fullPath"
                @contextmenu.prevent="openMenu(tag, $event)"
              >
                {{ tag.meta.title }}
                <el-icon :size="12" @click.stop="handleClose(tag)">
                  <CloseBold />
                </el-icon>
              </router-link>
            </template>
          </el-tab-pane>

          <el-tab-pane
            v-for="(tag, idx) in visitedTags"
            :key="tag.fullPath"
            :label="tag.meta.title"
            :name="tag.fullPath"
          >
            <template #label>
              <router-link
                :ref="`tagRef${notCloseableVisitedTags.length + idx + 1}`"
                :to="tag.fullPath"
                @click.middle="handleClose(tag)"
                @contextmenu.prevent="openMenu(tag, $event)"
              >
                {{ tag.meta.title }}
                <i
                  class="el-icon is-icon-close"
                  @click.stop.prevent="handleClose(tag)"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 1024 1024"
                  >
                    <path
                      fill="currentColor"
                      d="M764.288 214.592 512 466.88 259.712 214.592a31.936 31.936 0 0 0-45.12 45.12L466.752 512 214.528 764.224a31.936 31.936 0 1 0 45.12 45.184L512 557.184l252.288 252.288a31.936 31.936 0 0 0 45.12-45.12L557.12 512.064l252.288-252.352a31.936 31.936 0 1 0-45.12-45.184z"
                    ></path>
                  </svg>
                </i>
              </router-link>
            </template>
          </el-tab-pane>
        </el-tabs>
      </el-scrollbar>
      <ul
        v-show="visible"
        :style="{ left: left + 'px', top: top + 'px' }"
        class="contextmenu"
      >
        <!-- <li
          v-if="isActive(selectedTag)"
          @click.stop="handleRefresh(selectedTag)"
        >
          刷新
        </li> -->
        <li
          v-if="selectedTag?.fullPath !== '/dashboard'"
          @click.stop="handleClose(selectedTag)"
        >
          关闭
        </li>
        <li @click.stop="handleCloseOther(selectedTag)">关闭其它</li>
        <li @click.stop="handleCloseAll(selectedTag)">关闭所有</li>
      </ul>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  computed,
  ref,
  onMounted,
  getCurrentInstance,
  watch,
  nextTick,
  inject,
} from 'vue'
import { useRoute } from 'vue-router'
import { ElMessageBox } from 'element-plus'
import useAppStore from '@/store/modules/app'
import useLayoutStore from '@/store/modules/layout'
import { NEED_CONFIRM_PAGE_ROUTE_PATH_LIST } from '@/settings/config/router'

export default defineComponent({
  emits: ['refresh'],
  setup(props, { emit }) {
    const instance = getCurrentInstance()
    const route = useRoute()
    const visible = ref(false)
    const activeRouteName = ref(route.fullPath)
    const visitedTagsRef = ref()
    const tagsScrollBar = ref()
    const appStore = useAppStore()
    const layoutStore = useLayoutStore()
    const $array = inject('$array')
    const $is = inject('$is')
    const {
      proxy: { $bus },
    } = instance

    // tag访问历史页签关闭事件响应监听
    $bus.ntVisitedTagsBus.on(
      async ([pageRoute, autoOpenDefaultPage = true, callback]) => {
        const closeStatus = await handleClose(pageRoute, autoOpenDefaultPage)
        if (closeStatus) {
          if ($is.isFunction(callback)) {
            await callback()
            setTimeout(() => {
              $bus.ntVisitedTagsSwitchBus.emit(true)
            }, 300)
          }
        }
      }
    )

    const notCloseableVisitedTags = computed(() => {
      return appStore.visitedTags.filter((tag) => {
        return tag.meta.closeable === false
      })
    })

    const visitedTags = computed(() => {
      return appStore.visitedTags.filter((tag) => {
        return tag.meta.closeable !== false
      })
    })
    watch(
      () => route,
      () => {
        activeRouteName.value = route.fullPath
        const idx = $array.indexOfObjInObjArrByMultipleKey(
          {
            fullPath: route.fullPath,
          },
          [
            {
              fullPath: '/dashboard',
            },
            ...visitedTags.value,
          ],
          ['fullPath']
        )
        nextTick(() => {
          const curTag = instance?.refs[`tagRef${idx}`]
          let x = 0
          if ($is.isArray(curTag)) {
            x = curTag[0]?.$el.offsetLeft
          } else {
            x = curTag?.$el.offsetLeft
          }
          if (tagsScrollBar.value?.setScrollLeft) {
            tagsScrollBar.value.setScrollLeft(x ?? 0)
          }
        })
      },
      {
        deep: true,
      }
    )

    const closeMenu = () => {
      visible.value = false
    }

    const handleClose = async function handleClose(
      tag,
      autoOpenDefaultPage = true
    ) {
      if (
        NEED_CONFIRM_PAGE_ROUTE_PATH_LIST.includes(tag.path) &&
        !layoutStore.isSureConfirmLeave
      ) {
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
          return Promise.resolve(false)
        }
      }

      if (visible.value) {
        closeMenu()
      }
      const idx = $array.indexOfObjInObjArrByMultipleKey(
        {
          fullPath: tag.fullPath,
        },
        [
          {
            fullPath: '/dashboard',
          },
          ...visitedTags.value,
        ],
        ['fullPath']
      )

      appStore.removeOneVisitedTags(
        visitedTags.value[idx - 1],
        autoOpenDefaultPage
      )
      return Promise.resolve(true)
    }

    const handleCloseAll = (tag) => {
      if (visible.value) {
        closeMenu()
      }
      appStore.removeAllVisitedTags(tag)
    }

    const handleCloseOther = (tag) => {
      if (visible.value) {
        closeMenu()
      }
      appStore.removeOtherVisitedTags(tag)
    }
    const handleRefresh = (selectedTag) => {
      if (visible.value) {
        closeMenu()
      }
      // 刷新当前路由tag对应的页面
      emit('refresh', selectedTag)
    }

    const isActive = (tag) => {
      return tag?.fullPath === route.fullPath
    }

    const selectedTag = ref()
    const top = ref(0)
    const left = ref(0)
    const openMenu = (tag, e) => {
      const menuMinWidth = 105
      const offsetLeft = visitedTagsRef.value.getBoundingClientRect().left
      const { offsetWidth: visitedTagOffsetWidth } = visitedTagsRef
      const offsetWidth = visitedTagOffsetWidth
      const maxLeft = offsetWidth - menuMinWidth
      left.value = e.clientX - offsetLeft + 15

      if (left.value > maxLeft) {
        left.value = maxLeft
      } else {
        left.value *= 1
      }

      top.value = e.clientY - 52
      visible.value = true
      selectedTag.value = tag
    }

    onMounted(() => {
      document.body.addEventListener('click', closeMenu)
    })

    // 切换tab标签之前的钩子函数响应
    const handleBeforeLeave = async (activeName, oldActiveName) => {
      const index = [
        ...notCloseableVisitedTags.value,
        ...visitedTags.value,
      ].findIndex((tag) => {
        return tag.fullPath === oldActiveName
      })

      const currentTag = [
        ...notCloseableVisitedTags.value,
        ...visitedTags.value,
      ][index]

      let switchConfirmStatus = true

      if (NEED_CONFIRM_PAGE_ROUTE_PATH_LIST.includes(currentTag.path)) {
        switchConfirmStatus = await new Promise((resolve) => {
          $bus.ntVisitedTagsSwitchBus.on(async function handler(status) {
            if (status) {
              layoutStore.updateIsSureConfirmLeave(true)
              return resolve(true)
            }
            return resolve(false)
          })
        })
      }

      return switchConfirmStatus
    }

    return {
      visitedTagsRef,
      tagsScrollBar,
      notCloseableVisitedTags,
      visitedTags,
      activeRouteName,
      handleClose,
      handleCloseAll,
      handleCloseOther,
      handleRefresh,
      isActive,
      visible,
      left,
      top,
      openMenu,
      selectedTag,
      handleBeforeLeave,
    }
  },
})
</script>
<style lang="scss" scoped>
.nt-visited-tags {
  position: relative;
  border-bottom: solid 1px var(--el-menu-border-color);
  &-container {
    position: relative;
    height: 50px;
    width: 100%;
    padding-right: 20px;
    padding-left: 20px;
    background: #fff;
    user-select: none;
    box-sizing: border-box;

    :deep(.scrollbar) {
      width: 100%;
      height: 50px;
      .is-vertical {
        display: none;
      }
      .is-horizontal {
        display: block !important;
      }
      .el-scrollbar {
        &__wrap {
          position: relative;
          overflow-x: hidden !important;
        }

        &__view {
          position: absolute;
          width: 100%;
        }
      }
    }

    :deep(.el-tabs) {
      .el-tabs__header {
        height: 50px;
        border-bottom: 0;
        margin-bottom: 0;
        .el-tabs__nav {
          border: 0;
          &-wrap {
            .el-tabs__nav-prev,
            .el-tabs__nav-next {
              height: 100%;
              display: flex;
              align-items: center;

              &:hover {
                color: var(--el-color-primary);
              }
            }
          }
        }
        .el-tabs__item {
          position: relative;
          height: 40px;
          padding: 0;
          margin-top: 12px;
          margin-right: -18px;
          line-height: 38px;
          text-align: center;
          padding-left: 0 !important;
          border: 0;
          outline: none;
          transition: padding 0.3s cubic-bezier(0.645, 0.045, 0.355, 1) !important;
          &:last-child {
            // padding-right: 0;
          }
          &.is-active,
          &:hover {
            padding-right: 30px;
            mask: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANoAAAAkBAMAAAAdqzmBAAAAMFBMVEVHcEwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlTPQ5AAAAD3RSTlMAr3DvEM8wgCBA379gj5//tJBPAAAAnUlEQVRIx2NgAAM27fj/tAO/xBsYkIHyf9qCT8iWMf6nNQhAsk2f5rYheY7Dnua2/U+A28ZEe8v+F9Ax2v7/F4DbxkUH2wzgtvHTwbYPo7aN2jZq26hto7aN2jZq25Cy7Qvctnw62PYNbls9HWz7S8/G6//PsI6H4396gAUQy1je08W2jxDbpv6nD4gB2uWp+J9eYPsEhv/0BPS1DQBvoBLVZ3BppgAAAABJRU5ErkJggg==);
            mask-size: 100% 100%;
          }
          &.is-active,
          &.is-active:hover {
            color: var(--el-color-primary) !important;
            background: var(--el-color-primary-light-9) !important;
          }
          &.is-active {
            .el-icon {
              width: 14px;
              height: 14px;
            }
          }
          &:hover {
            color: var(--el-color-black) !important;
            background: #dee1e6;

            .el-icon {
              width: 14px;
              height: 14px;
            }
          }

          & > a {
            display: flex;
            align-items: center;
            padding: 0 16px 0 30px;
          }
        }
      }
    }

    .contextmenu {
      margin: 0;
      background: #fff;
      z-index: 3000;
      position: absolute;
      list-style-type: none;
      padding: 5px 0;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 400;
      color: #333;
      box-shadow: 2px 2px 3px 0 rgba(0, 0, 0, 0.3);
      li {
        margin: 0;
        padding: 7px 16px;
        cursor: pointer;
        &:hover {
          background: #eee;
        }
      }
    }
  }
}
</style>
