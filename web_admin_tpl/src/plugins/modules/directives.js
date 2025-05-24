/*
 * 指令注册
 */
import { authDirective } from '@/directives/permission'

const directivesPlugin = {
  install(app) {
    app.directive('PermissionAuth', authDirective)
  },
}
export default directivesPlugin
