<template>
  <el-dialog
    v-model="dialogFormVisible"
    :title="hdTitle"
    width="800px"
    :lock-scroll="lockScroll"
    @close="$emit('close')"
  >
    <slot></slot>
    <AddSystemConfigForm
      v-if="dialogFormVisible"
      ref="addSystemConfigFormRef"
      :mode="mode"
      @add-success="$emit('add-success')"
      @edit-success="$emit('edit-success')">
    </AddSystemConfigForm>
  </el-dialog>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from 'vue'
import AddSystemConfigForm from './AddSystemConfigForm.vue'

export default defineComponent({
  components: {
    AddSystemConfigForm
  },
  emits: [
    'add-success',
    'edit-success',
    'close'
  ],
  props: {
    mode: {
      type: String,
      default: 'add'
    },
    lockScroll: {
      type: Boolean,
      dafault: true
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  setup (props) {
    const dialogFormVisible = ref(false)

    const hdTitle = computed(() => {
      let tit = ''
      switch (props.mode) {
        case 'add':
          tit = '添加系统配置项'
          break
        case 'edit':
          tit = '编辑系统配置项'
          break
        case 'detail':
          tit = '系统配置详情'
          break
      }
      return tit
    })

    const addSystemConfigFormRef = ref()

    return {
      dialogFormVisible,
      hdTitle,
      addSystemConfigFormRef
    }
  }
})
</script>
<style lang="scss" scoped>
:deep(.el-dialog) {
  height: auto;
  padding-bottom: 30px;
}
</style>
