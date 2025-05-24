/*
 * utils完成全局挂载
 */
import * as array from '@/utils/helper/array'
import * as object from '@/utils/helper/object'
import * as is from '@/utils/helper/is'
import * as date from '@/utils/helper/date'
import * as url from '@/utils/helper/url'
import * as tree from '@/utils/helper/tree'
import * as message from '@/utils/other/message'
import * as download from '@/utils/other/download'

const utilsPlugin = {
  install(app) {
    // array
    app.config.globalProperties.$array = array
    app.provide('$array', array)

    // object
    app.config.globalProperties.$object = object
    app.provide('$object', object)

    // is
    app.config.globalProperties.$is = is
    app.provide('$is', is)

    // date
    app.config.globalProperties.$date = date
    app.provide('$date', date)

    // url
    app.config.globalProperties.$url = url
    app.provide('$url', url)

    // tree
    app.config.globalProperties.$tree = tree
    app.provide('$tree', tree)

    // message
    app.config.globalProperties.$message = message.default

    // download
    app.config.globalProperties.$download = download
    app.provide('$download', download)
  },
}

export default utilsPlugin
