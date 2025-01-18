<template>
  <router-view v-slot="{ Component}">
      <keep-alive v-if="keepAlive">
        <component v-if="keepAlive" :key="$route.fullPath" :is="Component" />
      </keep-alive>
      <component v-if="!keepAlive" :is="Component" />
  </router-view>
  <!-- <router-view></router-view> -->
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  props: {
    keepAlive: {
      type: Boolean,
      default: false
    }
  }
})
</script>>
