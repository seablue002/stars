<template>
  <div class="nt-echarts">
    <div ref="chartRef" class="chart"></div>
  </div>
</template>
<script>
import { ref, onUnmounted } from 'vue'
import * as echarts from 'echarts'

export default {
  name: 'NTEcharts',
  setup() {
    const chartRef = ref(null)

    // vue中使用echarts，chart如果为响应式对象会导致tooltip无法显示
    let chart = null

    const init = () => {
      chart = echarts.init(chartRef.value, 'purple-passion')

      // 监听窗口变化，调整chart布局
      window.addEventListener('resize', chart.resize)
    }

    const setOption = (option) => {
      chart.setOption(option)
    }

    onUnmounted(() => {
      // 移除监听窗口变化
      window.removeEventListener('resize', chart.resize)

      // 销毁chart
      chart.value?.dispose()
    })

    return {
      chartRef,
      init,
      setOption,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-echarts {
  width: 100%;
  height: 100%;
  .chart {
    width: 100%;
    height: 100%;
  }
}
</style>
