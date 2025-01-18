<template>
  <div class="page-header">
    <el-page-header icon="arrowLeft" :content="title" @back="handleBack" />
  </div>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
import { useRouter } from 'vue-router'
export default defineComponent({
  props: {
    title: {
      type: String,
      default: ''
    }
  },
  emits: ['back'],
  setup (props, { emit }) {
    const router = useRouter()
    const handleBack = () => {
      emit('back')
      router.back()
    }
    return {
      handleBack
    }
  }
})
</script>
<style lang="scss" scoped>
.page-header {
  display: flex;
  align-items: center;
  height: 50px;
  padding: 0 20px;
  background: #fff;
  border-radius: 4px;
  box-sizing: border-box;
}
</style>
