import { App } from 'vue'
import {
  formatDictLabel,
  formateDateStr,
  formatPicUrl
} from '@/utils'
const methods: { [key: string]: any } = {
  formatDictLabel,
  formateDateStr,
  formatPicUrl
}
const GlobalFunctionPlugin = {
  install (app: App): void {
    for (const attr in methods) {
      app.config.globalProperties[attr] = methods[attr]
    }
  }
}

export default GlobalFunctionPlugin
