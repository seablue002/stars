<!--
 * NTList组件
-->
<template>
  <div class="nt-list">
    <div class="nt-list__wrapper">
      <a
        v-for="(item, index) in list"
        :key="index"
        href="javascript:void(0)"
        class="nt-list__item"
        :class="{
          'is-active': currentIndex === getObjectAttrValue(item, options.key),
        }"
        :draggable="draggable"
        @click="handleSwitchItem(item)"
        @click.right="handleContextMenu($event, item)"
      >
        <slot name="item" :item="item" :index="index">
          {{ getObjectAttrValue(item, options.label) }}
        </slot>
      </a>
      <el-empty v-show="isEmpty" :image-size="60" description="暂无数据" />
    </div>
  </div>
</template>
<script>
import { ref, computed, nextTick } from 'vue'
import { get as getObjectAttrValue } from 'lodash-es'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'

export default {
  name: 'NTList',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    options: {
      type: Object,
      default: () => ({
        // 主键key字段
        key: 'id',
        // 展示内容字段
        label: 'name',
      }),
    },
    // item是否可以拖拽
    draggable: {
      type: Boolean,
      default: false,
    },
    beforeLeave: {
      type: Function,
      default: null,
    },
  },
  emits: ['switch', 'row-context-menu'],
  setup(props, { emit }) {
    const { $is } = useCurrentInstance()
    const { isFunction, isAsyncFunction } = $is

    const currentIndex = ref('')

    const isEmpty = computed(() => {
      return props.list.length === 0
    })

    // 切换item选择
    const handleSwitchItem = async (item) => {
      if (isFunction(props.beforeLeave)) {
        let leaveConfirm

        const index = props.list.findIndex((listItem) => {
          return (
            getObjectAttrValue(listItem, props.options.key) ===
            getObjectAttrValue(item, props.options.key)
          )
        })

        const value = getObjectAttrValue(props.list[index], props.options.key)

        if (isAsyncFunction(props.beforeLeave)) {
          await new Promise((resolve) => {
            props
              .beforeLeave(index, item, value)
              .then((status) => {
                leaveConfirm = status
                resolve()
              })
              .catch(() => {
                leaveConfirm = false
                resolve()
              })
          })
        } else {
          leaveConfirm = props.beforeLeave(index, item, value)
        }

        if (leaveConfirm === false) {
          return
        }

        // const index = props.list.findIndex((listItem) => {
        //   return (
        //     getObjectAttrValue(listItem, props.options.key) ===
        //     getObjectAttrValue(item, props.options.key)
        //   )
        // })

        if (index !== -1) {
          currentIndex.value = value
          emit('switch', currentIndex.value, props.list[index])
        }
      }
    }

    // 鼠标右键点击
    const handleContextMenu = (event, item) => {
      handleSwitchItem(item)
      nextTick(() => {
        emit('row-context-menu', event, currentIndex.value, item)
      })
    }

    return {
      getObjectAttrValue,
      currentIndex,
      isEmpty,
      handleSwitchItem,
      handleContextMenu,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-list {
  .nt-list__item {
    display: flex;
    align-items: center;
    padding: 4px 8px;
    border: 1px solid #f5f7fa;
    border-bottom: 0;
    transition: background 0.3s;
    &:hover,
    &.is-active {
      color: var(--el-color-primary);
      background: #f5f7fa;
    }
    &:last-child {
      border-bottom: 1px solid #f5f7fa;
    }
  }
}
</style>
