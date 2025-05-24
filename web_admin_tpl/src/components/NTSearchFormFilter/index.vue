<!--
 * 列表搜索筛选过滤表单
-->
<template>
  <div class="nt-search-form-filter">
    <el-form v-bind="$attrs" ref="ntSearchFormFilterForm">
      <el-row>
        <slot :isFoldFormFilter="isFoldFormFilter"></slot>

        <NTSearchFormFilterItem
          :col="$attrs?.btnsCol"
          class="nt-search-form-filter__btns"
        >
          <el-form-item>
            <el-button type="primary" :loading="loading" @click="handleSearch">
              查 询
            </el-button>
            <el-button plain @click="handleReset">重 置</el-button>
            <el-button
              v-if="isShowFoldUnfoldBtn"
              link
              type="primary"
              @click="isFoldFormFilter = !isFoldFormFilter"
            >
              {{ isFoldFormFilter ? '展开' : '收起' }}
              <i v-show="isFoldFormFilter" class="ri-arrow-down-s-line"></i>
              <i v-show="!isFoldFormFilter" class="ri-arrow-up-s-line"></i>
            </el-button>
          </el-form-item>
        </NTSearchFormFilterItem>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { ref } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'

export default {
  name: 'NTSearchFormFilter',
  props: {
    isShowFoldUnfoldBtn: {
      type: Boolean,
      default: true,
    },
    // 父组件中传递来的搜索执行函数
    searchHandle: {
      type: Function,
      default: null,
    },
    // 父组件中传递来的重置执行函数
    resetHandle: {
      type: Function,
      default: null,
    },
    // 搜索时外部loadding状态值
    loading: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const {
      $is: { isFunction },
    } = useCurrentInstance()

    const ntSearchFormFilterForm = ref(null)

    // 是否折叠多余搜索条件项
    const isFoldFormFilter = ref(true)

    // 搜索
    const handleSearch = () => {
      if (isFunction(props?.searchHandle)) {
        props?.searchHandle()
      }
    }

    // 重置
    const handleReset = () => {
      if (isFunction(props?.resetHandle)) {
        props?.resetHandle()
      }
    }

    return {
      ntSearchFormFilterForm,
      isFoldFormFilter,
      handleSearch,
      handleReset,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-search-form-filter {
  padding: 24px 12px 12px;
  background: #fff;
}
</style>
