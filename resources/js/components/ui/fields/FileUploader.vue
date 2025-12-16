<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Upload, XIcon } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const props = withDefaults(defineProps<{
  modelValue: File[] | File | string[] | string | Record<string, any> | null;
  multiple?: boolean;
  acceptedFileTypes?: string;
  name?: string;
  id?: string;
}>(), {
  multiple: false,
  acceptedFileTypes: 'image/jpeg, image/png, image/jpg, image/webp, image/svg+xml, image/avif',
  name: 'fileUpload',
  id: 'fileUpload',
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: File[] | File | string[] | string | Record<string, any> | null): void;
}>();

const page = usePage<SharedData>();

// PREVIEW LIST
const previews = ref<{ url: string; file?: File; id?: number }[]>([]);

// MODEL VALUE (initial load)
onMounted(() => {
  if (!props.modelValue) return;

  // ⭐ CASE 1: a single string (existing image URL)
  if (typeof props.modelValue === "string") {
    previews.value.push({ url: props.modelValue });
    return;
  }

  // ⭐ CASE 2: array of strings
  if (Array.isArray(props.modelValue) && typeof props.modelValue[0] === "string") {
    previews.value = (props.modelValue as string[]).map(v => ({ url: v }));
    return;
  }

  // ⭐ CASE 3: Record<string, any>
    if (
    typeof props.modelValue === "object" &&
    props.modelValue !== null &&
    ('original_url' in props.modelValue || Object.values(props.modelValue).some(v => v.original_url))
  ) {
    previews.value = Object.values(props.modelValue).map(v => ({
      url: v.original_url,
      id: v.id,
    }));
    return;
  }

  // ⭐ CASE 4: File or File[]
  const files: File[] = Array.isArray(props.modelValue)
    ? (props.modelValue as File[])
    : [props.modelValue as File];

  previews.value = files
    .filter(file => file instanceof File)
    .map(file => ({
      file,
      url: URL.createObjectURL(file),
    }));
});

function onSelect(e: Event) {
  const input = e.target as HTMLInputElement;
  if (!input.files?.length) return;

  const selected = Array.from(input.files);

  if (!props.multiple) {
    previews.value = [];
  }

  // update preview
  selected.forEach(file => {
    previews.value.push({
      file: file,
      url: URL.createObjectURL(file),
    });
  });

  emit("update:modelValue", props.multiple ? previews.value : previews.value[0]['file'] ?? null);
}

function removeFile(index: number) {
  previews.value.splice(index, 1);

  emit("update:modelValue", previews.value.length > 0 ? previews.value : null);
}
</script>

<template>
  <div class="w-full p-4 border-3 border-dashed border-muted rounded-md flex flex-col gap-3">
    <!-- File input -->
    <input type="file" class="hidden" :multiple="props.multiple" :accept="props.acceptedFileTypes" :id="props.id"
      :name="props.name" @change="onSelect" />

    <!-- Click / Drop Area -->
    <label :for="props.id"
      class="w-full flex items-center justify-center gap-2 cursor-pointer bg-field rounded-md py-4 text-body border border-muted">
      <Upload class="size-4" />
      <span class="text-sm">
        {{ page.props.lang === 'ar' ? 'إرفع الملفات أو انقر للتحميل' : 'Drop files or click to upload' }}
      </span>
    </label>

    <!-- PREVIEW -->
    <div class="flex items-center gap-4 flex-wrap" v-if="previews.length > 0">
      <div v-for="(item, index) in previews" :key="index"
        class="relative w-full sm:w-50 sm:h-50 rounded-md p-3 border-2 border-muted">
        <img :src="item.url" class="w-full h-full object-cover rounded-md" />

        <button type="button"
          class="absolute -top-2.5 -end-2.5 bg-destructive rounded-full p-1 text-white cursor-pointer"
          @click="removeFile(index)">
          <XIcon class="size-3.5" />
        </button>
      </div>
    </div>
  </div>
</template>
