<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <el-card shadow="never">
      <NTEditableLabelTree
        ref="editableLabelTreeRef"
        :availableIsLastVal="[0, 1]"
        class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
      >
      </NTEditableLabelTree>
    </el-card>
  </div>
</template>
<script>
import { defineComponent } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'

export default defineComponent({
  name: 'LabelList',
  setup() {
    useAutoMainContentHeight()

    return {}
  },
})
</script>
<style lang="scss" scoped></style>
