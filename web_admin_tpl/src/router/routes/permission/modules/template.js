/**
 * 模板管理模块路由
 */

// 列表模板管理模块路由
export const listTpl = {
  path: '/template/listTpl',
  name: 'ListTemplate',
  redirect: '/template/listTpl/list',
  meta: {
    icon: 'ri-file-list-line',
    title: '列表模板管理',
  },
  children: [
    {
      path: '/template/listTpl/list',
      name: 'ListTemplateList',
      component: () => import('@/views/template/listTpl/list/index.vue'),
      meta: {
        title: '列表模板管理',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/listTpl/add',
      name: 'AddListTemplate',
      component: () => import('@/views/template/listTpl/add/index.vue'),
      meta: {
        title: '添加列表模板',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/listTpl/edit',
      name: 'EditListTemplate',
      component: () => import('@/views/template/listTpl/add/index.vue'),
      meta: {
        title: '编辑列表模板',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/listTpl/detail',
      name: 'ListTemplateDetail',
      component: () => import('@/views/template/listTpl/add/index.vue'),
      meta: {
        title: '列表模板详情',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 详情模板管理模块路由
export const detailTpl = {
  path: '/template/detailTpl',
  name: 'DetailTemplate',
  redirect: '/template/detailTpl/list',
  meta: {
    icon: 'ri-article-line',
    title: '详情模板管理',
  },
  children: [
    {
      path: '/template/detailTpl/list',
      name: 'DetailTemplateList',
      component: () => import('@/views/template/detailTpl/list/index.vue'),
      meta: {
        title: '详情模板管理',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/detailTpl/add',
      name: 'AddDetailTemplate',
      component: () => import('@/views/template/detailTpl/add/index.vue'),
      meta: {
        title: '添加详情模板',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/detailTpl/edit',
      name: 'EditDetailTemplate',
      component: () => import('@/views/template/detailTpl/add/index.vue'),
      meta: {
        title: '编辑详情模板',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/template/detailTpl/detail',
      name: 'DetailTemplateDetail',
      component: () => import('@/views/template/detailTpl/add/index.vue'),
      meta: {
        title: '详情模板详情',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 模板管理模块总路由集成
const template = {
  path: '/template',
  name: 'Template',
  redirect: '/template/info',
  meta: {
    icon: 'ri-file-text-line',
    title: '模板管理',
  },
  children: [listTpl, detailTpl],
}

export default template
