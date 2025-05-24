<template>
  <div class="copyright">
    <p>
      <span v-html="copyright"></span>
      &nbsp;<span>version {{ appVersion }}</span>
    </p>
  </div>
</template>

<script>
import { computed, defineComponent } from 'vue'
import { APP_VERSION } from '@/settings/config/app'
import useCommonStore from '@/store/modules/common'
import DOMPurify from 'dompurify'

// 保留 a 标签的 target 属性
DOMPurify.addHook('uponSanitizeElement', (node, data) => {
  if (data.tagName === 'a' && node.hasAttribute('target')) {
    node.setAttribute('target', node.getAttribute('target'))
  }
})

export default defineComponent({
  setup() {
    const commonStore = useCommonStore()
    const appVersion = APP_VERSION

    const copyright = computed(() => {
      const unsafeHtml = commonStore.config.systemConfig?.copyright?.value || ''
      // 允许 a 标签的 target 属性
      return DOMPurify.sanitize(unsafeHtml, {
        ADD_ATTR: ['target'],
      })
    })
    return {
      appVersion,
      copyright,
    }
  },
})
</script>
<style lang="scss" scoped>
.copyright {
  display: flex;
  justify-content: center;
  padding: 0 15px 15px;
  background: #f0f2f5;
}
</style>
