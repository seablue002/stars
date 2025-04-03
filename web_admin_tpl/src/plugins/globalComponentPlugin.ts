import { App } from 'vue'
import Tips from '@/components/Tips.vue'
const GlobalComponentPlugin = {
  install (app: App): void {
    app.component(Tips.name, Tips)
  }
}

export default GlobalComponentPlugin
