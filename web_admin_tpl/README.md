# vue3-admin
项目使用[vue3](https://cn.vuejs.org/)技术栈（[vue-router](https://router.vuejs.org/), [pinia](https://pinia.vuejs.org/)）+ [vite](https://cn.vitejs.dev/)进行开发，</br>
UI组件库采用[element-plus](https://element-plus.org/zh-CN/)，</br>
编码格式控制采用[eslint](https://eslint.org/)，</br>
具体情况查看配置</br>

### 命名规范：
+ 组件
  ```
  无论公共基础组件还是各级别、各类业务组件，命名尽量采用：文件夹（文件夹名为组件名）/index.vue
  ```
  例如：</br>
  公共组件NTTable: `/src/components/NTTable/index.vue`  </br>
  页面中业务组件BAR: `/src/views/foo(页面)/components/BAR/index.vue`  </br>
  页面中业务组件结合实际情况，也可以这样命名：`/src/views/foo(页面)/components/BAR.vue`  </br>

+ 文件夹、页面、js、css文件等一律采用小驼峰命名方式：</br>
  1、`/src/hooks/usePermission.js`</br>
+ js变量命名、函数一律采用小驼峰命名方式</br>
``` javascript
    // 变量
    let googsList = ref([])

    // 函数
    const handleClick = () => {}

    function handleClick() {}
```

+ js常量命名采用全大写加下划线_分隔多个单词形式</br>
``` javascript
    // 常量
    const GOODS_STATUS = {
        0: '下架',
        1: '上架',
    }
```

### 目录结构说明：
```
目录结构，文件组织形式参照现有结构即可(尽可能按模块、把控好拆分颗粒度，以便于管理与便于开发为准)
```
```html
├── src                        # 源代码
│   ├── api                    # 所有api请求
│   │    │──modules            # api模块目录
│   │    │  └──auth.js         # 权限相关接口
│   │    │  └──goods.js        # 商品相关接口
│   │    └──index.js           # api主文件
│   ├── assets                 # 图片、svg 等静态资源
│   ├── components             # 公共组件
│   │    └──NTTable            # 表格组件
│   │    └──NTCustomTable      # 可调整风格、列表格组件
│   │    └──NTSearchFormFilter # 搜索表单过滤器组件
│   ├── settings               # 配置
│   │    └──config             # 常量配置
│   │    └──dict               # 用于后端不支持数据字典接口获取时，前端定义保有的数据字典定义目录
│   ├── directives             # 指令（按钮权限控制、input输入框输入内容、长度限制等）
│   ├── hooks                  # 钩子（代码逻辑复用）
│   ├── layouts                # 框架布局
│   ├── plugins                # 插件
│   ├── views                  # 页面
│   │    └──auth               # 权限管理
│   │         └──account       # 账号管理
│   │              └──list     # 账号列表
│   │              │     └──index.vue # 账号列表实际页面单文件.vue
│   │              └──edit     # 账号编辑
│   │                   └──index.vue # 账号编辑实际页面单文件.vue
│   ├── router                       # 路由配置
│   │    └──guards                   # 路由钩子
│   │    └──routes                   # 页面路由
│   │         └──index.js            # 路由主文件
│   │         └──basic.js            # 基础路由
│   │         └──permission          # 权限菜单路由
│   ├── store                        # pinia 状态管理
│   ├── utils                        # 工具库、方法
│   │    └──cache                    # 缓存操作
│   │    └──helper                   # 助手函数
│   │    └──http                     # http网络请求
│   │    └──other                    # 其它为归类
│   ├── styles            # 样式管理
│   ├── main.js           # 入口文件 加载组件 初始化等
│   └── App.vue           # 入口页面
├── .env                  # 通用环境变量配置
├── .env.development      # 开发环境下环境变量配置
├── .env.test             # 测试环境下环境变量配置
├── .env.uat              # UAT环境下环境变量配置
├── README                 # 说明文档
├── package.json           # package.json
├── package-lock           # package-lock.json
├── README                 # 说明文档
└── vite.config.js         # 项目运行编译配置
```

### 框架图：



### 开发可使用工具库：
```
使用原则，优先使用已有工具库和方法，没有适合自身开发业务需求的，可以按照规范自行添加新工具库或方法
```

+ `默认已经依赖安装常用工具库如下：`
    #### js工具库
    [1、lodash](https://lodash.com/)

    #### vue3工具库
    [1、vueuse](https://vueuse.org/)

    #### css工具库
    [1、tailwind](https://tailwindcss.com/docs/installation)

    #### icon图表
    [1、element plus自带icon](https://element-plus.org/zh-CN/)
    [2、remixicon](https://www.remixicon.cn/)

+ `项目本地自研工具库在/src/utils目录下`

### 框架熟悉辅助：
```
为了大家快速熟悉框架的使用，会在此补充一些框架中集成的组件、功能的使用demo，帮助大家减少学习成本。
```
#### 框架自定义组件
框架已在开发模式下，自动集成组件demo相关页面路由，如需了解使用方式，可以进入 `/src/views/other/demo/componentsUse`，查看具体使用代码实例。</br>
demo效果预览，请访问 `/demo/components-use`路由地址

目前框架中包含：
+ 1、表格组件 `NTTable`
+ 2、可以定制表格组件 `NTCustomTable`
+ 3、表格filter搜索过滤组件 `NTSearchFormFilter`
+ 4、下拉选择select表格组件 `NTSelectTable`


### 命令使用
```
输入命令后，根据命令行具体提示，进行选择即可
```
+ 本地开发 `npm run dev`
+ 打包项目 `npm run build`