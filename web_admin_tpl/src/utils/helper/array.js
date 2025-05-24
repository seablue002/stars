/*
 * 数组操作辅助函数
 */

/**
 * [indexOfObjInObjArrByKey 根据指定key, 获取对象obj在对象数组objArr所处位置的索引]
 * @param  {[Object]} obj    [待比较对象]
 * @param  {[Object[]]} objArr [查找对象的数组集合]
 * @param  {String[]} key    [需要进行匹配的key]
 * @return {[Number]}        [如果存在则返回obj在objArr中首次出现对应的index，未找到则返回-1]
 */
export function indexOfObjInObjArrByKey(obj, objArr, key = 'id') {
  let index = -1
  for (let i = 0; i < objArr.length; i += 1) {
    if (obj[key] === objArr[i][key]) {
      index = i
      break
    }
  }
  return index
}

/**
 * 根据多key查找对象obj在对应数组[obj1, obj2, ...]中首次出现的索引位置index
 * @param  {[Object]} obj    [待比较对象]
 * @param  {[Object[]]} objArr [查找对象的数组集合]
 * @param  {String[]} key    [需要进行匹配的key集合]
 * @return {[Number]}        [如果存在则返回obj在objArr中首次出现对应的index，未找到则返回-1]
 */
export function indexOfObjInObjArrByMultipleKey(obj, objArr, keys) {
  let index = -1
  for (let i = 0; i < objArr.length; i += 1) {
    let count = 0
    keys.forEach((key, j) => {
      if (obj[keys[j]] === objArr[i][keys[j]]) {
        count += 1
      }
    })

    if (count === keys.length) {
      index = i
      break
    }
  }
  return index
}
