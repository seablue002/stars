import { mock } from 'mockjs'
import { authFuncList } from './auth'

// admin 用户信息与权限菜单信息
const adminInfo = {
  user: {
    name: 'admin',
  },
  auth: authFuncList,
}

// finance 用户信息与权限菜单信息
const financeInfo = {
  user: {
    name: 'finance',
  },
  auth: [
    {
      id: '413695',
      pid: 0,
      parentName: '',
      name: 'Framework',
      icon: 'ri-presentation-fill',
      title: '框架使用',
      path: '/demo/framework',
      // 功能类型 menu菜单、button按钮
      type: 'menu',
      // 功能唯一标识码
      code: 'Framework',
      status: 1,
      sort: 2,
      createTime: mock('@datetime'),
      updateTime: mock('@datetime'),
      children: [
        {
          id: '414303',
          pid: '413695',
          parentName: '框架使用',
          name: 'AuthControl',
          icon: 'ri-git-repository-private-line',
          title: '权限控制',
          path: '/demo/framework/authControl',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'AuthControl',
          status: 1,
          sort: 1,
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
        },
      ],
    },
  ],
}

// operator 用户信息与权限菜单信息
const operatorInfo = {
  user: {
    name: 'operator',
  },
  auth: [
    {
      id: '485652',
      pid: 0,
      parentName: '',
      name: 'Components',
      icon: 'ri-shape-fill',
      title: '组件',
      path: '/demo/components',
      // 功能类型 menu菜单、button按钮
      type: 'menu',
      // 功能唯一标识码
      code: 'Components',
      status: 1,
      sort: 2,
      createTime: mock('@datetime'),
      updateTime: mock('@datetime'),
      children: [
        {
          id: '568212',
          pid: '485652',
          parentName: '组件',
          name: 'ComponentsBase',
          icon: 'ri-layout-grid-line',
          title: '基础组件',
          path: '/demo/components/base',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'ComponentsBase',
          status: 1,
          sort: 1,
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
          children: [
            {
              id: '236545',
              pid: '568212',
              parentName: '基础组件',
              name: 'Table',
              title: '表格',
              path: '/demo/components/base/table',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'Table',
              status: 1,
              sort: 1,
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  id: '845695',
                  pid: '236545',
                  parentName: '表格',
                  name: 'ConfigurableTable',
                  title: '配置型表格',
                  path: '/demo/components/base/table/configurableTable',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'ConfigurableTable',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                  children: [
                    {
                      id: '588961',
                      pid: '845695',
                      parentName: '配置型表格',
                      name: 'ConfigurableTableDetail',
                      title: '详情',
                      // 功能类型 menu菜单、button按钮
                      type: 'button',
                      // 功能唯一标识码
                      code: 'ConfigurableTableDetail',
                      status: 1,
                      sort: 1,
                      createTime: mock('@datetime'),
                      updateTime: mock('@datetime'),
                    },
                  ],
                },
                {
                  id: '145696',
                  pid: '236545',
                  parentName: '表格',
                  name: 'CustomeTable',
                  title: '自定义表格',
                  path: '/demo/components/base/table/customeTable',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'CustomeTable',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                  children: [
                    {
                      id: '125666',
                      pid: '145696',
                      parentName: '自定义表格',
                      name: 'CustomeTableDetail',
                      title: '详情',
                      // 功能类型 menu菜单、button按钮
                      type: 'button',
                      // 功能唯一标识码
                      code: 'CustomeTableDetail',
                      status: 1,
                      sort: 1,
                      createTime: mock('@datetime'),
                      updateTime: mock('@datetime'),
                    },
                  ],
                },
              ],
            },
            {
              id: '265332',
              pid: '568212',
              parentName: '基础组件',
              name: 'Form',
              title: '表单',
              path: '/demo/components/base/form',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'Form',
              status: 1,
              sort: 1,
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  id: '236445',
                  pid: '265332',
                  parentName: '表单',
                  name: 'DynamicForm',
                  title: '动态表单',
                  path: '/demo/components/base/form/dynamicForm',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'DynamicForm',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                },
                {
                  id: '255655',
                  pid: '265332',
                  parentName: '表单',
                  name: 'StepForm',
                  title: '分步表单',
                  path: '/demo/components/base/form/stepForm',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'StepForm',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                },
              ],
            },
            {
              id: '365654',
              pid: '568212',
              parentName: '基础组件',
              name: 'Column',
              title: '分栏',
              path: '/demo/components/base/column',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'Column',
              status: 1,
              sort: 1,
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  id: '369995',
                  pid: '365654',
                  parentName: '分栏',
                  name: 'ColumnAndList',
                  title: '左分类右列表',
                  path: '/demo/components/base/columnAndList',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'ColumnAndList',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                },
              ],
            },
            {
              id: '315233',
              pid: '568212',
              parentName: '基础组件',
              name: 'Tree',
              title: '树状图',
              path: '/demo/components/base/tree',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'Tree',
              status: 1,
              sort: 1,
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  id: '316455',
                  pid: '315233',
                  parentName: '树状图',
                  name: 'LineTree',
                  title: '水平树状图',
                  path: '/demo/components/base/tree/lineTree',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'LineTree',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                },
              ],
            },
          ],
        },
      ],
    },
    {
      id: '413695',
      pid: 0,
      parentName: '',
      name: 'Framework',
      icon: 'ri-presentation-fill',
      title: '框架使用',
      path: '/demo/framework',
      // 功能类型 menu菜单、button按钮
      type: 'menu',
      // 功能唯一标识码
      code: 'Framework',
      status: 1,
      sort: 2,
      createTime: mock('@datetime'),
      updateTime: mock('@datetime'),
      children: [
        {
          id: '414563',
          pid: '413695',
          parentName: '框架使用',
          name: 'MultiLevelRoutingCache',
          icon: 'ri-git-merge-line',
          title: '多级路由缓存',
          path: '/demo/framework/routeCache/multiLevelRoutingCache',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'MultiLevelRoutingCache',
          status: 1,
          sort: 1,
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
          children: [
            {
              id: '415653',
              pid: '414563',
              parentName: '多级路由缓存',
              name: 'MultiLevelRoutingCache1',
              title: '多级路由1-1',
              path: '/demo/framework/routeCache/multiLevelRoutingCache1-1',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'MultiLevelRoutingCache1',
              status: 1,
              sort: 1,
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  id: '4152361',
                  pid: '415653',
                  parentName: '多级路由1-1',
                  name: 'MultiLevelRoutingCache2',
                  title: '多级路由1-1-1',
                  path: '/demo/framework/routeCache/multiLevelRoutingCache1-1-1',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'MultiLevelRoutingCache2',
                  status: 1,
                  sort: 1,
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                },
              ],
            },
          ],
        },
        {
          id: '414659',
          pid: '413695',
          parentName: '框架使用',
          name: 'PageLeaveConfirm',
          icon: 'ri-arrow-left-circle-line',
          title: '离开页面确认',
          path: '/demo/framework/pageLeaveConfirm',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'PageLeaveConfirm',
          status: 1,
          sort: 1,
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
        },
        {
          id: '414303',
          pid: '413695',
          parentName: '框架使用',
          name: 'AuthControl',
          icon: 'ri-git-repository-private-line',
          title: '权限控制',
          path: '/demo/framework/authControl',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'AuthControl',
          status: 1,
          sort: 1,
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
        },
      ],
    },
  ],
}

const loginMock = () => {
  mock(/\/api\/v1\/user\/login/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('/api/v1/login接口请求参数', JSON.parse(options.body))

    let userInfo = null

    // 区分登录账号，返回对应用户信息与权限菜单
    switch (params.account) {
      case 'admin':
        userInfo = adminInfo
        break
      case 'finance':
        userInfo = financeInfo
        break
      case 'operator':
        userInfo = operatorInfo
        break
      default:
        break
    }

    // 将权限菜单树扁平化
    // 目前本框架匹配本地权限菜单采用的扁平化后的菜单进行比对
    // const auth = []
    // treeFlat(auth, userInfo.auth)
    return {
      code: 200,
      data: {
        token: returnToken(),
        user: userInfo.user,
        auth: userInfo.auth,
      },
      message: '登录成功',
    }
  })
}

// 32位随机token生成
function returnToken() {
  const abc = 'abcdefghijklmnopqrstuvwxyz1234567890'.split('')
  let token = ''
  for (let i = 0; i < 32; i += 1) {
    token += abc[Math.floor(Math.random() * abc.length)]
  }
  return token
}

export default loginMock
