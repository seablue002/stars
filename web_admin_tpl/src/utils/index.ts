import moment from 'moment'
import * as $_ from 'lodash'
import {
  RESOURCE_PATH
} from '@/config/http'

// 数据类型检查
export function isNumber (data: unknown): boolean {
  return typeof data === 'number'
}

export function isString (data: unknown): boolean {
  return typeof data === 'string'
}

export function isBoolean (data: unknown): boolean {
  return typeof data === 'boolean'
}

export function isArray (data: unknown): boolean {
  return Object.prototype.toString.call(data).slice(8, -1) === 'Array'
}

export function isObject (data: unknown): boolean {
  return typeof data === 'object' ? !isArray(data) : false
}

export function isFunction (data: unknown): boolean {
  return typeof data === 'function'
}

/**
 * [indexOfObjInObjArrByKey 根据指定key, 获取对象obj在对象数组objArr所处位置的索引]
 * @param  {[type]} obj    [description]
 * @param  {[type]} objArr [description]
 * @param  {String} key    [description]
 * @return {[type]}        [description]
 */
export function indexOfObjInObjArrByKey (obj: {[key: string]: any}, objArr: {[key: string]: any}[], key = 'id'): number {
  let index = -1
  for (let i = 0; i < objArr.length; i++) {
    if (obj[key] === objArr[i][key]) {
      index = i
      break
    }
  }
  return index
}

/**
 * [indexOfObjInObjArrByMultipleKey 根据指定多个key, 获取对象obj在对象数组objArr所处位置的索引]
 * @param  {[type]} obj    [description]
 * @param  {[type]} objArr [description]
 * @param  {String} key    [description]
 * @return {[type]}        [description]
 */
export function indexOfObjInObjArrByMultipleKey (obj: {[key: string]: any}, objArr: {[key: string]: any}[], keys = ['id']): number {
  let index = -1
  for (let i = 0; i < objArr.length; i++) {
    let count = 0
    for (const j in keys) {
      if (obj[keys[j]] === objArr[i][keys[j]]) {
        count++
      }
    }
    if (count === keys.length) {
      index = i
      break
    }
  }
  return index
}

/**
 * [env_steps 判别打包环境类型，执行不同回调]
 * @param  {[type]} devCallback  [开发环境时的回调]
 * @param  {[type]} stagCallback [类生产(测试)环境时的回调]
 * @param  {[type]} proCallback  [生产环境时的回调]
 * @return {[type]}              [description]
 */
export const envSteps = function (devCallback: () => any, stagCallback: () => any, proCallback: () => any): void {
  const NODE_ENV = process.env.NODE_ENV
  if (NODE_ENV === 'development') {
    // 本地开发测试环境api接口地址域名
    isFunction(devCallback) && devCallback()
  } else if (NODE_ENV === 'production') {
    // 正式环境域名api接口地址域名
    isFunction(proCallback) && proCallback()
    const APP_MODE = process.env.VUE_APP_MODE
    if (APP_MODE === 'staging') {
      // 类生产环境, 用于功能验收(staging环境\线上测试环境)api接口地址域名
      isFunction(stagCallback) && stagCallback()
    }
  }
}

/**
* 将原始接口tree数据树形结构调整为通用结构
* @param resultArr 存放最终树形化数据的数组变量
* @param originData 原始待处理数据
* @param treeDataKeyMap 原始待处理数据字段与通用结构字段对照表
* @param pid 元素的父级id
*/
// tree 通用结构字段interface
export interface TreeProps {
  id: string,
  label: string,
  pid: string | null,
  children: TreeProps[]
}
// 原始待处理数据字段与通用结构字段对照表
export interface TreeDataKeyMapProps {
  id: string,
  label: string,
  pid: string,
  children: string
}
export const formateTree = function (resultArr: TreeProps[], originData: Array<{[key: string]: any}>, treeDataKeyMap: TreeDataKeyMapProps, pid = '#ROOT_NODE_PID'): void {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i++) {
      if (String(originData[i][treeDataKeyMap.pid]) === pid) {
        const temp: TreeProps = {
          id: '',
          label: '',
          pid: '',
          children: []
        }
        for (const attr in treeDataKeyMap) {
          if (attr !== 'children') {
            (temp as any)[attr] = JSON.parse(JSON.stringify(originData[i][(treeDataKeyMap as any)[attr]]))
          } else {
            temp.children = []
          }
        }
        resultArr.push(temp)
        if (originData[i][treeDataKeyMap.children].length) {
          formateTree(temp.children, originData[i][treeDataKeyMap.children], treeDataKeyMap, String(originData[i][treeDataKeyMap.id]))
        }
      }
    }
  }
}

/**
 * [treeFlat 树结构的扁平化处理]
 * @param {[type]} resultArr      [结果存放数组]
 * @param {[type]} originData     [原始数据]
 * @param {[type]} deleteAttrsArr [需要剔除的字段名集合数组]
 */
export const treeFlat = function (resultArr: any[], originData: any[], level = 0, deleteAttrsArr: any[] = [], childrenKey = 'childNodes'): void {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i++) {
      const tempCurDataItem: {[key: string]: any} = {}
      for (const attr in originData[i]) {
        if (!deleteAttrsArr.includes(attr)) {
          tempCurDataItem[attr] = originData[i][attr]
        }
      }

      tempCurDataItem.level = level
      resultArr.push(tempCurDataItem)
      if (isArray(originData[i][childrenKey])) {
        let idx = level
        treeFlat(resultArr, originData[i][childrenKey], ++idx, deleteAttrsArr, childrenKey)
      }
    }
  }
}

