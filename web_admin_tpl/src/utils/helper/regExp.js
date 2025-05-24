/*
 * 常用正则表达式
 */

// 正整数 + 0
export const positiveIntegerReg = /^(0|[1-9][0-9]*)$/

// 正整数
export const positiveIntegerEx0Reg = /^([1-9][0-9]*)$/

// 正实数 + 0
export const positiveReg = /^(\d|[1-9]\d+)(\.\d+)?$/

// 正实数
export const positiveEx0Reg = /^0\.\d+$|^[1-9]\d*(\.\d+)?$/

// 实数
export const realNumberReg = /^(-?\d+)(\.\d+)?$/
