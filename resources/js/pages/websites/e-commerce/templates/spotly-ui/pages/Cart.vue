<script setup lang="ts">
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
import { Checkbox, Input, InputError, PhoneNumberField, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { SharedData } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ArrowUp, LoaderCircle, Minus, Plus, ShoppingCart, Trash2, TriangleAlert } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import Layout from './Layout.vue';

const props = defineProps<{
    iniItems: Record<string, any>;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    iniCartItemsCount: number;

    countries: Record<string, any>;
    cities: Record<string, any>;

    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const items = ref<Record<string, any>>(props.iniItems);
const cartItemsCount = ref<number>(props.iniCartItemsCount);

// For Delete
const deleteProcessing = ref<number | null>(null);
const removeItem = async (itemId: number) => {
    try {
        deleteProcessing.value = itemId;

        const response = await axios.delete(route('website.e-commerce.cart.removeItem'), {
            data: { itemId },
        });        

        if ('items' in response.data.props && 'cartItemsCount' in response.data.props) {
            items.value = response.data.props.items;
            cartItemsCount.value = response.data.props.cartItemsCount;

            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        toast.fire({ icon: 'error', title: error.response?.data.message });
    } finally {
        deleteProcessing.value = null;
    }
};

// For Quantity
const changeQuantityProcessing = ref<number | null>(null);
const changeQuantity = async (item: any, action: string) => {
    if (item.quantity <= 0) return;
    if (action !== 'minus' && action !== 'plus') return;

    const newQuantity = action === 'minus' ? Number(item.quantity - 1) : Number(item.quantity + 1);

    if (newQuantity <= 0) return;

    try {
        changeQuantityProcessing.value = item.id;

        const response = await axios.patch(route('website.e-commerce.cart.changeQuantity'), {
            itemId: item.id,
            quantity: newQuantity,
        });

        if (response.data.props.items) {
            items.value = response.data.props.items;

            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        toast.fire({ icon: 'error', title: error.response?.data.message });
    } finally {
        changeQuantityProcessing.value = null;
    }
};

// For Checkout
const selectedItems = ref<{ id: number }[]>([]);
const toggleSelectItem = (val: boolean, itemId: number) => {
    const index = selectedItems.value.findIndex((item) => item.id === itemId);

    if (index > -1) {
        if (!val) {
            selectedItems.value.splice(index, 1);
        }
    } else {
        if (val) {
            selectedItems.value.push({ id: itemId });
        }
    }
};

const page = usePage<SharedData>();

const orderForm = useForm({
    phone_number: page.props.auth?.website_user?.phone_number ?? '',
    city: '',
    delivery_address: '',
    note: '',
});

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    src: item.flag,
}));

const mappedCities = Object.entries(props.cities).map(([key, city]: [string, any]) => ({
    value: key,
    label: page.props.lang === 'ar' ? city.ar : city.en,
}));

