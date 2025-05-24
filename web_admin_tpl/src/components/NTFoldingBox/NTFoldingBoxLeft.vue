<template>
  <div
    class="nt-folding-box-left"
    :class="{ 'nt-folding-box-left--folded': isFolded }"
  >
    <el-scrollbar v-show="!isFolded">
      <slot></slot>
    </el-scrollbar>

    <template v-if="isShowFoldExpandBtn">
      <a
        v-if="!isFolded"
        href="javascript:void(0)"
        class="folding-btn"
        @click="handleFold"
      >
        <i class="ri-arrow-left-double-line"></i>
      </a>
      <a
        v-if="isFolded"
        href="javascript:void(0)"
        class="expand-btn"
        @click="handleExpand"
      >
        <i class="ri-arrow-right-double-line"></i>
      </a>
    </template>
  </div>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'NTFoldingBoxLeft',
  props: {
    isShowFoldExpandBtn: {
      type: Boolean,
      default: true,
    },
    isFolded: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['update:isFolded'],
  setup(props, { emit }) {
    const handleFold = () => {
      emit('update:isFolded', true)
    }

    const handleExpand = () => {
      emit('update:isFolded', false)
    }

    return {
      handleFold,
      handleExpand,
    }
  },
})
</script>
<style lang="scss" scoped>
.nt-folding-box-left {
  position: relative;
  display: flex;
  background-color: #fff;

  .el-scrollbar {
    flex: 0 0 250px;
    overflow: hidden;
  }

  .folding-btn {
    display: none;
    right: 0;
    border-radius: 4px 0 0 4px;
  }

  .expand-btn {
    display: flex;
    right: -20px;
    border-radius: 0 4px 4px 0;
  }

  .folding-btn,
  .expand-btn {
    position: absolute;
    top: 50px;
    z-index: 999;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 35px;
    color: #666;
    background-color: #e9e9e9;

    &:hover {
      color: #333;
      background-color: #e0e0e0;
    }
  }

  &:hover {
    .folding-btn {
      display: flex;
    }
  }

  &--folded {
    flex: 0 0 0px;
  }
}
</style>
