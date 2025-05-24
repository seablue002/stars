<template>
  <div v-for="(node, index) in treeData" :key="index" class="nt-line-tree-item">
    <template v-if="node.children?.length">
      <div class="node">
        <img class="node-icon" src="~@/assets/images/folder.svg" />
        <span>{{ node.name }}</span>
        <el-icon v-if="node.open" @click="node.open = false"
          ><Remove
        /></el-icon>
        <el-icon v-else @click="node.open = true"><CirclePlus /></el-icon>
      </div>
      <div
        v-show="node.open"
        class="node-children"
        :class="{ 'node-children-least-two': node.children?.length > 1 }"
      >
        <NTLineTreeItem :treeData="node.children"></NTLineTreeItem>
      </div>
    </template>
    <template v-else>
      <div class="leaf">
        <img class="node-icon" src="~@/assets/images/tag-gray.svg" />
        <span>{{ node.name }}</span>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  name: 'NTLineTreeItem',
  props: {
    treeData: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
<style lang="scss">
.nt-line-tree-item {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 10px;
  user-select: none;
  + .nt-line-tree-item {
    display: flex;
    width: 100%;
  }
  .node-children {
    &.node-children-least-two {
      .nt-line-tree-item {
        position: relative;
        &::before {
          position: absolute;
          left: -70px;
          top: 16px;
          content: '';
          display: block;
          width: 2px;
          height: calc(100% + 10px);
          background-color: #dfe4e9;
        }

        &:last-child {
          &::before {
            content: none;
          }
        }
      }
    }
    .nt-line-tree-item {
      .node,
      .leaf {
        position: relative;
        &::before {
          position: absolute;
          left: -70px;
          content: '';
          display: block;
          width: 68px;
          height: 2px;
          background-color: #dfe4e9;
        }
      }
    }
  }
}
.node {
  position: relative;
  display: flex;
  align-items: center;
  font-size: 16px;
  height: 32px;
  padding: 0 20px 0 8px;
  border: 2px solid #dfe4e9;
  border-radius: 4px;
  margin-right: 68px;
  box-sizing: border-box;
  .node-icon {
    width: 1.5em;
    height: auto;
    margin-right: 4px;
  }

  .el-icon {
    position: absolute;
    right: -9px;
    z-index: 1;
    color: #1890ff;
    background-color: #fff;
    border-radius: 50%;
    cursor: pointer;
  }
}

.leaf {
  display: flex;
  align-items: center;
  height: 32px;
  font-size: 16px;
  // margin-bottom: 10px;
  .node-icon {
    width: 1.2em;
    height: auto;
    margin-right: 2px;
  }
}
</style>
