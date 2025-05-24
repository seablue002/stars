/**
 * 配置管理模块路由
 */

// 系统配置管理模块路由
export const system = {
  path: '/settings/system',
  name: 'SettingsSystem',
  redirect: '/settings/system/all',
  meta: {
    icon: 'ri-settings-line',
    title: '系统配置',
  },
  children: [
    {
      path: '/settings/system/all',
      name: 'SettingsSystemAll',
      component: () => import('@/views/settings/system/all/index.vue'),
      meta: {
        title: '系统配置',
        cache: true,
        hidden: true,
      },
    },
  ],
}

// 配置管理模块总路由集成
const settings = {
  path: '/settings',
  name: 'Settings',
  redirect: '/settings/system',
  meta: {
    icon: 'ri-equalizer-line',
    title: '动态配置',
  },
  children: [system],
}

export default settings
