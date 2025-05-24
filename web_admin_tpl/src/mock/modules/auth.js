import { mock } from 'mockjs'

export const authFuncList = [
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
                  {
                    id: '596631',
                    pid: '845695',
                    parentName: '配置型表格',
                    name: 'ConfigurableTableEdit',
                    title: '编辑',
                    // 功能类型 menu菜单、button按钮
                    type: 'button',
                    // 功能唯一标识码
                    code: 'ConfigurableTableEdit',
                    status: 1,
                    sort: 1,
                    createTime: mock('@datetime'),
                    updateTime: mock('@datetime'),
                  },
                  {
                    id: '596101',
                    pid: '845695',
                    parentName: '配置型表格',
                    name: 'ConfigurableTableCreate',
                    title: '新增',
                    // 功能类型 menu菜单、button按钮
                    type: 'button',
                    // 功能唯一标识码
                    code: 'ConfigurableTableCreate',
                    status: 1,
                    sort: 1,
                    createTime: mock('@datetime'),
                    updateTime: mock('@datetime'),
                  },
                  {
                    id: '596102',
                    pid: '845695',
                    parentName: '配置型表格',
                    name: 'ConfigurableTableDelete',
                    title: '删除',
                    // 功能类型 menu菜单、button按钮
                    type: 'button',
                    // 功能唯一标识码
                    code: 'ConfigurableTableDelete',
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
      {
        id: '414403',
        pid: '413695',
        parentName: '框架使用',
        name: 'MessageSingleton',
        icon: 'ri-chat-4-line',
        title: 'message提示单例',
        path: '/demo/framework/messageSingleton',
        // 功能类型 menu菜单、button按钮
        type: 'menu',
        // 功能唯一标识码
        code: 'MessageSingleton',
        status: 1,
        sort: 3,
        createTime: mock('@datetime'),
        updateTime: mock('@datetime'),
      },
    ],
  },
  {
    id: '516301',
    pid: 0,
    parentName: '',
    name: 'Auth',
    icon: 'ri-user-settings-line',
    title: '权限管理',
    path: '/auth',
    // 功能类型 menu菜单、button按钮
    type: 'menu',
    // 功能唯一标识码
    code: 'Auth',
    status: 1,
    sort: 3,
    createTime: mock('@datetime'),
    updateTime: mock('@datetime'),
    children: [
      {
        id: '511230',
        pid: '516301',
        parentName: '权限管理',
        name: 'AuthUser',
        icon: 'ri-user-3-line',
        title: '用户管理',
        path: '/auth/user',
        // 功能类型 menu菜单、button按钮
        type: 'menu',
        // 功能唯一标识码
        code: 'AuthUser',
        status: 1,
        sort: 1,
        createTime: mock('@datetime'),
        updateTime: mock('@datetime'),
        children: [
          {
            id: '516412',
            pid: '511230',
            parentName: '用户管理',
            name: 'AuthUserList',
            title: '用户列表',
            path: '/auth/user/list',
            // 功能类型 menu菜单、button按钮
            type: 'menu',
            // 功能唯一标识码
            code: 'AuthUserList',
            status: 1,
            sort: 1,
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
        ],
      },
      {
        id: '518561',
        pid: '516301',
        parentName: '权限管理',
        name: 'AuthRole',
        icon: 'ri-shield-user-line',
        title: '角色管理',
        path: '/auth/role',
        // 功能类型 menu菜单、button按钮
        type: 'menu',
        // 功能唯一标识码
        code: 'AuthRole',
        status: 1,
        sort: 1,
        createTime: mock('@datetime'),
        updateTime: mock('@datetime'),
        children: [
          {
            id: '5189636',
            pid: '518561',
            parentName: '角色管理',
            name: 'AuthRoleList',
            title: '角色列表',
            path: '/auth/role/list',
            // 功能类型 menu菜单、button按钮
            type: 'menu',
            // 功能唯一标识码
            code: 'AuthRoleList',
            status: 1,
            sort: 1,
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
        ],
      },
      {
        id: '519156',
        pid: '516301',
        parentName: '权限管理',
        name: 'AuthFunc',
        icon: 'ri-function-line',
        title: '功能管理',
        path: '/auth/func',
        // 功能类型 menu菜单、button按钮
        type: 'menu',
        // 功能唯一标识码
        code: 'AuthFunc',
        status: 1,
        sort: 1,
        createTime: mock('@datetime'),
        updateTime: mock('@datetime'),
        children: [
          {
            id: '519554',
            pid: '519156',
            parentName: '功能管理',
            name: 'AuthFuncList',
            title: '功能列表',
            path: '/auth/func/list',
            // 功能类型 menu菜单、button按钮
            type: 'menu',
            // 功能唯一标识码
            code: 'AuthFuncList',
            status: 1,
            sort: 1,
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
        ],
      },
    ],
  },
]

const authMock = () => {
  // 系统用户列表模拟接口
  mock(/\/api\/v1\/auth\/user\/list/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统用户列表接口参数接收：', params)
    return {
      code: 200,
      message: '获取成功',
      data: {
        list: mock([
          {
            id: 360016,
            account: 'admin',
            department: '技术部',
            'status|1': ['正常', '禁用'],
            roleNames: ['管理员'],
            roleIds: [1],
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
          {
            id: 360017,
            account: 'libai',
            department: '市场部',
            'status|1': ['正常', '禁用'],
            roleNames: ['客服'],
            roleIds: [2],
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
          {
            id: 360018,
            account: 'wangbo',
            department: '财务部',
            'status|1': ['正常', '禁用'],
            roleNames: ['客服', '运营人员'],
            roleIds: [2, 3],
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
        ]),
        total: 3,
      },
    }
  })

  // 系统用户新增模拟接口
  mock(/\/api\/v1\/auth\/user\/create/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统用户新增接口参数接收：', params)
    return {
      code: 200,
      message: '新增成功',
    }
  })

  // 系统用户编辑模拟接口
  mock(/\/api\/v1\/auth\/user\/update/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统用户编辑接口参数接收：', params)
    return {
      code: 200,
      message: '编辑成功',
    }
  })

  // 系统角色列表模拟接口
  mock(/\/api\/v1\/auth\/role\/list/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统角色列表接口参数接收：', params)
    return {
      code: 200,
      message: '获取成功',
      data: {
        list: mock([
          {
            id: 350123,
            name: '管理员',
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
          {
            id: 561466,
            name: '客服',
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
          {
            id: 495562,
            name: '运营人员',
            createTime: mock('@datetime'),
            updateTime: mock('@datetime'),
          },
        ]),
        total: 3,
      },
    }
  })

  // 系统功能列表模拟接口
  mock(/\/api\/v1\/auth\/func\/listTree/, 'post', () => {
    return {
      code: 200,
      message: '获取成功',
      data: mock([
        {
          name: 'Home',
          title: '首页',
          path: '/home',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'Home',
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
        },
        {
          name: 'Components',
          title: '组件',
          path: '/demo/components',
          // 功能类型 menu菜单、button按钮
          type: 'menu',
          // 功能唯一标识码
          code: 'Components',
          createTime: mock('@datetime'),
          updateTime: mock('@datetime'),
          children: [
            {
              name: 'ComponentsBase',
              title: '基础组件',
              path: '/demo/components/base',
              // 功能类型 menu菜单、button按钮
              type: 'menu',
              // 功能唯一标识码
              code: 'ComponentsBase',
              createTime: mock('@datetime'),
              updateTime: mock('@datetime'),
              children: [
                {
                  name: 'Table',
                  title: '表格',
                  path: '/demo/components/base/table',
                  // 功能类型 menu菜单、button按钮
                  type: 'menu',
                  // 功能唯一标识码
                  code: 'Table',
                  createTime: mock('@datetime'),
                  updateTime: mock('@datetime'),
                  children: [
                    {
                      name: 'ConfigurableTable',
                      title: '配置型表格',
                      path: '/demo/components/base/table/configurableTable',
                      // 功能类型 menu菜单、button按钮
                      type: 'menu',
                      // 功能唯一标识码
                      code: 'ConfigurableTable',
                      createTime: mock('@datetime'),
                      updateTime: mock('@datetime'),
                      children: [
                        {
                          name: 'ConfigurableTableDetail',
                          title: '详情',
                          // 功能类型 menu菜单、button按钮
                          type: 'button',
                          // 功能唯一标识码
                          code: 'ConfigurableTableDetail',
                          createTime: mock('@datetime'),
                          updateTime: mock('@datetime'),
                        },
                      ],
                    },
                  ],
                },
              ],
            },
          ],
        },
      ]),
    }
  })

  // 系统角色新增模拟接口
  mock(/\/api\/v1\/auth\/role\/create/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统角色新增接口参数接收：', params)
    return {
      code: 200,
      message: '新增成功',
    }
  })

  // 系统角色编辑模拟接口
  mock(/\/api\/v1\/auth\/role\/update/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统角色编辑接口参数接收：', params)
    return {
      code: 200,
      message: '编辑成功',
    }
  })

  // 系统功能列表模拟接口
  mock(/\/api\/v1\/auth\/func\/list/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统功能列表接口参数接收：', params)
    return {
      code: 200,
      message: '获取成功',
      data: mock(authFuncList),
    }
  })

  // 系统功能新增模拟接口
  mock(/\/api\/v1\/auth\/func\/create/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统功能新增接口参数接收：', params)
    return {
      code: 200,
      message: '新增成功',
    }
  })

  // 系统功能编辑模拟接口
  mock(/\/api\/v1\/auth\/func\/update/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统功能编辑接口参数接收：', params)
    return {
      code: 200,
      message: '编辑成功',
    }
  })

  // 系统用户权限菜单列表模拟接口
  mock(/\/api\/v1\/auth\/menus/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('系统用户权限菜单列表接口参数接收：', params)
    return {
      code: 200,
      message: '获取成功',
      data: [],
    }
  })
}

export default authMock
