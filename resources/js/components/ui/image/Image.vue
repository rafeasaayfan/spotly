<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps<{
  src?: string;
  alt?: string;
  class?: string;
}>();

// Modal toggle
const show = ref(false);

// Fallback image
const imageSrc = computed(() => props.src || '/images/default-image.avif');
</script>

<template>
  <div class="cursor-pointer">
    <!-- Thumbnail -->
    <img
      :src="imageSrc"
      :alt="props.alt || 'No image'"
      :class="['max-w-72 max-h-52 object-cover rounded-md cursor-pointer hover:scale-105 transition duration-300', props.class]"
      @click="show = true"
    />

    <!-- Fullscreen Preview -->
    <Transition name="fade">
      <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-80 p-5 flex items-center justify-center z-50"
        @click.self="show = false"
      >
        <img
          :src="imageSrc"
          :alt="props.alt || 'Full image'"
          class="max-w-full max-h-full rounded-lg cursor-pointer"
          @click="show = false"
        />
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
