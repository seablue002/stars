<template>
  <div class="tree-panel">
    <div class="tree-panel__content">
      <el-input v-model="filterText" prefix-icon="Search" clearable placeholder="请输入关键词" />
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
      >
        <template #default="{ node, data }">
          <span class="tree-node" @mouseover.stop="handleDBLSelect(data)" @mouseout="handleCancelSelect" @click="handleSelect(data)">
            <div class="before-ele"></div>
            <img v-if="data.children.length > 0" class="node-icon" src="~@/assets/images/folder.svg">
            <!-- <img v-if="data.children.length === 0" class="node-icon" src="~@/assets/images/file.svg"> -->
            <!-- <img v-if="data.children.length > 0" class="node-icon" src="~@/assets/images/folder-gray.svg"> -->
            <img v-if="data.children.length === 0" class="node-icon" src="~@/assets/images/tag-gray.svg">
            <span v-show="!isInEdit(data)" :title="node.label">{{ node.label }}</span>
            <a class="tree-node__opts" v-show="isInEdit(data)">
            <!-- <a class="tree-node__opts"> -->
              <input
                v-if="data.pid !== ROOT_NODE_PID && isInEdit(data)"
                class="label-name"
                :title="node.label"
                v-model="data.label"
                @change="handleLabelNameEdit(node, data)"
                @keyup.enter="handleLabelNameKeyupEnter($event)"
                @click.stop
              />
              <span v-else :title="node.label">{{ node.label }}</span>

              <el-icon @click.stop="handleAddCategory(node, data)" title="添加子分类"><Plus /></el-icon>

              <el-icon v-if="data.pid !== ROOT_NODE_PID" @click.stop="handleEditCategory(node, data)" title="编辑"><Edit /></el-icon>

              <span v-show="data.pid !== ROOT_NODE_PID">
              <!-- <span> -->
                <el-popconfirm
                  title="确定删除吗?"
                  confirm-button-text="确定删除"
                  cancel-button-text="取消"
                  @confirm="handleDelConfirm(node, data)"
                >
                  <template #reference>
                    <span title="删除" class="tree-node__icon" @click.stop><el-icon><close /></el-icon></span>
                  </template>
                </el-popconfirm>
              </span>
            </a>
          </span>
        </template>
      </el-tree>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType, reactive, ref, watch } from 'vue'
import type { ElTree } from 'element-plus'
import type Node from 'element-plus/es/components/tree/src/model/node'
import _ from 'lodash'

// tree 通用结构字段interface
export interface TreeProps {
  id: string,
  label: string,
  pid: string | null,
  pids?: string,
  children?: TreeProps[]
}
// 原始待处理数据字段与通用结构字段对照表
export interface TreeDataKeyMapProps {
  id: string,
  label: string,
  pid: string,
  pids?: string,
  children?: string
}

export const ROOT_NODE_PID = '#ROOT_NODE_PID'
export default defineComponent({
  emits: [
    'label-name-change',
    'label-name-add',
    'hide-add-category-popover',
    'delete-category',
    'select-row',
    'add-category',
    'edit-category'
  ],
  props: {
    mode: {
      type: String,
      default: ''
    },
    treeData: {
      type: Array as PropType<TreeProps[]>,
      default: () => {
        return []
      }
    },
    nodePrimaryKey: {
      type: String,
      default: 'id'
    },
    nodeKeyAlias: {
      type: Object,
      default: () => {
        return {}
      }
    },
    isShowCheckBox: {
      type: Boolean,
      default: false
    },
    defaultCheckedKey: {
      type: Array,
      default: () => []
    }
  },
  setup (props, { emit }) {
    const elTreeRef = ref<InstanceType<typeof ElTree>>()
    // 是否可编辑态
    const isEditable = ref<boolean>(true)
    const selectedRowId = ref<string|null>(null)

    const dataSource = reactive<{ data: TreeProps[]}>({
      data: []
    })

    watch(() => props.treeData, (treeData) => {
      dataSource.data = _.cloneDeep(treeData)
    }, {
      immediate: true,
      deep: true
    })

    // Tree节点搜索过滤
    const filterText = ref('')
    const filterNode = (value: string, data: TreeProps) => {
      if (!value) return true
      return data.label.includes(value)
    }
    watch(filterText, (val) => {
      /* eslint-disable */
      elTreeRef.value!.filter(val)
    })

    const isInEdit = (data: TreeProps) => {
      return isEditable.value && (selectedRowId.value === data.id)
    }

    const selectedNodeClassName = (data: TreeProps) => {
      if (selectedRowId.value === data.id) {
        return 'is-selected'
      }
      return null
    }

    // 添加
    const addCategoryLabelName = ref('')
    const addCategoryLabelNamePopoverIsShow = ref(false)

    const handleAddCategoryLabelName = () => {
      emit('label-name-add', { label: addCategoryLabelName.value, pid: selectedRowId.value })
    }

    // 删除
    const handleDelConfirm = (node: Node, data: TreeProps) => {
      emit('delete-category', { node, data })
    }

    // 选择
    const handleDBLSelect = (data: TreeProps) => {
      selectedRowId.value = data.id
    }
    const handleSelect = (data: TreeProps) => {
      emit('select-row', data)
    }

    // 取消选择
    const handleCancelSelect = () => {
      selectedRowId.value = null
    }

    const handleIsEditable = () => {
      isEditable.value = !isEditable.value
      selectedRowId.value = null
    }

    // 编辑
    const handleLabelNameEdit = (node: Node, data: TreeProps) => {
      emit('label-name-change', { node, data })
    }

    const handleLabelNameKeyupEnter = (e: Event) => {
      e.target && (e.target as HTMLInputElement).blur()
    }

    // 添加分类
    const handleAddCategory = (node: Node, data: TreeProps) => {
      emit('add-category', { node, data })
    }

    // 编辑分类
    const handleEditCategory = (node: Node, data: TreeProps) => {
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
      addCategoryLabelNamePopoverIsShow,
      handleAddCategoryLabelName,
      handleDelConfirm,
      handleDBLSelect,
      handleSelect,
      handleCancelSelect,
      handleIsEditable,
      handleLabelNameEdit,
      handleLabelNameKeyupEnter,
      handleAddCategory,
      handleEditCategory
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
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
      >.el-tree-node__content {
        position: relative;
        >.tree-node {
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
        >.el-tree-node__content {
          padding-left: 0 !important;
          >.is-leaf {
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
          width: 1.2em;
          height: auto;
          margin-right: 2px;
        }
        &>span {
          overflow: hidden;
          text-overflow: ellipsis;
        }
        &__opts {
          .label-name {
            width: 6em;
            line-height: 22px;
            outline: 0;
            border: 1px solid #DCDFE6;
            margin-right: 4px;
            &:hover {
              border: 1px solid #C0C4CC;
            }
            &:focus {
              border: 1px solid $mainThemeColor;
            }
          }
          .el-icon {
            padding: 2px;
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

    :deep(.el-popup) {
      span.el-popper__arrow::after {
        content: '';
        display: block;
        height: 30px;
        width: 170px;
        background: red;
        position: absolute;
        left: 50%;
        transform: translate(-50%, -15px);
        z-index: -1;
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
