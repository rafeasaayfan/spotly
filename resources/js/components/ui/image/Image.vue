<script setup lang="ts">
import { ref, computed } from 'vue';
import { cn } from '@/lib/utils'
import { watch } from 'vue';

const props = withDefaults(defineProps<{
  src?: string;
  alt?: string;
  class?: string;
  withTeleport?: boolean
}>(), {
  withTeleport: false
});

// Modal toggle
const show = ref(false);

// Fallback image
const imageSrc = computed(() => props.src || '/images/default-image.avif');

watch(show, (value) => {
  if (value) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});
</script>

<template>
  <div class="cursor-pointer">
    <!-- Thumbnail -->
    <img
      :src="imageSrc"
      :alt="props.alt || 'No image'"
      :class="cn('max-w-full max-h-full object-cover rounded-md hover:-translate-y-1 hover:shadow-lg cursor-pointer transition duration-300', props.class)"
      @click="show = true"
    />

    <!-- Fullscreen Preview -->
    <Teleport v-if="props.withTeleport" to="body">
      <Transition name="fade">
        <div
          v-if="show"
          class="fixed inset-0 bg-black bg-opacity-80 p-5 flex items-center justify-center z-[9999]"
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
    </Teleport>

    <Transition v-else name="fade">
      <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-80 p-5 flex items-center justify-center z-[9999]"
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
