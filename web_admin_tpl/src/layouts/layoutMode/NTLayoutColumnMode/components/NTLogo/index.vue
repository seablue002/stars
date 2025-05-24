<template>
  <div class="nt-logo">
    <a href="/">
      <div class="logo">
        <img class="logo-img" src="@/assets/images/logo.png" />
      </div>
      <div class="title">{{ APP_TITLE }}</div>
    </a>
  </div>
</template>
<script>
import { APP_TITLE } from '@/settings/config/app'

export default {
  setup() {
    return {
      APP_TITLE,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-logo {
  position: relative;
  height: var(--nt-layout-logo-height);
  .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    width: var(--nt-layout-tab-width);
    height: var(--nt-layout-logo-height);
    background: #282c34;

    &-img {
      width: 48px;
      height: 48px;
    }
  }

  .title {
    font-size: 20px;
    line-height: 55px;
    color: #fff;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
    color: var(--el-color-black);
    padding-right: 15px;
    padding-left: 15px;
    margin-left: var(--nt-layout-tab-width);
    background: #fff;
    overflow: hidden;
  }
}
</style>
