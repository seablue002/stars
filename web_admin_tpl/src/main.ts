import { createApp } from 'vue'
import 'normalize.css/normalize.css'
import ElementPlus from 'element-plus'
import locale from 'element-plus/lib/locale/lang/zh-cn'
import 'element-plus/dist/index.css'
import * as ElIcons from '@element-plus/icons-vue'
import 'font-awesome/css/font-awesome.min.css'
import App from './App.vue'
import router from './router'
import { asyncPermissionRoutes, matchPermissionRoutes } from '@/router/routes'
import store from './store'
import message from '@/utils/message'
import SPPlugins from '@/plugins'

import {
  API_SUCCESS_CODE
} from '@/config/http'

export const app = createApp(App)

// 注册自定义插件
app.use(SPPlugins)

// 全局注册Element Icons图标组件
for (const name in ElIcons) {
  app.component(name, (ElIcons as any)[name])
}

// 挂载全局Elmessage单例封装方法
app.config.globalProperties.message = message

app.config.globalProperties.HTTP_CONFIG = {
  API_SUCCESS_CODE
}

matchPermissionRoutes(asyncPermissionRoutes)

app.use(ElementPlus, { locale }).use(store).use(router).mount('#app')
