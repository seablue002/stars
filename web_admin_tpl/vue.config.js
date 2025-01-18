const webpack = require('webpack')
const path = require('path')

const APP_VERSION = JSON.stringify(require('./package.json').version)
const CURRENT_TIME = new Date()
const APP_BUILD_TIME = JSON.stringify(CURRENT_TIME.toLocaleDateString() + ' ' + CURRENT_TIME.getHours() + ':' + CURRENT_TIME.getMinutes())

function resolve (dir) {
  return path.join(__dirname, './', dir)
}

module.exports = {
  publicPath: './',
  // 生产环境是否生成 sourceMap 文件
  productionSourceMap: false,
  configureWebpack: config => {
    config.resolve = {
      // 扩展名自动补全
      extensions: ['.ts', '.js', '.scss', '.json'],
      alias: {
        '@': resolve('src')
      }
    }
    config.plugins.push(
      new webpack.DefinePlugin({
        // 项目版本号
        'process.env.APP_VERSION': APP_VERSION,
        'process.env.APP_BUILD_TIME': APP_BUILD_TIME
      }),
      new webpack.ProvidePlugin({
        'window.Quill': 'quill/dist/quill.js',
        Quill: 'quill/dist/quill.js'
      })
    )
  }
}
