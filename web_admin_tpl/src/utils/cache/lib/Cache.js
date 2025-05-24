/*
 * 缓存操作封装类，统一sessionStorage、localStorage等缓存操作
 */
import { isNullOrUnDef } from '@/utils/helper/is'

class Cache {
  constructor(cache, keyPrefix) {
    this.cache = cache
    this.keyPrefix = keyPrefix
  }

  /**
   * 组装带缓存前缀的完整缓存key
   * @param {String} cacheKey 缓存key
   * @returns String 带缓存前缀的完整缓存key
   */
  assembleCacheKey(cacheKey) {
    return `${this.keyPrefix}${cacheKey}`
  }

  /**
   * 设置缓存数据项
   * @param {String} cacheKey 缓存key
   * @param {*} value 需要缓存的数据
   * @param {Number} expire 有效期秒s
   */
  setCache(cacheKey, value, expire) {
    const nowTimeStamp = +new Date()
    const data = {
      value,
      time: nowTimeStamp,
    }
    if (!isNullOrUnDef(expire)) {
      data.expire = nowTimeStamp + expire * 1000
    }

    const stringifyData = JSON.stringify(data)

    this.cache.setItem(this.assembleCacheKey(cacheKey), stringifyData)
  }

  /**
   * 获取缓存数据项
   * @param {String} cacheKey 缓存key
   * @param {*} defaultValue 默认返回值
   * @returns 返回数据值
   */
  getCache(cacheKey, defaultValue = null) {
    const storageVal = this.cache.getItem(this.assembleCacheKey(cacheKey))
    if (!storageVal) {
      return defaultValue
    }

    const parseData = JSON.parse(storageVal)

    const nowTimeStamp = +new Date()

    if (isNullOrUnDef(parseData.expire) || parseData.expire >= nowTimeStamp) {
      return parseData.value
    }

    // 已过期
    // 清除过期数据
    this.removeItem(cacheKey)
    return null
  }

  /**
   * 获取多个缓存数据项
   * @param {String[]} cacheKeys 缓存key
   * @param {any[]} defaultValues 默认返回值
   * @returns 返回数据值数组
   */
  getMultipleCache(cacheKeys, defaultValues = []) {
    const values = []

    cacheKeys.forEach((cacheKey, i) => {
      let defaultVal = defaultValues[i]
      if (defaultValues.length - 1 < i) {
        // 没有设置对应数据项默认值时，重置默认值为null, 保持与getCache默认值一致性
        defaultVal = null
      }
      values[i] = this.getCache(cacheKey, defaultVal)
    })

    return values
  }

  /**
   * 删除缓存数据项
   * @param {String} cacheKey 缓存key
   */
  removeCache(cacheKey) {
    this.cache.removeItem(this.assembleCacheKey(cacheKey))
  }

  /**
   * 删除多个缓存数据项
   * @param {*} cacheKeys 缓存keys
   */
  removeMultipleCache(cacheKeys) {
    cacheKeys.forEach((cacheKey) => {
      this.removeCache(cacheKey)
    })
  }

  /**
   * 清除当前存储方式下所有存储的数据
   */
  clear() {
    this.cache.clear()
  }
}

export default Cache
