<template>
  <div class="top-navbar">
    <div class="top-navbar-left">
      <side-links></side-links>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item
          v-for="(breadCrumb, index) in breadCrumbList"
          :key="index"
        >
          {{ breadCrumb.meta.title }}
        </el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="top-navbar-right">
      <div class="fullscreen-container right-menu-item hover-effect">
        <screenfull class="right-menu-item" />
      </div>
      <el-dropdown class="avatar-container right-menu-item hover-effect" trigger="click" @command="handleCommand">
        <div class="avatar-wrapper">
          <img src="~@/assets/images/admin-user-avatar-default.png" class="user-avatar">
          <i class="el-icon-caret-bottom"></i>
          <div class="user-name">{{adminInfo?.admin_info?.username}}</div>
        </div>
        <template #dropdown>
          <el-dropdown-menu>
              <router-link
                v-for="(menu, index) in quickMenuList"
                :key="index"
                :to="menu.path">
                <el-dropdown-item>{{ menu.name }}</el-dropdown-item>
              </router-link>
              <!-- <el-dropdown-item divided command="modify-pwd">
                <span style="display:block;">修改密码</span>
              </el-dropdown-item> -->
              <el-dropdown-item divided command="login-out">
                <span style="display:block;">退出登录</span>
              </el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, Ref, computed } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import Screenfull from '@/components/Screenfull.vue'
import SideLinks from './SideLinks.vue'

export default defineComponent({
  components: {
    Screenfull,
    SideLinks
  },
  setup () {
    const route = useRoute()
    const isFullscreen: Ref<boolean> = ref(false)
    const breadCrumbList = computed(() => {
      return route.matched
    })
    const messageBoxInstance: any = null
    const quickMenuList = [
      {
        path: '/',
        name: '首页'
      },
      {
        path: '/goods',
        name: '商品信息'
      }
    ]
    const store = useStore()
    const modifyPasswordDialog = ref()
    const handleCommand = (command: string) => {
      switch (command) {
        // 退出登录
        case 'login-out':
          store.dispatch('user/out')
          break
        // 修改密码
        case 'modify-pwd':
          // modifyPasswordDialog.value.dialogFormVisible = true
          break
      }
    }

    const { getters } = useStore()
    const adminInfo = computed(() => getters['user/getAdminUserInfo'])

    return {
      isFullscreen,
      breadCrumbList,
      messageBoxInstance,
      quickMenuList,
      handleCommand,
      modifyPasswordDialog,
      adminInfo
    }
  }
})
</script>
<style lang="scss" scoped>
.top-navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 50px;
  overflow: hidden;
  padding: 0 15px 0 0;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);
  user-select: none;
  &-left {
    display: flex;
    align-items: center;
    width: 60%;
    :deep(.el-breadcrumb) {
      .el-breadcrumb__inner {
        display: flex;
      }
    }
  }
  &-right {
    display: flex;
    align-items: center;
    height: 100%;
    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect:hover {
        background: rgba(0,0,0,.025);
      }
    }
    .avatar-container {
      height: 100%;

      .avatar-wrapper {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        margin-right: 30px;
      }

      .user-avatar {
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        vertical-align: bottom;
        margin-right: 10px;
      }
      .user-name {
        display: flex;
        align-items: center;
        height: 100%;
        font-size: 14px;
      }

      i {
        cursor: pointer;
        position: absolute;
        right: -20px;
        top: 25px;
        font-size: 12px;
      }
    }

    .fullscreen-container {
      .fullscreen-wrapper {
        display: flex;
        align-items: center;
        height: 100%;

        i.fa {
          font-size: 18px;
          cursor: pointer;
        }
      }
    }
  }
}
</style>
