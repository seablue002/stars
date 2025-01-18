/* 信息 Validator */
const infoRules = (() => {
  const validatorTitle = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入标题'))
    }
    callback()
  }

  const validatorSubTitle = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入副标题'))
    }
    callback()
  }

  return {
    title: [
      { required: true, validator: validatorTitle, trigger: 'blur' }
    ],
    sub_title: [
      { required: true, validator: validatorSubTitle, trigger: 'blur' }
    ]
  }
})()

export default infoRules
