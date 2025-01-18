import { App } from 'vue'
import Tips from '@/components/Tips.vue'
const GlobalComponentPlugin = {
  install<T> (app: App, options?: T): void {
    app.component(Tips.name, Tips)
  }
}

export default GlobalComponentPlugin
