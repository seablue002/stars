<template>
  <div class="nt-folding-box flex">
    <NTFoldingBoxLeft
      v-if="isShowLeft"
      v-model:isFolded="isBoxLeftFolded"
      :style="leftStylesComputed"
    >
      <slot name="left"></slot>
    </NTFoldingBoxLeft>

    <NTFoldingBoxRight v-if="isShowRight" :style="rightStylesComputed">
      <slot name="right"></slot>
    </NTFoldingBoxRight>
  </div>
</template>
<script>
import { defineComponent, toRefs, computed, ref } from 'vue'

export default defineComponent({
  name: 'NTFoldingBox',
  props: {
    isShowLeft: {
      type: Boolean,
      default: true,
    },
    isShowRight: {
      type: Boolean,
      default: true,
    },
    gap: {
      type: String,
      default: '15px',
    },
    leftStyles: {
      type: Object,
      default: null,
    },
    centerStyles: {
      type: Object,
      default: null,
    },
    rightStyles: {
      type: Object,
      default: null,
    },
  },
  setup(props) {
    const { gap, leftStyles, rightStyles } = toRefs(props)

    const leftStylesComputed = computed(() => {
      let styles = {}

      if (!isBoxLeftFolded.value) {
        styles = {
          marginRight: gap.value,
        }
      }

      styles = {
        ...styles,
        ...leftStyles.value,
      }
      return styles
    })

    const isBoxLeftFolded = ref(false)

    const rightStylesComputed = computed(() => {
      return {
        ...rightStyles.value,
      }
    })

    return {
      leftStylesComputed,
      isBoxLeftFolded,
      rightStylesComputed,
    }
  },
})
</script>
<style lang="scss" scoped></style>
