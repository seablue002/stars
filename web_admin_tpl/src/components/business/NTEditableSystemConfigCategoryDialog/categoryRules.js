/* 栏目 Validator */
const columnRules = (() => {
  const validatorName = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('请输入栏目名称'))
    }
    callback()
  }

  const validatorPid = (rule, value, callback) => {
    if (value === '' || value.length === 0) {
      callback(new Error('请选择上级栏目'))
    }
    callback()
  }

  const validatorColumnDirPath = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('请输入本栏目目录'))
    }
    callback()
  }

  const validatorIsLast = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('请选择栏目类型'))
    }
    callback()
  }

  const validatorModelTbNameName = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('请选择绑定的数据模型'))
    }
    callback()
  }

  const validatorListTplPath = (rule, value, callback) => {
    if (value === '0') {
      callback(new Error('请选择页面模板文件'))
    }
    callback()
  }

  return {
    name: [{ required: true, validator: validatorName, trigger: 'blur' }],
    pid: [{ required: true, validator: validatorPid, trigger: 'blur' }],
    is_last: [
      { required: true, validator: validatorIsLast, trigger: 'change' },
    ],
    column_dir_path: [
      { required: true, validator: validatorColumnDirPath, trigger: 'blur' },
    ],
    model_tb_name: [
      {
        required: true,
        validator: validatorModelTbNameName,
        trigger: 'change',
      },
    ],
    list_temp_id: [
      { required: true, validator: validatorListTplPath, trigger: 'blur' },
    ],
  }
})()

export default columnRules
