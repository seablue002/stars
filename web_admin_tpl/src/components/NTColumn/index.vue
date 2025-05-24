<!--
 * 分栏组件
-->
<template>
  <div class="nt-column">
    <div class="nt-column__wrapper">
      <div class="nt-column__header">
        <slot name="header"></slot>
      </div>

      <el-scrollbar v-if="scrollbar">
        <div class="nt-column__body">
          <slot></slot>
        </div>
      </el-scrollbar>
      <div v-else class="nt-column__body">
        <slot></slot>
      </div>

      <div class="nt-column__footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'NTColumn',
  props: {
    scrollbar: {
      type: Boolean,
      default: true,
    },
  },
}
</script>
<style lang="scss" scoped>
.nt-column {
  width: 100%;
  height: 100%;
  background-color: #fff;
  .nt-column__wrapper {
    display: flex;
    flex-direction: column;
    height: 100%;

    .nt-column__header {
      height: 44px;
      font-size: 16px;
      font-weight: 500;
      text-align: center;
      color: #000000d9;
      @apply flex;
      @apply items-center;
      @apply justify-center;
      @apply overflow-hidden;
      padding: 24px 12px;
      border-bottom: 1px solid #ebeef5;
    }

    .el-scrollbar {
      height: auto;
      flex: 1;

      .nt-column__body {
        padding: 12px 12px;
      }
    }

    .nt-column__footer {
      padding: 12px 12px;
      border-top: 1px solid #ebeef5;
    }
  }
}
</style>
