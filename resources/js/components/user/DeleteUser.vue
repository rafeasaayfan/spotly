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

    form.delete(route('profile.destroy'), {
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
        <HeadingSmall title="Delete account" description="Delete your account and all of its resources" />

        <div class="space-y-4 rounded-md border border-red-300/50 bg-red-300/20 dark:bg-red-700/10 p-4 dark:border-red-500/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-semibold">Warning</p>
                <p class="text-body-muted text-sm">Please proceed with caution, this cannot be undone.</p>
            </div>

            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive">Delete account</Button>
                </DialogTrigger>

                <DialogScrollContent>
                    <form class="space-y-4" @submit="deleteUser">
                        <DialogHeader>
                            <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                        </DialogHeader>

                        <div class="px-4">
                            <DialogDescription>
                                Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your
                                password to confirm you would like to permanently delete your account.
                            </DialogDescription>

                            <div class="grid gap-2 pt-5">
                                <Label for="password" class="sr-only">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    v-model="form.password"
                                    placeholder="Password"
                                />
                                <InputError :message="form.errors.password" />
                            </div>
                        </div>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="secondary" @click="closeModal">Cancel</Button>
                            </DialogClose>

                            <Button variant="destructive" type="submit" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                Delete account
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogScrollContent>
            </Dialog>
        </div>
    </div>
</template>
