<!--
 * 表格设置
-->
<template>
  <el-form label-width="80px" label-position="left">
    <el-form-item label="显示尺寸">
      <el-radio-group v-model="tableConfig.size" size="small">
        <el-radio-button label="large">大</el-radio-button>
        <el-radio-button label="default">正常</el-radio-button>
        <el-radio-button label="small">小</el-radio-button>
      </el-radio-group>
    </el-form-item>
    <el-form-item label="风格化">
      <el-checkbox v-model="tableConfig.border" label="纵向边框" />
      <el-checkbox v-model="tableConfig.stripe" label="斑马纹" />
    </el-form-item>
  </el-form>
</template>
<script>
import { computed } from 'vue'

export default {
  name: 'NTTableSetting',
  props: {
    config: {
      type: Object,
      default: () => ({
        // Table 的尺寸
        size: 'default',
        // 是否为斑马纹 table
        stripe: true,
        // 是否带有纵向边框
        border: false,
      }),
    },
  },
  emits: ['update:config'],
  setup(props, context) {
    const { emit } = context

    const tableConfig = computed({
      get() {
        return props.config
      },
      set(value) {
        emit('update:config', value)
      },
    })

    return {
      tableConfig,
    }
  },
}
</script>
<style lang="scss" scoped></style>
