<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <el-card shadow="never">
      <NTEditableColumnTree
        ref="editableColumnTreeRef"
        :availableIsLastVal="[0, 1, 2]"
        class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
      >
      </NTEditableColumnTree>
    </el-card>
  </div>
</template>
<script>
import { defineComponent } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'

export default defineComponent({
  name: 'ColumnList',
  setup() {
    useAutoMainContentHeight()

    return {}
  },
})
</script>
<style lang="scss" scoped></style>
