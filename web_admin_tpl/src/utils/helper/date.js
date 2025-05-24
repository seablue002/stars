import moment from 'moment'

/**
 * 日期格式化字符串形式
 * @param timestamp 日期时间戳
 * @param format 模式
 * @returns 格式化时间String
 */
export const formatDate = (timestamp, format = 'YYYY-MM-DD HH:mm:ss') => {
  if (!timestamp) {
    return ''
  }

  return moment(timestamp).format(format)
}

/**
 * 当前时间的格式化字符串形式
 * @returns 格式化时间String
 */
export function getCurDateTime(format = 'YYYY-MM-DD HH:mm:ss') {
  const date = new Date()
  const timestamp = date.getTime()
  return formatDate(timestamp, format)
}

export function checkTimeRangesOverlap(ranges) {
  const unifiedRanges = ranges.map((range) => {
    return range.map((date) => {
      return moment(`${formatDate(date, 'YYYY-MM-DD HH:mm:')}00`).valueOf()
    })
  })
  const sortedRanges = unifiedRanges.slice().sort((a, b) => a[0] - b[0])

  for (let i = 1; i < sortedRanges.length; i += 1) {
    if (sortedRanges[i][0] <= sortedRanges[i - 1][1]) {
      return true
    }
  }

  return false
}
