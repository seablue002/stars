/*
 * 权限控制指令
 */

import usePermission from '@/hooks/usePermission'
import { isArray } from '@/utils/helper/is'

// auth || role
const directiveGenerator = (type = 'auth') => ({
  mounted(el, binding) {
    const { hasPermission, hasRolePermission } = usePermission()
    const fn = type === 'auth' ? hasPermission : hasRolePermission

    const { value } = binding
    if (!value) return

    let ishas = false
    if (isArray(value)) {
      value.forEach((item) => {
        if (fn(item)) ishas = true
      })
      if (!ishas) el.parentNode?.removeChild(el)
    } else {
      ishas = fn(value)
    }

    if (!ishas) el.parentNode?.removeChild(el)
  },
})

export const authDirective = directiveGenerator('auth')
export const roleDirective = directiveGenerator('role')
