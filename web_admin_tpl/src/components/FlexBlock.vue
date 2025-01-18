<template>
  <div class="flex-block" :class="[isUnfold ? 'flex-block--unfold' : 'flex-block--fold']">
    <div class="flex-block__left" :style="leftStyles">
      <el-scrollbar>
        <slot name="left"></slot>
      </el-scrollbar>
    </div>
    <div class="flex-block__center" :style="centerStyles">
      <el-tooltip
        effect="light"
        :content="isUnfold ? '点击折叠' : '点击展开'"
        placement="right-start"
      >
        <el-icon color="#ffffff" @click="handleClick">
          <el-icon v-show="isUnfold"><arrow-left-bold /></el-icon>
          <el-icon v-show="!isUnfold"><arrow-right-bold /></el-icon>
        </el-icon>
      </el-tooltip>
    </div>
    <div class="flex-block__right" :style="rightStyles">
      <el-scrollbar>
        <slot name="right"></slot>
      </el-scrollbar>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import { ElScrollbar } from 'element-plus'

export default defineComponent({
  components: {
    ElScrollbar
  },
  emits: ['flex'],
  props: {
    leftStyles: {
      type: Object,
      default: null
    },
    centerStyles: {
      type: Object,
      default: null
    },
    rightStyles: {
      type: Object,
      default: null
    }
  },
  setup (props, { emit }) {
    // 是否展开态
    const isUnfold = ref<boolean>(true)
    let element: HTMLElement

    onMounted(() => {
      element = document.querySelector('.flex-block') as HTMLElement
    })

    const handleClick = () => {
      isUnfold.value = !isUnfold.value
      // 动态调整--flex-block-left-w值
      if (isUnfold.value === true) {
        // 展开态
        element && element.style.setProperty('--flex-block-left-w', '250px')
      } else {
        // 折叠态
        element && element.style.setProperty('--flex-block-left-w', '0px')
      }
      emit('flex')
    }

    return {
      isUnfold,
      handleClick
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable';
.flex-block {
  --flex-block-left-w: 250px;
  $flexBlockRightW: calc(100vw - var(--side-bar-w) - 20px - var(--flex-block-left-w));
  position: relative;
  display: flex;
  &--fold {
    .flex-block__left {
      display: none;
      flex-basis: 0;
    }
    .flex-block__center {
      background: #fff;
    }
  }
  &__left {
    // height: calc(100vh - 50px - 10px);
    min-height: calc(100vh - 50px - 10px);
    flex-basis: var(--flex-block-left-w);
    width: var(--flex-block-left-w);
    min-width: var(--flex-block-left-w);
    box-sizing: border-box;
    background: #fff;
    border-radius: 4px;
  }
  &__center {
    display: flex;
    flex-direction: column;
    justify-content: center;
    :deep(.el-icon) {
      font-size: 12px;
      padding: 20px 0;
      background: $mainThemeColor;
      cursor: pointer;
      border-radius: 20px;
      &:hover {
        background: $mainThemeColor;
      }
    }
    border-radius: 4px;
  }
  &__right {
    flex: 1;
    width: $flexBlockRightW;
    max-width: $flexBlockRightW;
    box-sizing: border-box;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
  }
}
</style>
