import { createApp } from 'vue'
import { useEventBus } from '@vueuse/core'
import ElementPlus from 'element-plus'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import registPlugins from '@/plugins'
import registRouterGuards from '@/router/guards'
import router, { registRouter } from '@/router'
import { registStore } from '@/store'
import 'normalize.css/normalize.css'
import 'element-plus/dist/index.css'
import locale from 'element-plus/lib/locale/lang/zh-cn'
import 'remixicon/fonts/remixicon.css'
import App from '@/App.vue'
import addPermissionRoutesMiddleWare from '@/middleware/addPermissionRoutesMiddleWare'
import mountCacheDataMiddleWare from '@/middleware/mountCacheDataMiddleWare'

async function bootstrap() {
  const app = createApp(App)

  // 注册UI组件库
  app.use(ElementPlus, { locale })
  Object.keys(ElementPlusIconsVue).forEach((key) => {
    const component = ElementPlusIconsVue[key]
    app.component(key, component)
  })

  // 注册store状态管理
  registStore(app)

  // 插件plugins注册
  registPlugins(app)

  // 初始化路由与相关必须的用户数据
  await mountCacheDataMiddleWare(app)
  await addPermissionRoutesMiddleWare()

  // 注册路由与路由守卫
  registRouter(app)
  registRouterGuards(router)

  // 事件总线
  const ntVisitedTagsBus = useEventBus('ntVisitedTags')
  const ntVisitedTagsSwitchBus = useEventBus('ntVisitedTagsSwitch')
  window.ntVisitedTagsSwitchBus = ntVisitedTagsSwitchBus
  window.ntVisitedTagsBus = ntVisitedTagsBus
  app.config.globalProperties.$bus = {
    ntVisitedTagsBus,
    ntVisitedTagsSwitchBus,
  }

  app.mount('#app')
}

bootstrap()
