import dict, { formatDictKeyToValue } from '@/settings/dict'

const dictPlugin = {
  install(app) {
    app.config.globalProperties.$dict = dict
    app.config.globalProperties.$dict.$formatDictKeyToValue =
      formatDictKeyToValue
  },
}
export default dictPlugin
