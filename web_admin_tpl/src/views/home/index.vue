<template>
  <div class="page-container page-container--bg">
    <!-- S 项目特性介绍信息 -->
    <el-card shadow="never" class="mb-[16px]">
      <div class="flex items-center">
        <img src="@/assets/images/avatar.png" class="avatar" />

        <div class="info">
          <p class="text-[18px] font-bold">
            您好
            {{ userInfo?.username }}，欢迎使用 {{ APP_TITLE }}
            {{ APP_VERSION }}， 一款开源免费、上手简单的企业网站搭建系统。
            <span style="color: #ff5722">专业团队维护，持续更新完善</span>
          </p>
          <p>
            包含： 菜单栏目管理、模板管理、信息内容管理、页面缓存管理、
            公共模板变量管理、系统配置等功能。
          </p>
        </div>
      </div>
    </el-card>
    <!-- S 项目特性介绍信息 -->

    <!-- S 统计信息 -->
    <el-row :gutter="16">
      <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8" class="mb-[16px]">
        <el-card shadow="never">
          <template #header>
            <span>访问量</span>
            <el-tag type="success" size="small" class="float-right">日</el-tag>
          </template>
          <NTEcharts ref="visitsChartRef" class="visits-chart-card"></NTEcharts>
        </el-card>
      </el-col>

      <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8" class="mb-[16px]">
        <el-card shadow="never">
          <template #header>
            <span>授权数</span>
            <el-tag type="warning" size="small" class="float-right">日</el-tag>
          </template>
          <NTEcharts ref="buyChartRef" class="buy-chart-card"></NTEcharts>
        </el-card>
      </el-col>

      <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8" class="mb-[16px]">
        <el-card shadow="never">
          <template #header>
            <span>商用授权与技术支持</span>
          </template>

          <div class="flex flex-col justify-center items-center">
            <img src="~@/assets/images/wechat_qrcode.png" class="w-[128px]" />
            <el-text>扫码添加微信</el-text>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <!-- E 统计信息 -->

    <!-- S 项目信息 -->
    <el-row :gutter="16">
      <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb-[16px]">
        <el-card shadow="never">
          <template #header>
            <span>项目信息</span>
            <el-tag size="small" class="float-right">
              当前版本：{{ APP_VERSION }}
            </el-tag>
          </template>
          <el-scrollbar>
            <el-descriptions :column="3" border>
              <el-descriptions-item>
                <template #label>vue</template>
                <el-tag type="success" size="small">^3.3.4</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">vue-router</span>
                </template>
                <el-tag type="success" size="small">^4.2.5</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>pinia</template>
                <el-tag type="success" size="small">^2.1.7</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>vite</template>
                <el-tag type="success" size="small">^4.3.9</el-tag>
              </el-descriptions-item>
              <el-descriptions-item :span="2">
                <template #label>
                  <span class="whitespace-nowrap">element-plus</span>
                </template>
                <el-tag type="success" size="small">^2.3.7</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">php</span>
                </template>
                <el-tag type="success" size="small">>=7.1.0</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">thinkphp</span>
                </template>
                <el-tag type="success" size="small">6.1.4</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">mysql</span>
                </template>
                <el-tag type="success" size="small">5.5.62</el-tag>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">源码获取</span>
                </template>
                <el-link
                  href="https://github.com/seablue002/stars"
                  target="_blank"
                >
                  <img
                    src="https://img.shields.io/github/stars/seablue002/stars?style=flat-square&logo=GitHub"
                    alt=""
                  />
                </el-link>
              </el-descriptions-item>
              <el-descriptions-item>
                <template #label>
                  <span class="whitespace-nowrap">视频教程</span>
                </template>
                <el-button size="small" plain>
                  <el-link
                    href="https://space.bilibili.com/255174376"
                    target="_blank"
                  >
                    bilibili（B站）
                  </el-link>
                </el-button>
              </el-descriptions-item>
            </el-descriptions>
          </el-scrollbar>
        </el-card>
      </el-col>
    </el-row>
    <!-- E 项目信息 -->

    <!-- S 快捷导航 -->
    <el-row :gutter="16">
      <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb-[16px]">
        <el-card shadow="never" class="fast-link">
          <template #header>
            <span>快捷导航</span>
          </template>

          <el-row :gutter="16">
            <el-col :span="6">
              <el-card shadow="hover">
                <el-link :underline="false" href="/content/info/list">
                  <el-icon><Reading /></el-icon>
                  信息管理
                </el-link>
              </el-card>
            </el-col>
            <el-col :span="6">
              <el-card shadow="hover">
                <el-link :underline="false" href="/tpl/list/list">
                  <el-icon><Document /></el-icon>
                  模板管理
                </el-link>
              </el-card>
            </el-col>
            <el-col :span="6">
              <el-card shadow="hover">
                <el-link :underline="false" href="/tpl_var/list">
                  <el-icon><Star /></el-icon>
                  模板变量管理
                </el-link>
              </el-card>
            </el-col>
            <el-col :span="6">
              <el-card shadow="hover">
                <el-link :underline="false" :href="API_HOST" target="_blank">
                  <el-icon><Monitor /></el-icon>
                  网站前台首页
                </el-link>
              </el-card>
            </el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
    <!-- E 快捷导航 -->

    <!-- S 系统信息 -->
    <el-row :gutter="16">
      <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
        <el-card shadow="hover">
          <template #header>
            <span>系统信息</span>
          </template>
          <table class="params-table">
            <tr v-for="(group, i) in systemInfo" :key="i" rows="2">
              <td v-for="(value, j) in group" :key="j">{{ value }}</td>
            </tr>
          </table>
        </el-card>
      </el-col>
    </el-row>
    <!-- E 系统信息 -->
  </div>
