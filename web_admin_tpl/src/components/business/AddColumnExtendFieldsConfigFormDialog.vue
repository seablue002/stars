<template>
  <el-dialog
    v-model="dialogFormVisible"
    :title="hdTitle"
    width="800px"
    :lock-scroll="lockScroll"
    @close="$emit('close')"
  >
    <slot></slot>
    <AddColumnExtendFieldsConfigForm
      v-if="dialogFormVisible"
      ref="addColumnExtendFieldsConfigFormRef"
      :mode="mode"
      @add-success="$emit('add-success')"
      @edit-success="$emit('edit-success')">
    </AddColumnExtendFieldsConfigForm>
  </el-dialog>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from 'vue'
import AddColumnExtendFieldsConfigForm from './AddColumnExtendFieldsConfigForm.vue'

export default defineComponent({
  components: {
    AddColumnExtendFieldsConfigForm
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
          tit = '添加栏目自定义字段配置项'
          break
        case 'edit':
          tit = '编辑栏目自定义字段配置项'
          break
        case 'detail':
          tit = '栏目自定义字段配置详情'
          break
      }
      return tit
    })

    const addColumnExtendFieldsConfigFormRef = ref()

    return {
      dialogFormVisible,
      hdTitle,
      addColumnExtendFieldsConfigFormRef
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
