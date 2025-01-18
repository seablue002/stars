<template>
  <div class="tips">
    <slot>{{title}}</slot>
  </div>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'Tips',
  props: {
    title: {
      type: String,
      default: ''
    }
  }
})
</script>
<style lang="scss" scoped>
.tips {
  display: flex;
  align-items: center;
  color: rgba(0,0,0,.45);
  font-size: 14px;
  line-height: 2em;
}
</style>
