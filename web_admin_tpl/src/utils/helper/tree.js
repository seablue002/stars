/*
 * tree相关操作函数集合
 */
import { isArray } from './is'

/**
 * tree递归遍历处理器
 * @param {Object[]} arrTree 需要进行遍历处理的tree
 * @param {Function} cb 每个递归回调处理函数
 * @param {String} children 子节点key
 */
export const recursionMachine = (arrTree, cb, children = 'children') => {
  if (isArray(arrTree)) {
    for (let i = 0; i < arrTree.length; i += 1) {
      cb(arrTree[i])
      if (arrTree[i][children] && arrTree[i][children].length) {
        recursionMachine(arrTree[i][children], cb, children)
      }
    }
  }
}

/**
 * 查找节点在整颗tree中的访问路径
 * @param {Object} root 当前遍历中的根节点
 * @param {String|Number} matchValue 目标值
 * @param {String} matchKey 匹配key
 * @param {String} children 子节点key
 * @param {Array} path 存放访问路径数组
 * @returns Array 访问路径
 */
export const findNodePath = (
  root,
  matchValue,
  matchKey = 'id',
  children = 'children',
  path = []
) => {
  // 检查目标是否是当前的节点
  if (root[matchKey] === matchValue) {
    return path
  }

  // 遍历子节点
  if (root[children]) {
    for (let i = 0; i < root[children].length; i += 1) {
      const childPath = findNodePath(
        root[children][i],
        matchValue,
        matchKey,
        children,
        path.concat(i)
      )

      // 如果在子节点中找到目标，返回路径
      if (childPath) {
        return childPath
      }
    }
  }

  // 如果没有找到，返回null
  return null
}

/**
 * 树结构扁平化
 * @param {Array} resultArr 最终结果数组
 * @param {Object[]} arrTree 待处理的tree数组
 * @param {Number} level 所处层级
 * @param {String[]} deleteAttrsArr 需要删除的attr属性
 * @param {String} children 子节点
 */
export const treeFlat = (
  resultArr,
  arrTree,
  level = 0,
  deleteAttrsArr = [],
  children = 'children'
) => {
  if (isArray(arrTree)) {
    for (let i = 0; i < arrTree.length; i += 1) {
      const tempCurDataItem = {}

      Object.keys(arrTree[i]).forEach((attr) => {
        if (!deleteAttrsArr.includes(attr)) {
          tempCurDataItem[attr] = arrTree[i][attr]
        }
      })

      tempCurDataItem.level = level
      resultArr.push(tempCurDataItem)
      if (isArray(arrTree[i][children])) {
        const idx = level + 1
        treeFlat(resultArr, arrTree[i][children], idx, deleteAttrsArr, children)
      }
    }
  }
}

/**
 * 将树结构扁平化数组还原为树结构
 * @param {Array} resultArr
 * @param {Array} originData
 * @param {Object} keyMaps
 * @param {Array} pids
 * @param {Number} pid
 */
export const getTree = (
  resultArr,
  originData,
  keyMaps = {},
  pids = [],
  pid = 0
) => {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i += 1) {
      if (originData[i].pid === pid) {
        if (!pids.includes(pid)) {
          pids.push(pid)
        }
        const mapKeys = Object.keys(keyMaps)
        const mapVals = Object.values(keyMaps)

        const temp = {
          pids: pids.join(','),
        }
        for (let j = 0; j < mapKeys.length; j += 1) {
          temp[mapKeys[j]] = originData[i][mapVals[j]]
        }
        temp.children = []
        resultArr.push(temp)

        getTree(temp.children, originData, keyMaps, pids, temp[mapKeys[0]])
      }
    }
  }
}

/**
 * 将数组整理为tree
 * @param {Array} resultArr 结果数组
 * @param {Array} originData 数据源
 * @param {Object} treeDataKeyMap 匹配key
 * @param {Number} pid 当前递归遍历父级id
 */
export const formateTree = (
  resultArr,
  originData,
  treeDataKeyMap = {
    id: 'id',
    pid: 'pid',
    label: 'label',
    children: 'children',
  },
  pid = '#ROOT_NODE_PID'
) => {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i += 1) {
      if (String(originData[i][treeDataKeyMap.pid]) === pid) {
        const temp = {}

        Object.keys(treeDataKeyMap).forEach((attr) => {
          if (attr !== 'children') {
            temp[attr] = originData[i][treeDataKeyMap[attr]]
          } else {
            temp.children = []
          }
        })

        resultArr.push(temp)

        if (
          originData[i][treeDataKeyMap.children] &&
          originData[i][treeDataKeyMap.children].length
        ) {
          formateTree(
            temp.children,
            originData[i][treeDataKeyMap.children],
            treeDataKeyMap,
            String(originData[i][treeDataKeyMap.id])
          )
        }
      }
    }
  }
}
