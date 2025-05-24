/*
 * 对象相关函数
 */
// 去除空对象数据
export const deleteEmpty = (data) => {
  const temp = {}
  Object.keys(data).forEach((key) => {
    if (data[key] !== '' && data[key] !== undefined && data[key] !== null) {
      temp[key] = data[key]
    }
  })

  return temp
}

// 从一个对象中获取本对象需要的值
// 把originObj 赋值给 curretObj对象
export const getAssignPlain = (curretObj, originObj) => {
  Object.keys(curretObj).forEach((key) => {
    if (![undefined, '', null, NaN].includes(originObj[key])) {
      curretObj[key] = originObj[key]
    }
  })
}
