/*
 * 本地开发编译选项配置
 */
const buildPromptOptions = [
  {
    type: 'list',
    name: 'env',
    message: '请选择要启动的环境',
    choices: [
      {
        name: 'dev开发环境',
        value: 'development',
      },
      {
        name: 'test测试环境',
        value: 'test',
      },
      {
        name: 'uat环境',
        value: 'uat',
      },
      {
        name: 'prod生产环境',
        value: 'production',
      },
    ],
  },
  {
    type: 'list',
    name: 'cdn',
    message: '是否使用CDN替换本地资源',
    choices: [
      {
        name: '是',
        value: true,
      },
      {
        name: '否',
        value: false,
      },
    ],
    default: true,
  },
]

export default buildPromptOptions
