<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { Digits, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { AlertTriangle, LoaderCircle } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    websiteId: number;
}>();

const form = useForm<Record<string, any>>({
    code: '',
});

const timer = ref(0);
const interval = ref(0);

const startTimer = (time?: number) => {
  timer.value = time ?? 180;
  interval.value = setInterval(() => {
    if (timer.value > 0) {
      timer.value--
    } else {
      clearInterval(interval.value)
    }
  }, 1000)
}

const isCodeSended = ref(true);
const sendingCode = ref(false);

const checkLastOtp = async () => {
    try {
        const response = await axios.post(route('client.myWebsite.checkLastOtp', props.websiteId));
        
        if (response.data?.success) {
            if(response.data?.props?.timer) {
                startTimer(response.data?.props?.timer);
                isCodeSended.value = false;
            }
        }

    } catch (error: any) {
        console.log(error);
        
        toast.fire({ icon: 'error', title: error.response?.data.message });
    }
}

onMounted(() => {
    checkLastOtp();
});

const submit = () => {
    form.delete(route('client.myWebsite.destroy', props.websiteId),{
        onError: (error: any) => {
            toast.fire({ icon: error.toastType, title: error.message });
        }
    });
};

const sendCode = async () => {
    sendingCode.value = true;
    try {
        const response = await axios.post(route('client.myWebsite.sendOtp', props.websiteId));
        
        if (response.data?.success) {
            startTimer();
            toast.fire({ icon: 'success', title: response.data?.message });
            isCodeSended.value = false;
        } else {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
            toast.fire({ icon: 'error', title: response.data?.message });
        }

    } catch (error: any) {
        toast.fire({ icon: 'error', title: error.response?.data.message });
    }

    sendingCode.value = false;
};
</script>

<template>
    <form class="mt-4 flex flex-col gap-6" @submit.prevent="submit">
        <p class="flex items-center gap-1.5 px-4 font-medium text-yellow-600">
            <AlertTriangle class="size-5" />
            <span class="text-sm sm:text-md">You can request a code only 3 times per hour!</span>
        </p>

        <div class="flex flex-col gap-1 px-4">
            <Label for="code" class="mb-1">Code</Label>
            <div class="flex w-full items-center justify-between sm:gap-2">
                <Digits v-model="form.code" />
            </div>
            <div class="flex items-center gap-2" :class="form.errors?.code ? 'justify-between' : 'justify-end'">
                <InputError v-if="form.errors?.code" :message="form.errors.code" />

                <Button type="button" variant="link" size="sm" class="mt-1 h-fit p-0" :disabled="form.processing || timer > 0 || sendingCode" @click="sendCode">
                    <span v-if="sendingCode" class="flex items-center gap-2">
                        <LoaderCircle class="size-3.5 animate-spin" />
                        Sending...
                    </span>
                    <span v-else-if="timer === 0">Send my code</span>
                    <span v-else>Resend in {{ timer }}s</span>
                </Button>
            </div>
        </div>

        <DialogFooter>
            <DialogClose as-child>
                <Button type="button">Cancel</Button>
            </DialogClose>
            <Button type="submit" variant="destructive" :disabled="isCodeSended || form.processing || sendingCode">
                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                Submit
            </Button>
        </DialogFooter>
    </form>
</template>
