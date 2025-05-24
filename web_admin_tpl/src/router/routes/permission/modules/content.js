/**
 * 内容信息管理模块路由
 */

// 信息管理模块路由
export const info = {
  path: '/content/info',
  name: 'Info',
  redirect: '/content/info/list',
  meta: {
    icon: 'ri-article-line',
    title: '信息管理',
  },
  children: [
    {
      path: '/content/info/list',
      name: 'InfoList',
      component: () => import('@/views/content/info/list/index.vue'),
      meta: {
        title: '信息管理',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/content/info/add',
      name: 'AddInfo',
      component: () => import('@/views/content/info/add/index.vue'),
      meta: {
        title: '添加信息',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/content/info/edit',
      name: 'EditInfo',
      component: () => import('@/views/content/info/add/index.vue'),
      meta: {
        title: '编辑信息',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/content/info/detail',
      name: 'InfoDetail',
      component: () => import('@/views/content/info/add/index.vue'),
      meta: {
        title: '信息详情',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 标签管理模块路由
export const label = {
  path: '/content/label',
  name: 'Label',
  redirect: '/content/label/list',
  meta: {
    icon: 'ri-price-tag-3-line',
    title: '标签管理',
  },
  children: [
    {
      path: '/content/label/list',
      name: 'LabelList',
      component: () => import('@/views/content/label/list/index.vue'),
      meta: {
        title: '标签管理',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 栏目管理模块路由
export const column = {
  path: '/content/column',
  name: 'ContentColumn',
  redirect: '/content/column/list',
  meta: {
    icon: 'ri-layout-column-line',
    title: '栏目管理',
  },
  children: [
    {
      path: '/content/column/list',
      name: 'ColumnList',
      component: () => import('@/views/content/column/list/index.vue'),
      meta: {
        title: '栏目管理',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 栏目自定义字段管理模块路由
export const columnExtraFields = {
  path: '/content/columnExtraFields',
  name: 'ColumnExtraFields',
  redirect: '/content/columnExtraFields/list',
  meta: {
    icon: 'ri-t-box-line',
    title: '栏目自定义字段',
  },
  children: [
    {
      path: '/content/columnExtraFields/list',
      name: 'ColumnExtraFieldsList',
      component: () =>
        import('@/views/content/columnExtraFields/list/index.vue'),
      meta: {
        title: '栏目自定义字段',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 权限管理模块总路由集成
const content = {
  path: '/content',
  name: 'Content',
  redirect: '/content/info',
  meta: {
    icon: 'ri-book-open-line',
    title: '内容管理',
  },
  children: [info, label, column, columnExtraFields],
}

export default content
