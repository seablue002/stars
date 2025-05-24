<template>
  <div class="nt-editable-system-config-category-tree">
    <NTEditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteCategory"
      @select-row="handleSelectRow"
    >
    </NTEditableTree>

    <!-- S 分类添加、修改弹窗 -->
    <component
      :is="EditableCategoryDialogAsyncComp"
      ref="editableCategoryDialogAsyncCompRef"
      @add-success="handleCategoryAddSuccess"
      @edit-success="handleCategoryEditSuccess"
    ></component>
    <!-- E 分类添加、修改弹窗 -->
  </div>
</template>
<script>
import { defineComponent, ref, onMounted } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import useAsyncComponent from '@/hooks/useAsyncComponent'

export default defineComponent({
  name: 'NTEditableSystemConfigCategoryTree',
  props: {
    availableIsLastVal: {
      type: Array,
      default: () => [0, 1, 2],
    },
  },
  emits: ['init-category-list', 'select-row', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { getTree, formateTree } = $tree

    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 分类数据列表
    const treeData = ref([])
    const treeDataKeyMap = {
      id: 'id',
      label: 'name',
      pid: 'pid',
      children: 'children',
      pids: 'pids',
    }
    const getCategoryList = async () => {
      treeIsLoading.value = true
      const apiRes =
        await $api.systemConfigCategory.systemConfigCategoryListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        treeData.value = []
        const treeArr = []
        getTree(treeArr, data, {
          id: 'id',
          pid: 'pid',
          name: 'name',
        })
        const allTreeNodeData = [
          {
            id: 0,
            pid: ROOT_NODE_PID,
            name: '全部分类',
            children: treeArr || [],
            pids: '',
          },
        ]

        formateTree(treeData.value, allTreeNodeData, treeDataKeyMap)
      } else {
        $message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000,
        })
      }
      treeIsLoading.value = false
      return data
    }

    onMounted(() => {
      initCategoryList()
    })

    const initCategoryList = async () => {
      const categoryList = await getCategoryList()
      emit('init-category-list', categoryList)
    }

    // 删除
    const handleDeleteCategory = ({ data, selectedId }) => {
      deleteCategory(data.id, selectedId)
    }

    const deleteCategory = async (id, selectedId) => {
      const params = {
        id,
      }
      const apiRes =
        await $api.systemConfigCategory.deleteSystemConfigCategoryApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        if (selectedId === id) {
          initCategoryList()
        } else {
          getCategoryList()
        }
        emit('delete-success', { isSelected: selectedId === id })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 选择
    const handleSelectRow = (data) => {
      emit('select-row', data)
    }

    const categoryTreeMode = ref('add')
    const handleOpenAddDialog = ({ data }) => {
      categoryTreeMode.value = 'add'
      let pids = data?.pids?.split(',')
      pids = pids.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableCategoryDialog(pids)
    }
    const handleOpenEditDialog = async ({ data }) => {
      categoryTreeMode.value = 'edit'
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableCategoryDialog(pids)
    }

    // 分类添加、修改弹窗
    const editableCategoryDialogAsyncCompRef = ref(null)
    let editableCategoryDialogAsyncCompTrigger = null
    const { AsyncComponent: EditableCategoryDialogAsyncComp } =
      useAsyncComponent({
        component: () =>
          import(
            '@/components/business/NTEditableSystemConfigCategoryDialog/index.vue'
          ),
        wait: async () => {
          await new Promise((resolve) => {
            editableCategoryDialogAsyncCompTrigger = resolve
          })
        },
      })

    const handleShowEditableCategoryDialog = (categoryPids) => {
      editableCategoryDialogAsyncCompTrigger()
      setTimeout(() => {
        editableCategoryDialogAsyncCompRef.value.open({
          mode: categoryTreeMode.value,
          categoryPids,
          categoryTree: treeData.value,
        })
      }, 16)
    }

    const handleCategoryAddSuccess = () => {
      getCategoryList()
    }

    const handleCategoryEditSuccess = () => {
      getCategoryList()
    }

    return {
      editableTreeRef,
      treeData,
      treeIsLoading,
      handleDeleteCategory,
      handleSelectRow,
      handleOpenAddDialog,
      handleOpenEditDialog,
      editableCategoryDialogAsyncCompRef,
      EditableCategoryDialogAsyncComp,
      handleCategoryAddSuccess,
      handleCategoryEditSuccess,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.el-form-item) {
  &.is-error {
    .el-col-11:first-child {
      .el-input__wrapper {
        box-shadow: 0 0 0 1px
          var(--el-input-border-color, var(--el-border-color)) inset;
      }
    }
  }
}
.nt-editable-system-config-category-tree {
  padding: 10px 15px;

  .line {
    text-align: center;
  }
}
</style>
