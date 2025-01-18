<template>
  <div class="list PAGE-MAIN-CONTENT">
    <el-card shadow="never" class="box-card">
      <template #header>
        <div class="card-header">
          <span>分类列表</span>
        </div>
      </template>
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <CategoryEditableTree
            :mode="categoryEditableTreeMode"
            @add-category="categoryEditableTreeMode = 'add'"
            @edit-category="categoryEditableTreeMode = 'edit'">
          </CategoryEditableTree>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import CategoryEditableTree from '@/components/business/CategoryEditableTree.vue'
export default defineComponent({
  components: {
    CategoryEditableTree
  },
  setup () {
    useAutoMainContentHeight()

    const categoryEditableTreeMode = ref('add')
    return {
      categoryEditableTreeMode
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
