<template>
  <div class="layout">
    <div class="sidebar-container" :class="{'sidebar-container--close': sidebarIsClose}">
      <side-logo></side-logo>
      <side-bar></side-bar>
    </div>

    <div class="main-container fixed-header">
      <top-nav-bar @fullscreen-change="handleScreenChange"></top-nav-bar>

      <tags-view ref="tagsViewRef"></tags-view>

      <div class="page-container">
        <!-- <router-view /> -->
        <BrigeRouterView :keepAlive="keepAlive"></BrigeRouterView>
      </div>

      <!-- S 滚动到顶部 -->
      <el-backtop></el-backtop>
      <!-- E 滚动到顶部 -->

      <copyright></copyright>
    </div>
  </div>
</template>

<script lang="ts">
// import { ref, onMounted } from 'vue'
import { computed, defineComponent } from 'vue'
import useSideBar from '@/hooks/useSideBar'
import BrigeRouterView from './BrigeRouterView.vue'
import SideLogo from './components/SideLogo.vue'
import SideBar from './components/SideBar.vue'
import TopNavBar from './components/TopNavBar.vue'
import TagsView from './components/TagsView.vue'
import Copyright from './components/Copyright.vue'
import { useRoute } from 'vue-router'
export default defineComponent({
  components: {
    BrigeRouterView,
    SideLogo,
    SideBar,
    TopNavBar,
    TagsView,
    Copyright
  },
  setup () {
    const route = useRoute()
    const { sidebarIsClose } = useSideBar()
    // const tagsViewRef = ref(null)

    // onMounted(() => {
    // })
    const handleScreenChange = () => {
      // this.$refs.tagsViewRef.$forceUpdate()
      console.log('全屏时需要实现调整tagsViewRef更新layout/index.vue line:54！！！！！！！！！！！')
    }
    const keepAlive = computed(() => {
      return route.matched[1].meta.keepAlive
    })
    return {
      sidebarIsClose,
      handleScreenChange,
      keepAlive
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
.layout {
  // 定义一个css变量便于后续修改切换
  --side-bar-w: #{$sideBarWidth};

  .sidebar-container {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;

    width: var(--side-bar-w);
    background-color: $menuBg;

    .sidebar {
      flex-grow: 1;
      height: calc(100% - 56px);
    }
  }
  .main-container {
    position: absolute;
    left: var(--side-bar-w);
    top: 0;
    width: calc(100% - var(--side-bar-w));
    min-height: 100vh;
    padding-bottom: 0;
    box-sizing: border-box;
    background: #f0f2f5;
    &.fixed-header {
      padding-top: 86px;
      .top-navbar {
        position: fixed;
        top: 0;
        width: calc(100% - var(--side-bar-w));
        z-index: 3;
      }
      .tags-view {
        position: fixed;
        top: 50px;
        z-index: 3;
        width: 100%;
        &::before {
          content: '';
          display: block;
          position: absolute;
          top: 0;
          width: 100%;
          height: 36px;
          background-color: #f0f2f5;
        }
      }
    }
    .page-container {
      margin: 0 10px 0;
    }
  }
}
</style>