/**
 * 将树结构扁平化数组还原为树结构
 * @param resultArr
 * @param originData
 * @param pid
 * @param keyMaps 主键必须放第一位
 */
export const getTree = function (resultArr: any[], originData: any[], pid = 0, keyMaps: {[attr: string]: any} = { id: 'id', name: 'name', pid: 'pid' }, pids: number[] = []): void {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i++) {
      if (originData[i].pid === pid) {
        if (!pids.includes(pid)) {
          pids.push(pid)
        }
        const mapKeys = Object.keys(keyMaps)
        const mapVals = Object.values(keyMaps)
        const temp: {[attr: string]: any} = {
          pids: pids.join(',')
        }
        for (let j = 0; j < mapKeys.length; j++) {
          temp[mapKeys[j]] = originData[i][mapVals[j]]
        }
        temp.children = []
        resultArr.push(temp)

        getTree(temp.children, originData, temp[mapKeys[0]], keyMaps, pids)
      }
    }
  }
}

/**
 * 日期字符串格式化为指定形式日期函数
 * @param dateStr 日期字符串
 * @param pattern 模式
 * @returns
 */
export const formateDateStr = (timestamp: number, pattern = 'YYYY-MM-DD HH:mm:ss'): string => {
  if (!timestamp) {
    return ''
  }

  return moment(timestamp).format(pattern)
}

/**
 * 获取当前时间YYYYMMDDHHmmss字符串形式
 * @returns
 */
export const getCurDateTime = (): string => {
  const date = new Date()
  const timestamp = date.getTime()
  return moment(timestamp).format('YYYYMMDDHHmmss')
}

/**
 * 根据用户登录获取的权限路由数据，过滤匹配出用户所能访问的页面路由
 * @param resultArr 存放结果的数组
 * @param asyncPermissionRoutes 所有需要权限访问的页面路由数据
 * @param compareData 用户登录获取的权限路由数据
 */
export const filterRoutes = function (resultArr: any[], asyncPermissionRoutes: Array<{[key: string]: any}>, compareData: any[]): void {
  if (isArray(asyncPermissionRoutes)) {
    for (let i = 0; i < asyncPermissionRoutes.length; i++) {
      if (indexOfObjInObjArrByKey(asyncPermissionRoutes[i], compareData, 'path') !== -1) {
        const temp: {[key: string]: any} = {
        }
        for (const attr of Object.keys(asyncPermissionRoutes[i])) {
          if (attr !== 'children') {
            temp[attr] = $_.clone(asyncPermissionRoutes[i][attr])
          } else {
            temp.children = []
          }
        }
        resultArr.push(temp)

        if (asyncPermissionRoutes[i]?.children?.length) {
          temp.children = []
          filterRoutes(temp.children, asyncPermissionRoutes[i].children, compareData)
        }
      }
    }
  }
}

export const recursionMachine = function (originData: Array<{[key: string]: any}>, cb: (dataItem: any) => void): void {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i++) {
      cb(originData[i])
      if (originData[i].children.length) {
        recursionMachine(originData[i].children, cb)
      }
    }
  }
}

export const getRuleTree = function (resultArr: Array<{[key: string]: any}>, originData: Array<{[key: string]: any}>, pid: number | string = 0, keyMaps: {[attr: string]: any} = { id: 'id', label: 'title', pid: 'pid' }): void {
  if (isArray(originData)) {
    for (let i = 0; i < originData.length; i++) {
      if (originData[i].pid === pid) {
        const mapKeys = Object.keys(keyMaps)
        const mapVals = Object.values(keyMaps)
        const temp: {[attr: string]: any} = {
          // pid: pid,
          field: 'id',
          disabled: false
        }
        for (let j = 0; j < mapKeys.length; j++) {
          temp[mapKeys[j]] = originData[i][mapVals[j]]
        }

        temp.children = []
        resultArr.push(temp)

        getRuleTree(temp.children, originData, temp.id, keyMaps)
      }
    }
  }
}

export const getCombination = function (array: any[]): any[] {
  let resultArray: any[] = []
  array.forEach((arrItem: any[]) => {
    if (resultArray.length === 0) {
      resultArray = arrItem
    } else {
      const emptyArray: any[] = []
      resultArray.forEach((item) => {
        arrItem.forEach((value) => {
          isArray(item) ? emptyArray.push([...item, value]) : emptyArray.push([item, value])
        })
      })
      resultArray = emptyArray
    }
  })
  return resultArray
}

export const formateResourceUrl = function (path: string): string {
  return `${RESOURCE_PATH}${path}`
}

// 手动触发window系统事件
export const triggleWindowSystemEvent = (eventName: string): void => {
  const resizeEvent = document.createEvent('Event')
  resizeEvent.initEvent(eventName, true, true)
  window.dispatchEvent(resizeEvent)
}

export const formatDictLabel = (dictList: {[key: string]: { label: string }}, attrPrefix: string, type: string): string => {
  const item = dictList[`${attrPrefix}${type}`]
  const labelText = item ? item?.label : '未知'
  return labelText
}

// 返回图片路径
export const formatPicUrl = (urlOrFile: string|File): string => {
  let url = ''
  if ((urlOrFile as any) instanceof File) {
    const windowURL = window.URL || window.webkitURL
    url = windowURL.createObjectURL(urlOrFile as any)
  } else if ((typeof urlOrFile) === 'string') {
    url = RESOURCE_PATH + urlOrFile
  }
  return url
}