</template>
<script>
import { ref, onMounted, computed } from 'vue'
import * as echarts from 'echarts'
import { chunk, flatten } from 'lodash-es'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useUserStore from '@/store/modules/user'
import { APP_VERSION, APP_TITLE } from '@/settings/config/app'
import { API_HOST } from '@/settings/config/http'

export default {
  name: 'HomePage',
  setup() {
    const userStore = useUserStore()
    const { $api, $apiCode, $message } = useCurrentInstance()

    // 用户信息
    const userInfo = computed(() => userStore.userInfo)

    // 访问量
    const visitsChartRef = ref(null)
    onMounted(() => {
      visitsChartRef.value.init()

      const option = visitsData()

      visitsChartRef.value.setOption(option)
    })

    // 访问数据
    const visitsData = () => {
      const option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            label: {
              backgroundColor: '#6a7985',
            },
          },
        },
        grid: {
          top: '6px',
          left: '0',
          right: '15px',
          bottom: '3%',
          containLabel: true,
        },
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: [],
          },
        ],
        yAxis: [
          {
            type: 'value',
          },
        ],
        series: [
          {
            name: '访问量',
            type: 'line',
            smooth: true,
            itemStyle: {
              color: 'rgb(255, 70, 131)',
            },
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                {
                  offset: 0,
                  color: 'rgb(255, 158, 68)',
                },
                {
                  offset: 1,
                  color: 'rgb(255, 70, 131)',
                },
              ]),
            },
            data: [],
          },
        ],
      }

      // 近7天日期
      const dateArr = getDate()

      option.xAxis[0].data = dateArr.map((date) => {
        return date.join('/')
      })

      dateArr.forEach(() => {
        const num =
          1600 + Math.ceil(Math.random() * 600) * (Math.random() > 0.5 ? 1 : -1)
        option.series[0].data.push(num)
      })

      return option
    }

    // 获取最近days天的日期
    const getDate = (days = 7) => {
      const date = new Date()
      const dateArr = []
      date.setDate(date.getDate() + 1)
      for (let i = 0; i < days; i += 1) {
        date.setDate(date.getDate() - 1)
        const month = date.getMonth() + 1
        const day = date.getDate()
        dateArr.push([month, day])
      }
      return dateArr
    }

    // 授权数
    const buyChartRef = ref(null)
    onMounted(() => {
      buyChartRef.value.init()

      const option = buyData()

      buyChartRef.value.setOption(option)
    })

    // 购买数据
    const buyData = () => {
      const option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            label: {
              backgroundColor: '#6a7985',
            },
          },
        },
        grid: {
          top: '6px',
          left: '0',
          right: '15px',
          bottom: '3%',
          containLabel: true,
        },
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: [],
          },
        ],
        yAxis: [
          {
            type: 'value',
          },
        ],
        series: [
          {
            name: '授权数',
            type: 'line',
            smooth: true,
            itemStyle: {
              color: 'rgb(100, 188, 255)',
            },
            data: [],
          },
        ],
      }

      // 近7天日期
      const dateArr = getDate()

      option.xAxis[0].data = dateArr.map((date) => {
        return date.join('/')
      })

      dateArr.forEach(() => {
        const num =
          66 + Math.ceil(Math.random() * 10) * (Math.random() > 0.5 ? 1 : -1)
        option.series[0].data.push(num)
      })

      return option
    }

    // 系统信息
    const systemInfo = ref({})
    const getSystemInfo = async () => {
      const apiRes = await $api.common.systemInfoApi().catch((error) => {
        $message.error({
          message: error,
          duration: 3000,
        })
      })

      const { status, message, data } = apiRes.data

      if (status === $apiCode.SUCCESS && data) {
        systemInfo.value = chunk(flatten(Object.entries(data)), 4)
      } else {
        $message.error({
          message,
          duration: 3000,
        })
      }
    }
    getSystemInfo()

    return {
      API_HOST,
      APP_VERSION,
      APP_TITLE,
      userInfo,
      visitsChartRef,
      buyChartRef,
      systemInfo,
    }
  },
}
</script>
<style lang="scss" scoped>
.avatar {
  width: 60px;
  height: 60px;
  margin-right: 24px;
}

.visits-chart-card,
.buy-chart-card {
  height: 150px;
}

.fast-link {
  margin-bottom: 12px;
  .el-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }
  :deep(.el-col) {
    .el-link {
      display: flex;
      height: 118px;
      font-size: 16px;

      .el-link__inner {
        flex-direction: column;
        font-size: 14px;
      }

      .el-icon {
        font-size: 26px;
        margin-bottom: 6px;
      }
    }
  }
}

.params-table {
  border-collapse: collapse;
  width: 100%;
  td {
    padding: 9px 15px !important;
    overflow: hidden;
    font-size: 14px;
    text-overflow: ellipsis;
    white-space: nowrap;
    border: 1px solid #e6e6e6;
  }
  td:nth-child(odd) {
    width: 200px;
    text-align: right;
    background-color: #f7f7f7;
  }
}
</style>
