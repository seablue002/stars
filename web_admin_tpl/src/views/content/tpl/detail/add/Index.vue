<template>
  <div class="add-tpl PAGE-MAIN-CONTENT">
    <PageHeader :title="hdTitle"></PageHeader>
    <keep-alive>
      <AddDetailTplForm ref="tplFormRef" :mode="mode"></AddDetailTplForm>
    </keep-alive>
  </div>
</template>
<script lang="ts">
import { computed, ref, defineComponent, onMounted, onActivated } from 'vue'
import { useRoute } from 'vue-router'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import PageHeader from '@/components/business/PageHeader.vue'
import AddDetailTplForm from './components/AddDetailTplForm.vue'
export default defineComponent({
  components: {
    PageHeader,
    AddDetailTplForm
  },
  setup () {
    const route = useRoute()
    useAutoMainContentHeight()
    useFixKeepAliveListRefresh.recode()

    // 详情
    const tplFormRef = ref()
    // 页面所处模式
    const mode = computed(() => {
      let mode = ''
      if (/\/add$/.test(route.path)) {
        mode = 'add'
      } else if (/\/edit$/.test(route.path)) {
        mode = 'edit'
      } else if (/\/detail$/.test(route.path)) {
        mode = 'detail'
      }
      return mode
    })
    const id = computed(() => {
      return route.query.id
    })
    const hdTitle = computed(() => {
      let tit = ''
      switch (mode.value) {
        case 'add':
          tit = '添加详情模板'
          break
        case 'edit':
          tit = '编辑详情模板'
          break
        case 'detail':
          tit = '详情模板详情'
          break
      }
      return tit
    })
    onMounted(() => {
      if (id.value && ['edit', 'detail'].includes(mode.value)) {
        tplFormRef.value.formMdl.id = id.value
      }
    })
    onActivated(() => {
      if (id.value && ['edit', 'detail'].includes(mode.value)) {
        tplFormRef.value.formMdl.id = id.value
      }
    })
    return {
      tplFormRef,
      id,
      mode,
      hdTitle
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
