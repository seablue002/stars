<template>
  <div class="list PAGE-MAIN-CONTENT">
    <el-card shadow="never" class="box-card">
      <template #header>
        <div class="card-header">
          <span>栏目列表</span>
        </div>
      </template>
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <ColumnEditableTree
            :mode="columnEditableTreeMode"
            @add-category="columnEditableTreeMode = 'add'"
            @edit-category="columnEditableTreeMode = 'edit'">
          </ColumnEditableTree>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import ColumnEditableTree from '@/components/business/ColumnEditableTree.vue'
export default defineComponent({
  components: {
    ColumnEditableTree
  },
  setup () {
    useAutoMainContentHeight()

    const columnEditableTreeMode = ref('add')
    return {
      columnEditableTreeMode
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
