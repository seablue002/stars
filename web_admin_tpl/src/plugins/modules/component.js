/*
 * 全局组件注册
 */
const components = {}
const componentsFiles = import.meta.glob('@/components/**/*.vue', {
  eager: true,
})

Object.entries(componentsFiles).forEach(([, module]) => {
  components[module?.default?.name] = module?.default
})

const globComponentPlugin = {
  install(app) {
    Object.entries(components).forEach(([, module]) => {
      app.component(module.name, module)
    })
  },
}
export default globComponentPlugin
