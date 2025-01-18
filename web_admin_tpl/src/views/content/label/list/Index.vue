<template>
  <div class="list PAGE-MAIN-CONTENT">
    <el-card shadow="never" class="box-card">
      <template #header>
        <div class="card-header">
          <span>标签列表</span>
        </div>
      </template>
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <LabelEditableTree
            :mode="labelEditableTreeMode"
            @add-category="labelEditableTreeMode = 'add'"
            @edit-category="labelEditableTreeMode = 'edit'">
          </LabelEditableTree>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import LabelEditableTree from '@/components/business/LabelEditableTree.vue'
export default defineComponent({
  components: {
    LabelEditableTree
  },
  setup () {
    useAutoMainContentHeight()

    const labelEditableTreeMode = ref('add')
    return {
      labelEditableTreeMode
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
