<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white dark:bg-sidebar rounded-lg shadow-lg max-w-2xl w-full relative">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold">
          <slot name="title" />
        </h3>
        <button @click="$emit('close')" class="cursor-pointertext-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl">&times;</button>
      </div>
      <div class="p-6">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  open: boolean
}>()
defineEmits(['close'])
</script>