const checkoutProcessing = ref(false);
const cartCheckedOut = ref(false);
const checkout = async () => {
    try {
        checkoutProcessing.value = true;

        const payload = {
            items: selectedItems.value.length > 0 ? selectedItems.value : items.value.map((i: any) => ({ id: i.id })),
            ...orderForm.data(),
        };

        const response = await axios.post(route('website.e-commerce.cart.checkout'), payload);

        if (response.data.props.items) {
            items.value = response.data.props.items;
            cartItemsCount.value = response.data.props.cartItemsCount;
            cartCheckedOut.value = response.data.props.cartCheckedOut;

            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();

            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        toast.fire({ icon: 'error', title: error.response?.data.message });
    } finally {
        checkoutProcessing.value = false;
    }
};

const calculateTotalCheckoutPrice = (): number => {
    if (selectedItems.value.length > 0) {
        return items.value
            .filter((item: any) => selectedItems.value.some((sel) => sel.id === item.id))
            .reduce((sum: any, item: any) => sum + item.unit_price * item.quantity, 0);
    } else {
        return items.value.reduce((sum: any, item: any) => sum + item.unit_price * item.quantity, 0);
    }
};
</script>

<template>
    <Head :title="$t('my.title') + ' ' + $t('my.cart')" />

    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="cartItemsCount"
    >
        <section class="pt-28 pb-22" v-if="items && items.length > 0">
            <div class="mb-10 w-full text-center">
                <h2 class="web-text-active eco-section-title-underline w-fit text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('my.title') }} <span class="eco-gradient-text">{{ $t('my.cart') }}</span>
                </h2>
            </div>

            <div
                :class="[
                    'web-border-color border-s-3 border-e-3 border-b-3 border-dashed p-3 md:p-6',
                    true ? 'grid grid-cols-1 gap-3 lg:grid-cols-2' : 'flex h-full w-full items-center justify-center',
                ]"
            >
                <div
                    v-for="item in items"
                    :key="item.id"
                    class="group web-border-color relative flex flex-col rounded-md border sm:flex-row col-span-1 lg:col-span-1"
                    :class="deleteProcessing || changeQuantityProcessing ? 'pointer-events-none opacity-50' : ''"
                >
                    <div
                        class="web-border-color flex h-50 w-full min-w-full items-center justify-center overflow-hidden rounded-s-md border-e bg-[var(--bg_content_light)]
                        p-3 sm:max-h-40 sm:h-full sm:w-53 sm:min-w-53 sm:p-0 lg:w-53 lg:min-w-53 dark:bg-[var(--bg_content_dark)]"
                    >
                        <img
                            v-if="item.image_urls"
                            :src="item.image_urls[0]"
                            class="h-full transition-all duration-300 group-hover:scale-130 sm:w-full sm:rounded-s-md"
                        />
                    </div>

                    <div
                        class="flex w-full flex-col gap-1 rounded-e-md bg-[var(--bg_card_light)] p-2 sm:h-full sm:justify-between dark:bg-[var(--bg_card_dark)] overflow-x-hidden"
                    >
                        <div class="flex w-full items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="`/product/${encodeURIComponent(item.product.slug)}`"
                                    class="group/link web-text-active flex w-fit items-center gap-1 text-xl font-bold"
                                >
                                    {{ item.product.name }}
                                    <ArrowUp
                                        class="size-3.5 rotate-45 transition-transform duration-300 group-hover/link:translate-x-[2px] group-hover/link:-translate-y-[2px]"
                                    />
                                </Link>
                            </div>

                            <div class="flex items-center gap-2">
                                <Checkbox
                                    :value="item.id"
                                    :checked="selectedItems.includes(item.id)"
                                    @update:modelValue="(val) => typeof val === 'boolean' && toggleSelectItem(val, item.id)"
                                    class="size-5.5 cursor-pointer bg-[var(--bg_field_light)] shadow data-[state=checked]:bg-[var(--primary_light)] dark:bg-[var(--bg_field_dark)] data-[state=checked]:dark:bg-[var(--primary_dark)]"
                                />
                            </div>
                        </div>

                        <div class="flex items-center gap-1 w-full max-w-full overflow-x-auto custom-scrollbar">
                            <div v-for="value in item.attributes" :key="value" class="flex items-center gap-1 px-2 py-1 rounded-md border web-border-color flex-shrink-0 whitespace-nowrap">
                                <span v-if="value.color_code" class="web-text-body text-xs font-medium">
                                    {{ page.props.lang === 'ar' ? value.color_name_ar : value.color_name }}
                                </span>
                                <span v-else class="web-text-body text-xs font-medium">
                                    {{ page.props.lang === 'ar' ? value.attribute_value_name_ar : value.attribute_value_name }}
                                </span>
                            </div>
                        </div>

                        <div class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs pt-1">
                            <span>{{ $t('quantity') }}</span>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="removeItem(item.id)"
                                    class="web-bg-danger web-text-for-danger flex size-5 cursor-pointer items-center justify-center rounded-full opacity-50 transition-all duration-300 hover:opacity-100"
                                    :class="deleteProcessing ? 'cursor-default' : ''"
                                >
                                    <Trash2 v-if="deleteProcessing !== item.id" class="size-3" />
                                    <LoaderCircle v-else class="size-3 animate-spin" />
                                </button>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="web-bg-content web-text-body-muted flex size-5 cursor-pointer items-center justify-center rounded"
                                        @click="changeQuantity(item, 'minus')"
                                    >
                                        <Minus class="size-3.5" />
                                    </button>
                                    <p class="web-text-active text-base font-medium">
                                        <span v-if="changeQuantityProcessing !== item.id">{{ item.quantity }}</span>
                                        <LoaderCircle v-else class="size-4 animate-spin" />
                                    </p>
                                    <button
                                        type="button"
                                        class="web-bg-content web-text-body-muted flex size-5 cursor-pointer items-center justify-center rounded"
                                        @click="changeQuantity(item, 'plus')"
                                    >
                                        <Plus class="size-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                <span>{{ $t('unit.price') }}</span>
                                {{ item.unit_price }}$
                            </p>
                            <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                <span>{{ $t('total.price') }}</span>
                                <span class="web-text-active-link text-base font-bold">{{ item.unit_price * item.quantity }}$</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex w-full items-center justify-end gap-2">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button type="button" class="eco-glow-button web-bg-primary web-text-for-primary">
                            {{ selectedItems.length > 0 ? $t('checkout.selected') : $t('checkout.all') }}
                        </Button>
                    </DialogTrigger>

                    <DialogScrollContent class="web-bg-body web-border-color lg:max-w-2xl">
                        <DialogHeader class="web-bg-dropdown web-border-color">
                            <DialogTitle class="web-text-active">{{ $t('checkout.cart') }}</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <form class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2">
                            <div
                                v-if="!page.props.auth.website_user"
                                class="flex flex-col gap-2 rounded-md border border-[var(--danger_light)]/15 bg-[var(--danger_light)]/10 p-4 md:col-span-2 dark:border-[var(--danger_dark)]/15 dark:bg-[var(--danger_dark)]/10"
                            >
                                <h1 class="web-text-danger flex items-center gap-1 font-bold">
                                    <TriangleAlert class="size-4.5" />
                                    {{ $t('warning') }}
                                </h1>

                                <div class="web-text-body text-sm font-medium">
                                    {{ $t('placing.as') }} <b class="web-text-active">{{ $t('guest') }}</b
                                    >. {{ $t('warning.title') }}
                                    <Link href="/login" class="web-text-active-link underline">{{ $t('logging.in') }}</Link> {{ $t('or') }}
                                    <Link href="/register" class="web-text-active-link underline">{{ $t('creating.account') }}</Link>
                                    {{ $t('better.experience"') }}.
                                </div>
                            </div>

                            <div class="grid gap-1.5 md:col-span-2">
                                <Label class="web-text-body-muted" for="delivery_address">{{ $t('delivery.address.required') }}</Label>
                                <Input
                                    class="web-bg-field web-border-color web-text-active"
                                    id="delivery_address"
                                    v-model="orderForm.delivery_address"
                                />
                                <InputError :message="orderForm.errors.delivery_address" class="mt-1" />
                            </div>

                            <div class="grid gap-1.5">
                                <Label class="web-text-body-muted" for="city">{{ $t('city.required') }}</Label>
                                <SelectWithSearch
                                    class="web-bg-field web-border-color web-text-active"
                                    id="city"
                                    v-model="orderForm.city"
                                    :options="mappedCities"
                                />
                                <InputError :message="orderForm.errors.city" class="mt-1" />
                            </div>

                            <div class="grid gap-1.5">
                                <Label class="web-text-body-muted" for="phone_number">{{ $t('phone_number.required') }}</Label>
                                <PhoneNumberField
                                    selectClass="web-bg-field web-border-color web-text-active"
                                    inputClass="web-bg-field web-border-color web-text-active"
                                    id="phone_number"
                                    v-model="orderForm.phone_number"
                                    :options="mappedCountries ?? []"
                                />
                                <InputError :message="orderForm.errors.phone_number" class="mt-1" />
                            </div>

                            <div class="grid gap-1.5 md:col-span-2">
                                <Label class="web-text-body-muted" for="note">{{ $t('note') }}</Label>
                                <Textarea class="web-bg-field web-border-color web-text-active" id="note" v-model="orderForm.note" :maxlength="255" />
                                <InputError :message="orderForm.errors.note" class="mt-1" />
                            </div>
                        </form>

                        <div class="flex w-full items-center gap-2 p-4">
                            <span class="text-sm">{{ $t('total.price.without.fee') }}</span>
                            <span class="font-bold web-text-active-link">{{ calculateTotalCheckoutPrice() }}$</span>
                        </div>

                        <DialogFooter class="web-bg-dropdown web-border-color">
                            <DialogClose as-child>
                                <Button variant="secondary" class="web-bg-secondary web-text-active">{{ $t('myWebsites.cancel') }}</Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                :disabled="
                                    checkoutProcessing ||
                                    !orderForm.phone_number.trim() ||
                                    !orderForm.delivery_address.trim() ||
                                    !orderForm.city.trim()
                                "
                                class="eco-glow-button web-bg-primary web-text-for-primary"
                                @click="checkout"
                            >
                                <LoaderCircle v-if="checkoutProcessing" class="h-4 w-4 animate-spin" />
                                <span>{{ $t('myWebsites.submit') }}</span>
                            </Button>
                        </DialogFooter>
                    </DialogScrollContent>
                </Dialog>
            </div>
        </section>

        <section class="pt-28 pb-22" v-else>
            <div
                class="web-border-color mt-10 flex flex-col items-center justify-center border-s-3 border-e-3 border-b-3 border-dashed px-3 py-20 md:px-3"
            >
                <ShoppingCart class="web-text-body-muted mb-6 size-15" />

                <template v-if="!cartCheckedOut">
                    <h2 class="mb-1 text-xl font-semibold">{{ $t('client.payments.cart_empty') }}</h2>
                    <p class="web-text-body-muted mb-6 text-center text-sm">
                        {{ $t('empty.cart.message') }}
                    </p>
                </template>
                <template v-else>
                    <h2 class="mb-1 text-xl font-semibold text-green-600">{{ $t('order.placed') }}</h2>
                </template>

                <Link href="/shop" class="web-bg-primary web-text-for-primary rounded-md px-4 py-2 text-sm font-medium">
                    <span>{{ $t('go.to.shop') }}</span>
                </Link>
            </div>
        </section>
    </Layout>
</template>
