/* 常用正则表达式 */

// 正整数 + 0
export const isPositiveIntegerReg = /^(0|[1-9][0-9]*)$/

// 正整数
export const isPositiveIntegerEx0 = /^([1-9][0-9]*)$/

// 正实数 + 0
export const isPositive = /^(\d|[1-9]\d+)(\.\d+)?$/

// 正实数
export const isPositiveEx0 = /^0\.\d+$|^[1-9]\d*(\.\d+)?$/

// 实数
export const isRealNumber = /^(-?\d+)(\.\d+)?$/

// 手机号
export const isPhone = /^1\d{10}/
