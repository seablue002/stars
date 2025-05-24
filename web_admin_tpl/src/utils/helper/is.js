/*
 * 类型判断
 */

/**
 * 数据类型检测
 * @param {*} value 待检测数据
 * @param {*} type 类型
 * @returns Boolean
 */

import {
  positiveIntegerReg,
  positiveIntegerEx0Reg,
  positiveReg,
} from './regExp'

export function dataTypeCheck(value, type) {
  return Object.prototype.toString.call(value) === `[object ${type}]`
}

export function isArray(value) {
  return dataTypeCheck(value, 'Array')
}

export function isObject(value) {
  return dataTypeCheck(value, 'Object')
}

export function isString(value) {
  return dataTypeCheck(value, 'String')
}

export function isNumber(value) {
  return dataTypeCheck(value, 'Number')
}

export function isFunction(value) {
  return dataTypeCheck(value, 'Function') || isAsyncFunction(value)
}

export function isAsyncFunction(value) {
  return dataTypeCheck(value, 'AsyncFunction')
}

export function isRegExp(value) {
  return dataTypeCheck(value, 'RegExp')
}

export function isDef(value) {
  return !dataTypeCheck(value, 'Undefined')
}

export function isUnDef(value) {
  return !isDef(value)
}

export function isNull(value) {
  return dataTypeCheck(value, 'Null')
}

export function isNullAndUnDef(value) {
  return isUnDef(value) && isNull(value)
}

export function isNullOrUnDef(value) {
  return isUnDef(value) || isNull(value)
}

// 是否正整数 + 0
export function isPositiveInteger(value) {
  return positiveIntegerReg.test(value)
}

// 是否正整数
export function isPositiveIntegerEx0(value) {
  return positiveIntegerEx0Reg.test(value)
}

// 是否正实数 + 0
export function isPositive(value) {
  return positiveReg.test(value)
}
