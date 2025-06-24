<!--
 * 列表搜索筛选过滤表单item
-->
<template>
  <div v-bind="$attrs" class="nt-search-form-filter-item">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'NTSearchFormFilterItem',
  setup() {
    return {}
  },
}
</script>
<style lang="scss" scoped>
.nt-search-form-filter-item {
  display: inline-block;
}
</style>
