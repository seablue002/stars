<template>
  <div class="dashboard PAGE-MAIN-CONTENT">
    <el-row class="welcome">
      <el-col>
        <el-card shadow="never">
          <div>欢迎使用stars繁星CMS~</div>
          <br/>
          <div>
            当前版本: 开源版 {{ appVersion }} &nbsp;
            <a href="https://github.com/seablue002/stars" target="_blank">github仓库地址： <el-tag type="info">https://github.com/seablue002/stars</el-tag></a>&nbsp;
            <a href="https://gitee.com/seablue002/stars" target="_blank">gitee仓库地址： <el-tag type="info">https://gitee.com/seablue002/stars</el-tag></a>&nbsp;
            <a href="https://space.bilibili.com/255174376" target="_blank">免费视频教程： <el-tag type="info">https://space.bilibili.com/255174376</el-tag></a>
          </div>
          <br/>
          <div>技术支持与商用授权请联系</div>
          <el-image
            style="width: 128px; height: 128px"
            :src="wechat"
            :zoom-rate="1.2"
            :max-scale="7"
            :min-scale="0.2"
            :preview-src-list="[wechat]"
            show-progress
            :initial-index="4"
            fit="cover"
          />
        </el-card>
      </el-col>
    </el-row>

    <el-card shadow="hover" class="fast-link">
      <template #header>
        <span>快捷导航</span>
      </template>

      <el-row :gutter="16">
        <el-col :span="6">
          <el-card shadow="hover">
            <el-link :underline="false" href="#/content/info/list">
              <el-icon><Reading /></el-icon>
              信息管理
            </el-link>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card shadow="hover">
            <el-link :underline="false" href="#/tpl/list/list">
              <el-icon><Document /></el-icon>
              模板管理
            </el-link>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card shadow="hover">
            <el-link :underline="false" href="#/tpl_var/list">
              <el-icon><Star /></el-icon>
              模板变量管理
            </el-link>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card shadow="hover">
            <el-link :underline="false" :href="API_HOST">
              <el-icon><Monitor /></el-icon>
              网站前台首页
            </el-link>
          </el-card>
        </el-col>
      </el-row>
    </el-card>

    <el-row>
      <el-col>
        <el-card shadow="hover">
          <template #header>
            <i class="ri-functions"></i>
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
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance } from 'vue'
import { chunk, flatten } from 'lodash'
import { API_HOST, HTTP_CONFIG } from '@/config/http'
import { APP_VERSION } from '@/config/app'

import { systemInfoApi } from '@/api/common'

const wechat = require('@/assets/images/wechat.png')

export default defineComponent({
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    const systemInfo = ref({})
    const getSystemInfo = async () => {
      const { status, data, message } = await systemInfoApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        systemInfo.value = chunk(flatten(Object.entries(data)), 4)
      } else {
        proxy.message({
          type: 'warning',
          message,
          duration: 3000
        })
      }
    }
    getSystemInfo()
    return {
      appVersion: APP_VERSION,
      systemInfo,
      wechat,
      API_HOST
    }
  }
})
</script>
<style lang="scss" scoped>
.dashboard {
  .welcome {
    margin-bottom: 12px;
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
  .tool-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    [class^='ri-'] {
      font-size: 22px;
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
}
</style>
