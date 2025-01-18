/** 重置ElMessage，防止重复点击重复弹出message弹框 */
import { ElMessage } from 'element-plus'
let messageInstance: any = null
const mainMessage: any = function DoneMessage (options: {[key: string]: any}) {
  // 如果弹窗已存在先关闭
  if (messageInstance) {
    messageInstance.close()
  }

  messageInstance = ElMessage(options)
}
const arr = ['success', 'warning', 'info', 'error']
arr.forEach(function (type: string) {
  mainMessage[type] = function (options: any) {
    if (typeof options === 'string') {
      options = {
        message: options
      }
    }
    options.type = type
    return mainMessage(options)
  }
})

export default mainMessage
