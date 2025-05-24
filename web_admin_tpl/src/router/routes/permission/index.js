/*
 * 权限菜单主文件
 */
const routes = []
const routesFiles = import.meta.glob('./modules/*.js', { eager: true })

Object.entries(routesFiles).forEach((file) => {
  routes.push(file[1].default)
})

export default routes
