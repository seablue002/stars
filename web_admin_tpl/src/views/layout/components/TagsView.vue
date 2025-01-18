<template>
  <div class="tags-view" ref="tagsViewRef">
    <div class="tags-view-container">
      <el-scrollbar
        ref="tagsScrollBar"
        class="scrollbar"
      >
        <!-- <router-link
          to="/"
          ref="tagRef0"
          :class="$route.fullPath === '/dashboard' ? 'active' : ''"
          class="tags-view-item"
          @contextmenu.prevent="openMenu({ fullPath: '/dashboard' }, $event)"
        >
          首页
        </router-link> -->
        <router-link
          v-for="(tag, idx) in routerHistoryTags"
          :key="tag.fullPath"
          :to="tag.fullPath"
          :ref="`tagRef${idx + 1}`"
          :class="isActive(tag) ? 'active' : ''"
          class="tags-view-item"
          @click.middle="handleClose(tag)"
          @contextmenu.prevent="openMenu(tag, $event)"
        >
          {{ tag.meta.title }}
          <el-icon @click.prevent.stop="handleClose(tag)"><close /></el-icon>
        </router-link>
      </el-scrollbar>
      <ul v-show="visible" :style="{ left: left + 'px', top: top + 'px' }" class="contextmenu">
        <!-- <li v-if="isActive(selectedTag)" @click.stop="handleRefresh(selectedTag)">刷新</li> -->
        <li v-if="selectedTag?.fullPath !== '/dashboard'" @click.stop="handleClose(selectedTag)">关闭</li>
        <li @click.stop="handleCloseOther(selectedTag)">关闭其它</li>
        <li @click.stop="handleCloseAll(selectedTag)">关闭所有</li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, onMounted, getCurrentInstance, watch, nextTick } from 'vue'
import COMMON_TAGS_PATH from '@/config/commonHistoryTags'
import { indexOfObjInObjArrByMultipleKey, isArray } from '@/utils'
import { useStore } from 'vuex'
import { RouteLocationNormalized, useRoute } from 'vue-router'

export default defineComponent({
  setup () {
    const instance = getCurrentInstance()
    const route = useRoute()
    const tagsViewRef = ref()
    const tagsScrollBar = ref()
    const store = useStore()
    const routerHistoryTags = computed(() => {
      return store.state.app.routerHistoryTags.filter((tag: RouteLocationNormalized) => {
        return !COMMON_TAGS_PATH.includes(tag.path)
      })
    })
    watch(() => route, () => {
      const idx = indexOfObjInObjArrByMultipleKey({ fullPath: route.fullPath }, [{ fullPath: '/dashboard' }, ...routerHistoryTags.value], ['fullPath'])
      nextTick(() => {
        const curTag = instance?.refs[`tagRef${idx}`]
        let x = 0
        if (isArray(curTag)) {
          x = (curTag as any)[0]?.$el.offsetLeft
        } else {
          x = (curTag as any)?.$el.offsetLeft
        }
        if (tagsScrollBar.value?.setScrollLeft) {
          tagsScrollBar.value.setScrollLeft(x)
        }
      })
    }, {
      deep: true
    })

    const handleClose = (tag: RouteLocationNormalized) => {
      if (visible.value) {
        closeMenu()
      }
      store.commit('app/removeRouterHistoryTagsVal', tag)
    }

    const handleCloseAll = (tag: RouteLocationNormalized) => {
      if (visible.value) {
        closeMenu()
      }
      store.commit('app/removeAllRouterHistoryTagsVal', tag)
    }

    const handleCloseOther = (tag: RouteLocationNormalized) => {
      if (visible.value) {
        closeMenu()
      }
      store.commit('app/removeOtherRouterHistoryTagsVal', tag)
    }
    const handleRefresh = (tag: RouteLocationNormalized) => {
      if (visible.value) {
        closeMenu()
      }
      store.commit('app/refreshRouterHistoryTagsVal', tag)
    }

    const isActive = (tag: RouteLocationNormalized) => {
      const route = useRoute()
      return tag?.fullPath === route.fullPath
    }

    const visible = ref(false)
    const selectedTag = ref<any>()
    const top = ref(0)
    const left = ref(0)
    const openMenu = (tag: RouteLocationNormalized, e: MouseEvent) => {
      const menuMinWidth = 105
      const offsetLeft = tagsViewRef.value.getBoundingClientRect().left
      const offsetWidth = tagsViewRef.value.offsetWidth
      const maxLeft = offsetWidth - menuMinWidth
      left.value = e.clientX - offsetLeft + 15

      if (left.value > maxLeft) {
        left.value = maxLeft
      } else {
        left.value = left.value * 1
      }

      top.value = e.clientY - 50
      visible.value = true
      selectedTag.value = tag
    }

    const closeMenu = () => {
      visible.value = false
    }

    onMounted(() => {
      document.body.addEventListener('click', closeMenu)
    })

    return {
      tagsViewRef,
      tagsScrollBar,
      routerHistoryTags,
      handleClose,
      handleCloseAll,
      handleCloseOther,
      handleRefresh,
      isActive,
      visible,
      left,
      top,
      openMenu,
      selectedTag
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
.tags-view {
  &-container {
    height: 36px;
    width: 100%;
    background: transparent;
    user-select: none;

    :deep(.scrollbar) {
      width: 100%;
      height: 100%;
      .is-vertical {
        display: none;
      }
      .is-horizontal {
        display: block !important;
      }
      .el-scrollbar {
        &__wrap {
          position: relative;
          display: flex;
          align-items: center;
          overflow-x: hidden!important;
        }

        &__view {
          display: flex;
          position: absolute;
        }
      }
    }

    .tags-view-item {
      display: flex;
      align-items: center;
      cursor: pointer;
      height: 26px;
      line-height: 26px;
      border: 1px solid #d8dce5;
      color: #495060;
      background: #fff;
      padding: 0 8px;
      font-size: 12px;
      margin-left: 5px;
      word-break: keep-all;
      &:first-of-type {
        margin-left: 10px;
      }
      &:last-of-type {
        margin-right: 10px;
      }
      &.active {
        background-color: #fff;
        border-color: #fff;
        &::before {
          content: '';
          background: $mainThemeColor;
          display: inline-block;
          width: 8px;
          height: 8px;
          border-radius: 50%;
          position: relative;
          margin-right: 2px;
        }
      }

      .el-icon {
        width: 16px;
        height: 16px;
        vertical-align: 2px;
        border-radius: 50%;
        text-align: center;
        transition: all .3s cubic-bezier(.645, .045, .355, 1);
        transform-origin: 100% 50%;
        margin-left: 2px;
        &:before {
          transform: scale(.6);
          display: inline-block;
          vertical-align: -3px;
        }
        &:hover {
          background-color: #b4bccc;
          color: #fff;
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
      box-shadow: 2px 2px 3px 0 rgba(0, 0, 0, .3);
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
