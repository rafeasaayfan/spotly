<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose } from '@/components/ui/dialog';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { AlertTriangle } from 'lucide-vue-next';

const props = defineProps<{
    websiteId: number;
}>();

const form = useForm<Record<string, any>>({
    code: '',
});

const submit = () => {
    form.delete(route('client.myWebsite.destroy', props.websiteId));
};

const sendCode = async () => {
    try {
        const response = await axios.post(route('client.myWebsite.sendOtp'));
        if (response.data?.success) {
            toast.fire({ icon: 'success', title: response.data?.message });
        } else {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
            toast.fire({ icon: 'error', title: response.data?.message });
        }
    } catch (error: any) {
        console.log(error);
    }
};
</script>

<template>
    <form class="mt-4 flex flex-col gap-4" @submit.prevent="submit">
        <p class="flex items-center gap-1.5 px-4 font-medium text-yellow-600">
            <AlertTriangle class="size-5" />
            <span>You can request a code only 3 times per hour!</span>
        </p>

        <div class="flex flex-col gap-1 px-4">
            <Label for="code" class="mb-1">Code</Label>
            <Input v-model="form.code" id="code" placeholder="Enter your code" required />
            <div class="flex items-center gap-2" :class="form.errors?.code ? 'justify-between' : 'justify-end'">
                <InputError v-if="form.errors?.code" v-show="form.errors.code" />

                <Button type="button" variant="link" size="sm" class="mt-1 h-fit p-0" @click="sendCode()"> Send my code </Button>
            </div>
        </div>

        <div class="border-muted mt-1 flex items-center justify-end gap-3 border-t px-4 pt-3">
            <DialogClose as-child>
                <Button type="button">Cancel</Button>
            </DialogClose>
            <Button type="submit" variant="destructive">Submit</Button>
        </div>
    </form>
</template>



