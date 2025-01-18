<template>
  <div class="add-admin-user PAGE-MAIN-CONTENT">
    <PageHeader :title="hdTitle"></PageHeader>
    <keep-alive>
      <AddAdminUserForm ref="adminUserFormRef" :mode="mode"></AddAdminUserForm>
    </keep-alive>
  </div>
</template>
<script lang="ts">
import { computed, ref, defineComponent, onMounted, onActivated } from 'vue'
import { useRoute } from 'vue-router'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import PageHeader from '@/components/business/PageHeader.vue'
import AddAdminUserForm from './components/AddAdminUserForm.vue'
export default defineComponent({
  components: {
    PageHeader,
    AddAdminUserForm
  },
  setup () {
    const route = useRoute()
    useAutoMainContentHeight()
    useFixKeepAliveListRefresh.recode()

    // 详情
    const adminUserFormRef = ref()
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
          tit = '添加管理员'
          break
        case 'edit':
          tit = '编辑管理员'
          break
        case 'detail':
          tit = '管理员详情'
          break
      }
      return tit
    })
    onMounted(() => {
      if (id.value && ['edit', 'detail'].includes(mode.value)) {
        adminUserFormRef.value.formMdl.id = id.value
      }
    })
    onActivated(() => {
      if (id.value && ['edit', 'detail'].includes(mode.value)) {
        adminUserFormRef.value.formMdl.id = id.value
      }
    })
    return {
      adminUserFormRef,
      id,
      mode,
      hdTitle
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
