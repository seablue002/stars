/*
 * 发布打包模式编译主文件
 */
import inquirer from 'inquirer'
import shelljs from 'shelljs'
import chalk from 'chalk'
import buildPromptOptions from './buildPromptOptions.mjs'
import packageJson from '../../package.json' assert { type: 'json' }

inquirer.prompt(buildPromptOptions).then((answers) => {
  // 运行的命令，dev本地开发，build打包项目
  process.env.VITE_APP_COMMAND = 'build'
  // 编译环境，赋值给node变量供后续编译使用
  process.env.VITE_APP_ENV = answers.env
  // 是否启用cdn替换本地资源，赋值给node变量供后续编译使用
  process.env.VITE_APP_USE_CDN = answers.cdn

  process.env.VITE_APP_VERSION = packageJson.version

  // 打印输出启动信息
  console.log(chalk.red('-------------------------------------'))
  console.log(chalk.green('正在打包...'))
  console.log(`1、打包环境: ${chalk.green(answers.env)}`)
  console.log(`2、是否启用CDN: ${chalk.green(answers.cdn)}`)
  console.log(chalk.red('-------------------------------------'))

  shelljs.exec(`npm run lint && vite build --mode ${answers.env}`)
})
