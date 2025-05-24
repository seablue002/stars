/**
 * 系统管理模块路由
 */

// 配置管理模块路由
export const configs = {
  path: '/system/configs',
  name: 'SystemConfigs',
  redirect: '/system/configs/run',
  meta: {
    icon: 'ri-tools-line',
    title: '配置管理',
  },
  children: [
    {
      path: '/system/configs/run',
      name: 'SystemConfigsRun',
      component: () => import('@/views/system/configs/run/index.vue'),
      meta: {
        title: '运行配置',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 系统管理模块总路由集成
const system = {
  path: '/system',
  name: 'System',
  redirect: '/system/configs',
  meta: {
    icon: 'ri-dashboard-3-line',
    title: '系统管理',
  },
  children: [configs],
}

export default system
