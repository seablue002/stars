<template>
  <div
    class="nt-logo flex items-center justify-center"
    :class="{ 'nt-logo--fold': isNavMenuFold }"
  >
    <a class="nt-logo__wrapper flex justify-center" href="/">
      <div class="logo flex items-center">
        <img class="logo-img" src="~@/assets/images/logo.png" />
      </div>
      <div v-show="!isNavMenuFold" class="title">{{ APP_TITLE }}</div>
    </a>
  </div>
</template>
<script>
import { computed } from 'vue'
import useLayoutStore from '@/store/modules/layout'
import { APP_TITLE } from '@/settings/config/app'

export default {
  setup() {
    const layoutStore = useLayoutStore()
    // 折叠、展开切换菜单操作
    const isNavMenuFold = computed(() => {
      return layoutStore.isNavMenuFold
    })

    return {
      APP_TITLE,
      isNavMenuFold,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-logo {
  position: fixed;
  top: 0;
  z-index: 998;
  width: var(--nt-layout-menu-width);
  height: var(--nt-layout-logo-height);
  background: #3d3e3e;
  padding: 0 10px;
  overflow: hidden;
  &.nt-logo--fold {
    .logo {
      margin-left: 0px;
    }
  }
  .nt-logo-wrapper {
    width: 100%;
  }
  .logo {
    flex: 0 0 40px;
    &-img {
      width: 40px;
      height: 40px;
    }
  }

  .title {
    font-size: 18px;
    line-height: 55px;
    color: #fff;
    text-align: center;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
    padding: 0 0 0 12px;
    background: #3d3e3e;
    overflow: hidden;
  }
}
</style>
