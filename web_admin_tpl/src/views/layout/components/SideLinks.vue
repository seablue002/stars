<template>
  <div class="sidebar-trigger" @click="hanldeSwitchSidebarCloseState">
    <el-icon size="16px" v-if="sidebarIsClose"><expand /></el-icon>
    <el-icon size="16px" v-else><fold /></el-icon>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from 'vue'
import { useStore } from 'vuex'
import useSideBar from '@/hooks/useSideBar'

export default defineComponent({
  setup () {
    const store = useStore()
    const { sidebarIsClose, sidebarW } = useSideBar()
    const _initSideBarW = () => {
      const element = document.querySelector('.layout') as HTMLElement
      element && element.style.setProperty('--side-bar-w', String(sidebarW.value))
    }
    const hanldeSwitchSidebarCloseState = () => {
      const element = document.querySelector('.layout') as HTMLElement
      const sidebarIsCloseNewStatus = !sidebarIsClose.value
      const w = sidebarIsCloseNewStatus ? '64px' : '210px'
      element.style.setProperty('--side-bar-w', w)

      store.commit('app/setSidebarWVal', w)
      store.commit('app/setSidebarIsCloseVal', sidebarIsCloseNewStatus)
    }
    onMounted(() => {
      _initSideBarW()
    })

    return {
      sidebarIsClose,
      sidebarW,
      hanldeSwitchSidebarCloseState
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
.sidebar-trigger {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  cursor: pointer;
  .el-menu {
    border-right: 0;
    &-item {
      color: $menuText;
      background-color: $menuBg;

      &:hover {
        background-color: $menuHover;

        i {
          color: $menuActiveText!important;
        }
      }

      &.is-active {
        i {
          color: #909399;
        }
      }
    }
  }
}
</style>
