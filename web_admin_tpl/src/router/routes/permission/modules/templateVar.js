/**
 * 公共变量管理模块路由
 */

// 模板变量管理模块路由
export const templateVar = {
  path: '/publicVar/template',
  name: 'PublicVarTemplate',
  redirect: '/publicVar/template/list',
  meta: {
    icon: 'ri-t-box-line',
    title: '模板变量管理',
  },
  children: [
    {
      path: '/publicVar/template/list',
      name: 'PublicVarTemplateList',
      component: () => import('@/views/publicVar/template/list/index.vue'),
      meta: {
        title: '模板变量管理',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/publicVar/template/add',
      name: 'AddPublicVarTemplate',
      component: () => import('@/views/publicVar/template/add/index.vue'),
      meta: {
        title: '添加模板变量',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/publicVar/template/edit',
      name: 'EditPublicVarTemplate',
      component: () => import('@/views/publicVar/template/add/index.vue'),
      meta: {
        title: '编辑模板变量',
        cache: true,
        hidden: true,
      },
    },
    {
      path: '/publicVar/template/detail',
      name: 'PublicVarTemplateDetail',
      component: () => import('@/views/publicVar/template/add/index.vue'),
      meta: {
        title: '模板变量详情',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 公共变量管理模块总路由集成
const publicVar = {
  path: '/publicVar',
  name: 'PublicVar',
  redirect: '/publicVar/template',
  meta: {
    icon: 'ri-shapes-line',
    title: '变量管理',
  },
  children: [templateVar],
}

export default publicVar
