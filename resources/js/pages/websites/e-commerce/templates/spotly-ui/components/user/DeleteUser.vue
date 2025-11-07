<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/fields';
import InputError from '@/components/ui/fields/InputError.vue';
import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    password: '',
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('website.profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="flex flex-col gap-6">
        <HeadingSmall
            class="web-border-color"
            titleClass="web-text-active"
            :title="$t('delete.account')"
            descriptionClass="web-text-body-muted"
            :description="$t('delete.subtitle')"
        />

        <div class="space-y-4 rounded-md border border-red-300/50 bg-red-300/20 p-4 dark:border-red-500/10 dark:bg-red-700/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-semibold">{{ $t('warning') }}</p>
                <p class="web-text-body-muted text-sm">{{ $t('warning.subtitle') }}</p>
            </div>

            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive" class="web-bg-danger web-text-for-danger">{{ $t('delete.account') }}</Button>
                </DialogTrigger>

                <DialogScrollContent class="web-bg-body web-border-color">
                    <form class="space-y-4" @submit="deleteUser">
                        <DialogHeader class="web-bg-body web-border-color">
                            <DialogTitle class="web-text-active">{{ $t('delete.are.sure') }}</DialogTitle>
                        </DialogHeader>

                        <div class="px-4">
                            <DialogDescription class="web-text-body-muted">
                                {{ $t('delete.desc') }}
                            </DialogDescription>

                            <div class="grid gap-2 pt-5">
                                <Label for="password" class="sr-only web-text-body-muted">{{ $t('password') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="password"
                                    type="password"
                                    name="password"
                                    v-model="form.password"
                                    :placeholder="$t('password')"
                                />
                                <InputError :message="form.errors.password" />
                            </div>
                        </div>

                        <DialogFooter class="web-bg-body web-border-color">
                            <DialogClose as-child>
                                <Button variant="secondary" class="web-bg-secondary" @click="closeModal">{{ $t('myWebsites.cancel') }}</Button>
                            </DialogClose>

                            <Button variant="destructive" type="submit" :disabled="form.processing" class="web-bg-danger web-text-for-danger">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                {{ $t('delete.account') }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogScrollContent>
            </Dialog>
        </div>
    </div>
</template>
