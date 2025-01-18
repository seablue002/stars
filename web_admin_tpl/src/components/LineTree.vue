<template>
  <div class="line-tree">
    <div
      v-for="(row, idx) in treeData"
      :key="idx"
      class="row">
      <LineTreeItem :treeData="[row]"></LineTreeItem>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, PropType } from 'vue'
import LineTreeItem from './LineTreeItem.vue'
export default defineComponent({
  name: 'LineTree',
  components: {
    LineTreeItem
  },
  props: {
    treeData: {
      type: Array as PropType<any[]>,
      default () {
        return []
      }
    }
  },
  setup () {
    return {}
  }
})
</script>
<style lang="scss" scoped>
.line-tree {
  .row {
    display: flex;
  }
}
</style>
