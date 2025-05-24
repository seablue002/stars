import cache from '@/utils/cache'

const cachePlugin = {
  install(app) {
    app.config.globalProperties.$cache = cache
  },
}
export default cachePlugin
