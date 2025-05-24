/*
 * 本地数据字典
 */
const dict = {}
const dictFiles = import.meta.glob('./modules/*.js', { eager: true })

Object.entries(dictFiles).forEach(([path, module]) => {
  const fileName = path.match(/\/(\w+)\./)[1]
  dict[fileName] = module
})

/**
 * 将值转换为字典对应的value, 用于后端返回数值等需要前端转换为对应value展示
 * @param dictVar 字典变量
 * @param keyVal 需要转换为字典value的值
 * @returns 转换完毕后的字典value的值
 */
export const formatDictKeyToValue = (dictVar, keyVal) => {
  const index = dictVar.findIndex((dictItem) => {
    return dictItem.value === keyVal
  })

  if (index === -1) {
    return ''
  }
  return dictVar[index].label
}

export default dict
