/*
 * 插件plugins主文件
 */
const pluginFiles = import.meta.glob('./modules/*.js', { eager: true })
export default function registPlugins(app) {
  Object.entries(pluginFiles).forEach(([, module]) => {
    app.use(module.default)
  })
}
