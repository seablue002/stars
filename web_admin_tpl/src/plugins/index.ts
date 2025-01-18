import { App } from 'vue'
// 全局自定义组件
import GlobalComponentPlugin from './globalComponentPlugin'

// 全局方法
import GlobalFunctionPlugin from './globalFunctionPlugin'

const SPPlugins = {
  install (app: App): void {
    app.use(GlobalComponentPlugin)
    app.use(GlobalFunctionPlugin)
  }
}

export default SPPlugins
