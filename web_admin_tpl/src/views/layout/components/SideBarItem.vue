<template>
  <div>
    <template
      v-for="(item, index) in menu"
      :key="item.path + index"
    >
      <el-sub-menu
        v-if="item.children && !item.hidden"
        :index="item.path"
      >
        <template v-slot:title>
          <el-icon v-if="item.meta.icon">
            <component :is="item.meta.icon" />
          </el-icon>
          <span>{{ item.meta.title }}</span>
        </template>
        <side-bar-item :menu="item.children"></side-bar-item>
      </el-sub-menu>
      <el-menu-item
        v-else-if="!item.hidden"
        :index="item.path"
      >
        <el-icon v-if="item.meta.icon">
          <component :is="item.meta.icon" />
        </el-icon>

        <template v-slot:title>
          <span>{{ item.meta.title }}</span>
        </template>
      </el-menu-item>
    </template>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'SideBarItem',
  props: {
    menu: {
      type: Array,
      default () {
        return []
      }
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
