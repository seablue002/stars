<template>
  <div class="nt-folding-box-right">
    <el-scrollbar>
      <slot></slot>
    </el-scrollbar>
  </div>
</template>
<script>
export default {
  name: 'NTFoldingBoxRight',
}
</script>
<style lang="scss" scoped>
.nt-folding-box-right {
  flex: 1;
  overflow-x: hidden;
}
</style>
