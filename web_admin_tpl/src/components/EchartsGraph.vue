<template>
  <div :id="echartsID" class="echarts-graph" :style="styles"></div>
</template>
<script lang="ts">
import { computed, defineComponent, onActivated, onBeforeUnmount, onMounted, reactive, watch } from 'vue'
import * as echarts from 'echarts'
export default defineComponent({
  props: {
    styles: {
      type: Object,
      default: () => ({
        width: '100%',
        height: '350px'
      })
    },
    optionsConf: {
      type: Object,
      default: () => ({})
    }
  },
  setup (props) {
    const echartsGraph = reactive<any>({ chart: null })

    const echartsID = computed(() => {
      return `echartsGraph${new Date().getTime()}${Math.ceil(Math.random() * 1000)}`
    })

    // 监听optionsConf数据变化，实时调整图表
    watch(() => props.optionsConf, () => {
      init()
    }, {
      deep: true
    })

    onMounted(() => {
      init()
      window.addEventListener('resize', resize)
    })

    onActivated(() => {
      resize()
    })

    onBeforeUnmount(() => {
      window.removeEventListener('resize', resize)
      echartsGraph.chart.dispose()
      echartsGraph.chart = null
    })

    // 初始化echart图表
    const init = () => {
      echartsGraph.chart && echartsGraph.chart.dispose()
      const chart = echarts.init((document.getElementById(echartsID.value) as HTMLElement))
      chart.setOption(props.optionsConf, true)
      echartsGraph.chart = chart
    }

    // 动态调整图表尺寸大小
    const resize = () => {
      echartsGraph.chart.resize()
    }
    return {
      echartsGraph,
      echartsID
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
