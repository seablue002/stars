/*
 * url操作方法集合
 */

/**
 * 提取单个query参数
 * @param {String} key query参数名
 * @param {*} url 浏览器访问地址
 * @returns String query参数对应的数据值
 */
export const getOneUrlQueryParam = (key, url) => {
  const reg = new RegExp(`[&, ?]${key}=([^\\&,\\#]*)`, 'i')
  const value = reg.exec(url)

  return value ? value[1] : null
}

// 提取多个query参数
export const getMultiUrlQueryParams = (keys, url) => {
  const params = {}
  keys.forEach((key) => {
    const reg = new RegExp(`[&, ?]${key}=([^\\&,\\#]*)`, 'i')
    const value = reg.exec(url)
    params[key] = value ? value[1] : null
  })

  return params
}

// 解析地址栏url参数
export const parseURL = (url) => {
  const a = document.createElement('a')
  a.href = url
  return {
    source: url,
    protocol: a.protocol.replace(':', ''),
    host: a.hostname,
    port: a.port || '80',
    query: a.search,
    params: (() => {
      const ret = {}
      const seg = a.search.replace(/^\?/, '').split('&')
      const len = seg.length
      let s = []

      for (let i = 0; i < len; i += 1) {
        if (seg[i]) {
          s = seg[i].split('=')
          const [prevIndex, nextIndex] = s
          ret[prevIndex] = nextIndex
        }
      }
      return ret
    })(),
    file: (a.pathname.match(/\/([^/?#]+)$/i) || ['', ''])[1],
    hash: a.hash.replace('#', ''),
    path: a.pathname.replace(/^([^/])/, '/$1'),
    relative: (a.href.match(/tps?:\/\/[^/]+(.+)/) || ['', ''])[1],
    segments: a.pathname.replace(/^\//, '').split('/'),
  }
}
