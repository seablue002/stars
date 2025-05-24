/*
 * element-plus Message单例二次封装，防止出现多个message提示
 */
import { ElMessage } from 'element-plus'
import { isString } from '@/utils/helper/is'

let messageInstance
function DoneMessage(options) {
  // 如果弹窗已存在先关闭
  if (messageInstance) {
    messageInstance.close()
  }

  messageInstance = ElMessage(options)
}
const messageTypeFn = ['success', 'warning', 'info', 'error']
messageTypeFn.forEach((type) => {
  DoneMessage[type] = (options) => {
    let newOptions = options
    if (isString(options)) {
      newOptions = {
        message: options,
      }
    }
    newOptions.type = type
    return DoneMessage(newOptions)
  }
})

export default DoneMessage
