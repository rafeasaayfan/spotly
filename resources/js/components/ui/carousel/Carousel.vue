<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Button } from '../button'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { SharedData } from '@/types'
import { usePage } from '@inertiajs/vue3'
import { cn } from '@/lib/utils';

const page = usePage<SharedData>();

interface CarouselProps {
  items?: any[]
  autoplay?: boolean
  autoplaySpeed?: number
  showDots?: boolean
  showArrows?: boolean
  loop?: boolean
  parentClass?: string
  imgClass?: string
  title?: string
  description?: string
}

const props = withDefaults(defineProps<CarouselProps>(), {
  items: () => [],
  autoplay: true,
  autoplaySpeed: 5000,
  showDots: true,
  showArrows: true,
  loop: true,
  parentClass: '',
  imgClass: 'w-full h-full',
  title: '',
  description: ''
})

const emit = defineEmits<{
  slideChange: [index: number]
}>()

const currentIndex = ref(0)
const isTransitioning = ref(false)
const autoplayInterval = ref<number | null>(null)

const totalSlides = computed(() => props.items.length)
const canGoNext = computed(() => props.loop || currentIndex.value < totalSlides.value - 1)
const canGoPrev = computed(() => props.loop || currentIndex.value > 0)

const goToSlide = (index: number) => {
  if (index === currentIndex.value) return
  if (index < 0 || index >= totalSlides.value) return
  if (isTransitioning.value) return

  isTransitioning.value = true
  currentIndex.value = index

  emit('slideChange', index)

  setTimeout(() => {
    isTransitioning.value = false
  }, 300)
}

const nextSlide = () => {
  if (!canGoNext.value) return

  const nextIndex = currentIndex.value + 1
  if (nextIndex >= totalSlides.value) {
    if (props.loop) {
      goToSlide(0)
    }
  } else {
    goToSlide(nextIndex)
  }
}

const prevSlide = () => {
  if (!canGoPrev.value) return

  const prevIndex = currentIndex.value - 1
  if (prevIndex < 0) {
    if (props.loop) {
      goToSlide(totalSlides.value - 1)
    }
  } else {
    goToSlide(prevIndex)
  }
}

const startAutoplay = () => {
  if (!props.autoplay || totalSlides.value <= 1) return

  autoplayInterval.value = setInterval(() => {
    nextSlide()
  }, props.autoplaySpeed)
}

const stopAutoplay = () => {
  if (autoplayInterval.value) {
    clearInterval(autoplayInterval.value)
    autoplayInterval.value = null
  }
}

const handleMouseEnter = () => {
  if (props.autoplay) {
    stopAutoplay()
  }
}

const handleMouseLeave = () => {
  if (props.autoplay) {
    startAutoplay()
  }
}

onMounted(() => {
  startAutoplay()
})

onUnmounted(() => {
  stopAutoplay()
})
</script>

<template>
  <div :class="cn('relative overflow-hidden rounded-md', props.parentClass)" @mouseenter="handleMouseEnter"
    @mouseleave="handleMouseLeave">
    <!-- Main Carousel -->
    <div class="carousel-track flex w-full h-full" :style="{
      transform: page.props.lang === 'ar'
        ? `translateX(${currentIndex * 100}%)`
        : `translateX(-${currentIndex * 100}%)`,
      transition: isTransitioning ? 'transform 0.3s ease-in-out' : 'none'
    }">
      <div v-for="(item, index) in items" :key="index"
        class="carousel-slide w-full h-full flex-shrink-0 flex-grow-0 flex-basis-full">
        <slot name="slide" :item="item" :index="index">
          <div class="w-full h-full">
            <img :src="item.original_url ?? item" :alt="item.title || `Slide ${index + 1}`"
              :class="cn('object-cover rounded-md', props.imgClass)" />

            <div class="absolute bottom-0 top-0 start-0 text-center text-white z-20 pb-8">
              <h3 v-if="props.title" class="text-2xl font-bold mb-2">{{ props.title }}</h3>
              <p v-if="props.description" class="text-base text-white/80">{{ props.description }}</p>
            </div>
          </div>
        </slot>
      </div>
    </div>

    <!-- Navigation Arrows -->
    <div v-if="showArrows && totalSlides > 1" class="absolute bottom-0 end-0 p-3 flex gap-3 items-center">
      <Button @click="prevSlide" :disabled="!canGoPrev" class="size-8 md:size-10 rounded-full z-10"
        :class="{ 'disabled': !canGoPrev }">
        <ChevronLeft class="size-3.5 md:size-5" :class="page.props.lang === 'ar' ? 'rotate-180' : ''" />
      </Button>

      <Button @click="nextSlide" :disabled="!canGoNext" class="size-8 md:size-10 rounded-full z-10"
        :class="{ 'disabled': !canGoNext }">
        <ChevronRight class="size-3.5 md:size-5" :class="page.props.lang === 'ar' ? 'rotate-180' : ''" />
      </Button>
    </div>

    <!-- Dots Navigation -->
    <div v-if="showDots && totalSlides > 1"
      class="w-full absolute bottom-0 start-0 flex items-center justify-start sm:justify-center gap-1 ps-3 pb-1 z-10">
      <Button v-for="(item, index) in items" :key="index" @click="goToSlide(index)" type="button" size="icon"
        class="size-3.5 bg-white/50 hover:bg-white/80 rounded-full active:bg-white/90 active:ring-2 active:ring-[var(--primary)]"
        :class="{ 'size-4 bg-white': index === currentIndex }" :aria-label="`Go to slide ${index + 1}`" />
    </div>

    <!-- Progress Bar -->
    <!-- <div v-if="autoplay && totalSlides > 1" class="absolute top-0 left-0 right-0 h-1 bg-[var(--primary)]/60 z-10">
      <div class="h-full bg-destructive" :style="{
        width: `${((currentIndex + 1) / totalSlides) * 100}%`,
        transition: 'width 0.1s linear'
      }" />
    </div> -->

    <div class="absolute bottom-0 left-0 right-0 text-center h-8 text-white 
      bg-gradient-to-b from-transaprent to-black/80 backdrop-blur-[1px]">
    </div>
  </div>
</template>

<style scoped>
.carousel-track {
  will-change: transform;
}

.carousel-slide {
  will-change: transform;
}
</style>