<template>
  <div class="tree-panel">
    <div class="tree-panel__content">
      <el-input
        v-model="filterText"
        prefix-icon="Search"
        clearable
        placeholder="请输入关键词"
        class="mb-[15px]"
      />
      <el-tree
        ref="elTreeRef"
        :data="dataSource.data"
        :node-key="nodePrimaryKey"
        default-expand-all
        :expand-on-click-node="false"
        :show-checkbox="isShowCheckBox"
        :props="{ class: selectedNodeClassName, ...nodeKeyAlias }"
        :default-checked-keys="defaultCheckedKey"
        :filter-node-method="filterNode"
        empty-text="暂无栏目"
      >
        <template #default="{ node, data }">
          <span
            class="tree-node h-full"
            @mouseover.stop="handleDBLSelect(data)"
            @mouseout="handleCancelSelect"
            @click="handleSelect(data)"
          >
            <div class="before-ele"></div>
            <img
              v-if="data.children.length > 0"
              class="node-icon"
              src="~@/assets/images/folder.svg"
            />
            <img
              v-if="data.children.length === 0"
              class="node-icon"
              src="~@/assets/images/tag-gray.svg"
            />
            <span v-show="!isInEdit(data)" :title="node.label">{{
              node.label
            }}</span>
            <a
              v-show="isInEdit(data)"
              class="tree-node__opts h-full flex items-center"
            >
              <span :title="node.label" class="node-name">{{
                node.label
              }}</span>

              <span class="ml-[10px] flex justify-end items-center flex-1">
                <el-icon
                  title="添加子分类"
                  @click.stop="handleAddCategory(node, data)"
                  ><Plus
                /></el-icon>

                <el-icon
                  v-if="data.pid !== ROOT_NODE_PID"
                  title="编辑"
                  @click.stop="handleEditCategory(node, data)"
                  ><Edit
                /></el-icon>

                <el-icon
                  v-if="data.pid !== ROOT_NODE_PID"
                  title="删除"
                  class="tree-node__icon"
                  @click.stop="handleDelCategory(node, data)"
                  ><Delete
                /></el-icon>
              </span>
            </a>
          </span>
        </template>
      </el-tree>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, ref, watch } from 'vue'
import { cloneDeep } from 'lodash-es'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'

export const ROOT_NODE_PID = '#ROOT_NODE_PID'
export default defineComponent({
  name: 'NTEditableTree',
  props: {
    mode: {
      type: String,
      default: '',
    },
    treeData: {
      type: Array,
      default: () => {
        return []
      },
    },
    nodePrimaryKey: {
      type: String,
      default: 'id',
    },
    nodeKeyAlias: {
      type: Object,
      default: () => {
        return {}
      },
    },
    isShowCheckBox: {
      type: Boolean,
      default: false,
    },
    defaultCheckedKey: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['delete-category', 'select-row', 'add-category', 'edit-category'],
  setup(props, { emit }) {
    const { $confirm } = useCurrentInstance()

    const elTreeRef = ref(null)
    // 是否可编辑态
    const isEditable = ref(true)
    const selectedRowId = ref(null)

    const dataSource = reactive({
      data: [],
    })

    watch(
      () => props.treeData,
      (treeData) => {
        dataSource.data = cloneDeep(treeData)
      },
      {
        immediate: true,
        deep: true,
      }
    )

    // Tree节点搜索过滤
    const filterText = ref('')
    const filterNode = (value, data) => {
      if (!value) return true
      return data.label.includes(value)
    }
    watch(filterText, (val) => {
      elTreeRef.value.filter(val)
    })

    const isInEdit = (data) => {
      return isEditable.value && selectedRowId.value === data.id
    }

    const selectedNodeClassName = (data) => {
      if (selectedRowId.value === data.id) {
        return 'is-selected'
      }
      return null
    }

    // 删除
    const handleDelCategory = (node, data) => {
      const selectedId = selectedRowId.value
      $confirm('确定要删除吗？', '提示', {
        type: 'warning',
      })
        .then(() => {
          emit('delete-category', {
            node,
            data,
            selectedId,
          })
        })
        .catch(() => {})
    }

    // 选择
    const handleDBLSelect = (data) => {
      selectedRowId.value = data.id
    }
    const handleSelect = (data) => {
      emit('select-row', data)
    }

    // 取消选择
    const handleCancelSelect = () => {
      selectedRowId.value = null
    }

    // 添加分类
    const handleAddCategory = (node, data) => {
      emit('add-category', { node, data })
    }

    // 编辑分类
    const handleEditCategory = (node, data) => {
      emit('edit-category', { node, data })
    }

    return {
      elTreeRef,
      ROOT_NODE_PID,
      isEditable,
      selectedRowId,
      isInEdit,
      selectedNodeClassName,
      dataSource,
      filterText,
      filterNode,
      handleDelCategory,
      handleDBLSelect,
      handleSelect,
      handleCancelSelect,
      handleAddCategory,
      handleEditCategory,
    }
  },
})
</script>
<style lang="scss" scoped>
@import '@/assets/style/variable.scss';
.tree-panel {
  $optsH: 50px;
  height: 100%;
  &__content {
    padding: 10px 0;
    box-sizing: border-box;
    .el-tree {
      margin-top: 10px;
      margin-bottom: 10px;
    }
    :deep(.el-tree-node) {
      > .el-tree-node__content {
        height: 30px;
        position: relative;
        > .tree-node {
          .before-ele {
            content: '';
            display: inline-block;
            position: absolute;
            left: 0;
            height: 100%;
            width: 100%;
          }
        }
      }
      .is-selected {
        > .el-tree-node__content {
          padding-left: 15px !important;
          > .is-leaf {
            display: none;
          }
        }
      }
      &.is-current {
        > .el-tree-node__content {
          background: var(--el-color-primary-light-9) !important;
        }
      }
      .el-tree-node__content {
        box-sizing: border-box;
        .el-tree-node__expand-icon {
          position: relative;
          z-index: 1;
        }
      }
      .tree-node {
        display: flex;
        align-items: center;
        width: 100%;
        font-size: 15px;
        text-overflow: ellipsis;
        overflow: hidden;
        .node-icon {
          width: 1.4em;
          height: auto;
          margin-right: 2px;
        }
        & > span {
          overflow: hidden;
          text-overflow: ellipsis;
        }
        &__opts {
          width: calc(100% - 21px - 15px);
          .node-name {
            display: inline-block;
            width: calc(100% - 54px);
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
          }
          .el-icon {
            font-size: 14px;
            margin: 0 3px;
            &:hover {
              color: #1890ff;
              transform: scale(1.3);
            }
          }
        }
        &___icon {
          display: flex;
          align-items: center;
          justify-content: center;
        }
      }
    }
  }
  &__opts {
    display: flex;
    align-items: center;
    height: $optsH;
    margin: 0 4px;
    border-top: 1px solid #e4e7ed;
    box-sizing: border-box;
  }
}
</style>
