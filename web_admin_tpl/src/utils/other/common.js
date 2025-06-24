import router from '@/router'
import { rootRoute } from '@/router/routes/basic'
import permissionRoutes from '@/router/routes/permission'
import * as $is from '@/utils/helper/is'
import * as $tree from '@/utils/helper/tree'
import * as $array from '@/utils/helper/array'
import { cloneDeep } from 'lodash-es'
import { RESOURCE_PATH } from '@/settings/config/http'

// 生成最终权限菜单树tree，以及动态添加路由
export const formatAndAddPermissionRoutes = (
  authList,
  updateAblePermissionRoutesFullTree,
  updateAblePermissionButtons
) => {
  // 从store中的权限菜单，获取剔除children字段后的权限菜单tree
  const onlinePermissionList = []
  $tree.treeFlat(onlinePermissionList, authList, 0, ['children'], 'children')

  // 将本地所有菜单路由扁平化
  const permissionRoutesFlat = []
  $tree.treeFlat(
    permissionRoutesFlat,
    permissionRoutes,
    0,
    ['children'],
    'children'
  )

  // 根据用户权限菜单过滤出本地有权限访问的菜单数组
  const permissionRoutesMatched = permissionRoutesFlat.filter((node) => {
    const index = $array.indexOfObjInObjArrByKey(
      node,
      onlinePermissionList,
      'name'
    )

    if (index !== -1) {
      // 将匹配到的本地路由加上对应上线路由id,pid字段信息，为后续使用做准备
      const { id, pid, sort } = onlinePermissionList[index]
      node.id = id
      node.pid = pid
      node.sort = sort
    }

    return index !== -1
  })

  // 将根据用户权限菜单过滤出本地有权限访问的菜单数组转为tree
  const permissionRoutesMatchedTree = []
  $tree.getTree(
    permissionRoutesMatchedTree,
    permissionRoutesMatched,
    {
      id: 'id',
      pid: 'pid',
      path: 'path',
      name: 'name',
      redirect: 'redirect',
      meta: 'meta',
      component: 'component',
      sort: 'sort',
    },
    []
  )

  // 升序排序菜单
  permissionRoutesMatchedTree.sort((a, b) => {
    return a.sort - b.sort
  })

  $tree.recursionMachine(permissionRoutesMatchedTree, (node) => {
    if ($is.isArray(node.children)) {
      node.children.sort((a, b) => {
        return a.sort - b.sort
      })
    }
  })

  // 完整的可访问权限树，组装整颗可访问的权限菜单树
  const ablePermissionRoutesFullTree = cloneDeep(rootRoute)
  ablePermissionRoutesFullTree.children = [
    ...ablePermissionRoutesFullTree.children,
    ...(permissionRoutesMatchedTree || []),
  ]

  // 动态添加权限路由到路由router
  if (ablePermissionRoutesFullTree.children) {
    ablePermissionRoutesFullTree.children.forEach((route) => {
      router.addRoute('Root', route)
    })
  }

  // 保存权限菜单
  updateAblePermissionRoutesFullTree(ablePermissionRoutesFullTree)

  // 保存权限按钮
  const ablePermissionButtons = onlinePermissionList
    .filter((route) => {
      return route.type === 'button'
    })
    .map((route) => {
      return route.code
    })
  updateAblePermissionButtons(ablePermissionButtons)
}

// 返回图片等资源访问路径
export const formatPicUrl = (urlOrFile) => {
  let url = ''
  if (urlOrFile instanceof File) {
    const windowURL = window.URL || window.webkitURL
    url = windowURL.createObjectURL(urlOrFile)
  } else if (typeof urlOrFile === 'string') {
    url = RESOURCE_PATH + urlOrFile
  }
  return url
}

export const formateResourceUrl = (path) => {
  return `${RESOURCE_PATH}${path}`
}

export const unformatResourceUrl = (path) => {
  return path.replace(`${RESOURCE_PATH}`, '')
}

export const parseInvalidJson = (str) => {
  try {
    // 先尝试标准JSON解析
    return JSON.parse(str)
  } catch {
    // 自动修复常见格式问题
    const fixed = str
      .replace(/^(['"])|(['"])$/g, '') // 去除外层包裹的引号
      .replace(/([{,]\s*)([a-zA-Z0-9-]+?)(\s*:)/g, '$1"$2"$3') // 为键名添加双引号
      .replace(/'/g, '"') // 替换单引号为双引号
      .replace(/,(\s*[}\]])/g, '$1') // 移除尾部逗号
      .replace(/\\"/g, '"') // 处理转义引号

    return JSON.parse(fixed)
  }
}

// 判断字符串是否可以转换为数字
export function canConvertToNumber(str) {
  const numberPattern = /^[-+]?\d*(\.\d+)?$/
  return numberPattern.test(str)
}
