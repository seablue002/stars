import { RouteRecordRaw } from 'vue-router'
import router from './index'
// import { treeFlat, filterRoutes } from '@/utils'

/* 全局界面结构布局层次的页面组件 */
import Layout from '@/views/layout/Index.vue'
/* 全局实现路由多层嵌套菜单内容显示层次的页面组件 */
import BrigeRouterView from '@/views/layout/BrigeRouterView.vue'

// 错误、提示页面组件
import NoFound from '@/views/404.vue'

// 框架基础路由
export const baseRoutes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Layout',
    redirect: '/content/info/list'
    // redirect: '/dashboard',
    // component: Layout,
    // children: [
    //   {
    //     path: '/dashboard',
    //     component: () => import('@/views/dashboard/Index.vue'),
    //     meta: {
    //       title: '首页',
    //       icon: 'House'
    //     }
    //   }
    // ]
  },
  {
    path: '/login',
    component: () => import('@/views/Login.vue'),
    hidden: true,
    meta: {
      title: '系统登录'
    }
  }
]

// 需要根据角色权限动态显示的路由
export const asyncPermissionRoutes: Array<RouteRecordRaw> = [
  {
    path: '/content',
    redirect: '/content/info',
    component: Layout,
    meta: {
      title: '内容管理',
      icon: 'Reading'
    },
    children: [
      {
        path: '/content/info/list',
        component: () => import('@/views/content/info/list/Index.vue'),
        meta: {
          title: '信息管理',
          keepAlive: true
        }
      },
      {
        path: '/content/label/list',
        component: () => import('@/views/content/label/list/Index.vue'),
        meta: {
          title: '标签管理',
          keepAlive: true
        }
      },
      {
        path: '/content/info/add',
        component: () => import('@/views/content/info/add/Index.vue'),
        meta: {
          title: '添加信息',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/content/info/edit',
        component: () => import('@/views/content/info/add/Index.vue'),
        meta: {
          title: '编辑信息',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/content/column/list',
        component: () => import('@/views/content/column/list/Index.vue'),
        meta: {
          title: '栏目管理',
          keepAlive: true
        }
      },
      {
        path: '/content/column_extra_fields/list',
        component: () => import('@/views/content/column_extra_fields/list/Index.vue'),
        meta: {
          title: '栏目自定义字段',
          keepAlive: true
        }
      }
    ]
  },
  {
    path: '/tpl',
    redirect: '/tpl/list/list',
    component: Layout,
    meta: {
      title: '模板管理',
      icon: 'Document'
    },
    children: [
      {
        path: '/tpl/list/list',
        component: () => import('@/views/content/tpl/list/list/Index.vue'),
        meta: {
          title: '列表模板',
          keepAlive: true
        }
      },
      {
        path: '/tpl/list/add',
        component: () => import('@/views/content/tpl/list/add/Index.vue'),
        meta: {
          title: '添加列表模板',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl/list/edit',
        component: () => import('@/views/content/tpl/list/add/Index.vue'),
        meta: {
          title: '编辑列表模板',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl/list/detail',
        component: () => import('@/views/content/tpl/list/add/Index.vue'),
        meta: {
          title: '列表模板详情',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl/detail/list',
        component: () => import('@/views/content/tpl/detail/list/Index.vue'),
        meta: {
          title: '详情模板',
          keepAlive: true
        }
      },
      {
        path: '/tpl/detail/add',
        component: () => import('@/views/content/tpl/detail/add/Index.vue'),
        meta: {
          title: '添加详情模板',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl/detail/edit',
        component: () => import('@/views/content/tpl/detail/add/Index.vue'),
        meta: {
          title: '编辑详情模板',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl/detail/detail',
        component: () => import('@/views/content/tpl/detail/add/Index.vue'),
        meta: {
          title: '详情模板详情',
          keepAlive: true
        },
        hidden: true
      }
    ]
  },
  {
    path: '/tpl_var',
    redirect: '/tpl_var/list',
    component: Layout,
    meta: {
      title: '公共模板变量',
      icon: 'Star'
    },
    children: [
      {
        path: '/tpl_var/list',
        component: () => import('@/views/tpl_var/list/Index.vue'),
        meta: {
          title: '管理模板变量',
          keepAlive: true
        }
      },
      {
        path: '/tpl_var/add',
        component: () => import('@/views/tpl_var/add/Index.vue'),
        meta: {
          title: '添加模板变量',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl_var/edit',
        component: () => import('@/views/tpl_var/add/Index.vue'),
        meta: {
          title: '编辑模板变量',
          keepAlive: true
        },
        hidden: true
      },
      {
        path: '/tpl_var/detail',
        component: () => import('@/views/tpl_var/add/Index.vue'),
        meta: {
          title: '模板变量详情',
          keepAlive: true
        },
        hidden: true
      }
    ]
  },
  // {
  //   path: '/operate',
  //   redirect: '/operate/analysis',
  //   component: Layout,
  //   meta: {
  //     title: '运营',
  //     icon: 'DataLine'
  //   },
  //   children: [
  //     {
  //       path: '/operate/slider/list',
  //       component: () => import('@/views/operate/slider/list/Index.vue'),
  //       meta: {
  //         title: '轮播图管理',
  //         keepAlive: true
  //       }
  //     },
  //     {
  //       path: '/operate/slider/add',
  //       component: () => import('@/views/operate/slider/add/Index.vue'),
  //       meta: {
  //         title: '添加轮播图',
  //         keepAlive: true
  //       },
  //       hidden: true
  //     },
  //     {
  //       path: '/operate/slider/detail',
  //       component: () => import('@/views/operate/slider/add/Index.vue'),
  //       meta: {
  //         title: '轮播图详情',
  //         keepAlive: true
  //       },
  //       hidden: true
  //     },
  //     {
  //       path: '/operate/slider/edit',
  //       component: () => import('@/views/operate/slider/add/Index.vue'),
  //       meta: {
  //         title: '编辑轮播图',
  //         keepAlive: true
  //       },
  //       hidden: true
  //     }
  //   ]
  // },
  {
    path: '/setting',
    redirect: '/setting/auth',
    component: Layout,
    meta: {
      title: '设置',
      icon: 'SetUp'
    },
    children: [
      {
        path: '/setting/system',
        component: () => import('@/views/setting/system/Index.vue'),
        meta: {
          title: '系统设置',
          keepAlive: true
        }
      },
      {
        path: '/setting/auth',
        redirect: '/setting/auth/user/list',
        component: BrigeRouterView,
        props: route => ({ keepAlive: route.meta.keepAlive }),
        meta: {
          title: '权限管理',
          keepAlive: false
        },
        children: [
          {
            path: '/setting/auth/user/list',
            component: () => import('@/views/setting/auth/user/list/Index.vue'),
            meta: {
              title: '管理员列表',
              keepAlive: true
            }
          },
          {
            path: '/setting/auth/user/add',
            component: () => import('@/views/setting/auth/user/add/Index.vue'),
            meta: {
              title: '添加管理员',
              keepAlive: true
            },
            hidden: true
          },
          {
            path: '/setting/auth/user/edit',
            component: () => import('@/views/setting/auth/user/add/Index.vue'),
            meta: {
              title: '编辑管理员',
              keepAlive: true
            },
            hidden: true
          },
          {
            path: '/setting/auth/role/list',
            component: () => import('@/views/setting/auth/role/list/Index.vue'),
            meta: {
              title: '角色列表',
              keepAlive: true
            }
          },
          {
            path: '/setting/auth/role/add',
            component: () => import('@/views/setting/auth/role/add/Index.vue'),
            meta: {
              title: '添加角色',
              keepAlive: true
            },
            hidden: true
          },
          {
            path: '/setting/auth/role/edit',
            component: () => import('@/views/setting/auth/role/add/Index.vue'),
            meta: {
              title: '编辑角色',
              keepAlive: true
            },
            hidden: true
          },
          {
            path: '/setting/auth/rule/list',
            component: () => import('@/views/setting/auth/rule/list/Index.vue'),
            meta: {
              title: '规则列表',
              keepAlive: true
            }
          },
          {
            path: '/setting/auth/rule/add',
            component: () => import('@/views/setting/auth/rule/add/Index.vue'),
            meta: {
              title: '添加规则',
              keepAlive: true
            },
            hidden: true
          },
          {
            path: '/setting/auth/rule/edit',
            component: () => import('@/views/setting/auth/rule/add/Index.vue'),
            meta: {
              title: '编辑规则',
              keepAlive: true
            },
            hidden: true
          }
        ]
      }
    ]
  },
  {
    path: '/system',
    redirect: '/system/config',
    component: Layout,
    meta: {
      title: '系统',
      icon: 'Odometer'
    },
    children: [
      {
        path: '/system/config',
        redirect: '/system/config/run',
        component: BrigeRouterView,
        props: route => ({ keepAlive: route.meta.keepAlive }),
        meta: {
          title: '配置管理',
          keepAlive: false
        },
        children: [
          {
            path: '/system/config/run',
            component: () => import('@/views/system/config/run/category/Index.vue'),
            meta: {
              title: '运行配置列表',
              keepAlive: true
            }
          }
        ]
      }
    ]
  }
]

// 错误、提示（404页面）等页面路由
export const commonRoutes: Array<RouteRecordRaw> = [
  {
    path: '/:pathMatch(.*)*',
    component: Layout,
    hidden: true,
    meta: {
      title: '404页面'
    },
    children: [
      {
        path: '/:pathMatch(.*)*',
        component: NoFound
      }
    ]
  }
]

/**
 * 根据用户权限匹配获得可以访问的菜单路由，注册到路由系统，以及添加错误、提示(404页面)到路由系统
 * @param usePermissionRoutes 后端api接口根据用户权限返回的权限路由信息
 */
export const matchPermissionRoutes = (usePermissionRoutes: any[]): any => {
  const matchedPermissionRoutes = usePermissionRoutes
  matchedPermissionRoutes.map((menu: any) => {
    router.addRoute(menu)
    return menu
  })
  commonRoutes.forEach((menu) => {
    router.addRoute(menu)
  })
}
