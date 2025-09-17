<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, Radio } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { Check, CheckCircle, LoaderCircle, LucideShoppingCart, PackageSearch, Search, SearchX } from 'lucide-vue-next';
import { computed, ref, watch, watchEffect } from 'vue';

interface PaymentFormProps {
    website_id: number;
    website_name: string;
    plan_id: number;
    plan_name: string;
    plan_price: number;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Make a Payment',
        href: '/dashboard/make-payment',
    },
];

const props = defineProps<{
    websites: Record<string, any>;
    paymentWebsite: Record<string, any>;
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

const query = route().queryParams;
const search = ref(query.search || '');
const status = ref(query.status as string || '');

watch(search, (val) => {
    fetchPlansBySearch(val as string, status.value as string);
});
watch(status, (val) => {
    fetchPlansBySearch(search.value as string, val as string);
});

const fetchPlansBySearch = (searchTerm: string, status: string) => {
    router.get(
        route('client.makePayment'),
        {
            search: searchTerm,
            status: status,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const plans = ref<Record<string, any>>([]);
const isFetching = ref(false);

const selectedWebsite = ref<{ id: number; name: string } | null>(null);
const selectedPlan = ref<{ id: number; name: string; price: number } | null>(null);

const paymentCart = ref<PaymentFormProps[]>([]);

// const isPaying = ref(false);
// const selectedPaymentMethod = ref('whishMoney');
// const whishMoneyPhoneNumber = ref('');
// const whishMoneyOtp = ref('');

const fetchPlans = async (website: Record<string, any>) => {
    isFetching.value = true;

    selectedWebsite.value = null;
    selectedPlan.value = null;

    selectedWebsite.value = {
        id: website.id,
        name: website.name,
    };

    try {
        const response = await axios.post(route('client.makePayment.getPlans'), { websiteTypeId: website.website_type.id });

        plans.value = response.data.props.plans;
    } catch (error) {
        throw error;
    }

    isFetching.value = false;
};

const addToCard = (plan: Record<string, any>) => {
    selectedPlan.value = null;
    selectedPlan.value = {
        id: plan.id,
        name: plan.name,
        price: plan.price,
    };

    if (selectedWebsite.value && selectedPlan.value) {
        const existingIndex = paymentCart.value.findIndex((item) => item.website_id === selectedWebsite.value?.id);

        if (existingIndex !== -1) {
            paymentCart.value[existingIndex] = {
                website_id: selectedWebsite.value.id,
                website_name: selectedWebsite.value.name,
                plan_id: selectedPlan.value.id,
                plan_name: selectedPlan.value.name,
                plan_price: selectedPlan.value.price,
            };
        } else {
            paymentCart.value.push({
                website_id: selectedWebsite.value.id,
                website_name: selectedWebsite.value.name,
                plan_id: selectedPlan.value.id,
                plan_name: selectedPlan.value.name,
                plan_price: selectedPlan.value.price,
            });
        }
    }
};

const removeFromCard = (item: PaymentFormProps) => {
    paymentCart.value = paymentCart.value.filter((cartItem) => cartItem !== item);

    if (selectedWebsite.value?.id === item.website_id && selectedPlan.value?.id === item.plan_id) {
        selectedPlan.value = null;
    }
};

const totalAmount = computed(() => {
    return Number(paymentCart.value.reduce((sum, item) => sum + Number(item.plan_price), 0).toFixed(2));
});

// const payNow = async () => {
//     if (paymentCart.value.length === 0) {
//         toast.fire({ icon: 'error', title: 'Your cart is empty!' });
//         return;
//     }

//     isPaying.value = true;

//     try {
//         if (selectedPaymentMethod.value === 'whishMoney') {
//             if (!whishMoneyPhoneNumber.value || !whishMoneyOtp.value) {
//                 toast.fire({ icon: 'error', title: 'Please enter your Whish Money phone number and OTP.' });
//                 isPaying.value = false;
//                 return;
//             }

//             // Simulate Whish Money API call
//             await new Promise((resolve) => setTimeout(resolve, 2000));

//             toast.fire({
//                 icon: 'success',
//                 title: `Whish Money payment of $${totalAmount.value} successful!`,
//             });
//             whishMoneyPhoneNumber.value = '';
//             whishMoneyOtp.value = '';
//         } else {
//             // Generic payment simulation
//             await new Promise((resolve) => setTimeout(resolve, 2000));

//             toast.fire({
//                 icon: 'success',
//                 title: `Payment of $${totalAmount.value} successful!`,
//             });
//         }
//         paymentCart.value = []; // Clear the cart after successful payment
//         selectedWebsiteId.value = 0;
//         selectedPlanId.value = 0;
//         plans.value = [];
//     } catch (error) {
//         toast.fire({ icon: 'error', title: 'Payment failed. Please try again.' });
//         console.error('Payment error:', error);
//     }

//     isPaying.value = false;
// };
</script>

<template>
    <Head title="Make Payment" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="border-muted mx-2 my-4 md:mx-4 grid grid-cols-1 md:grid-cols-5 lg:grid-cols-9 gap-4 border p-2 md:p-4 rounded-md">
            <div class="md:col-span-3 lg:col-span-6 flex flex-col gap-5">
                <div class="bg-card flex flex-col rounded-md">
                    <div class="flex flex-wrap gap-2 w-full items-center justify-between border-muted border-b px-2 pt-2 pb-2 md:px-4 md:pt-4">
                        <div class="flex flex-col gap-1">
                            <h3 class="text-active font-medium">Websites:</h3>
                            <div class="flex items-center gap-4">
                                <Radio v-model="status" value="expiredSoon" label="Expired Soon" />
                                <Radio v-model="status" value="notPaid" label="Not Paid" />
                            </div>
                        </div>

                        <div class="relative">
                            <Input
                                class="w-full pl-9"
                                id="search"
                                type="search"
                                autofocus
                                :tabindex="1"
                                placeholder="searching..."
                                :value="search"
                                v-model="search"
                            />

                            <Search class="pointer-events-none absolute top-1/2 left-2 z-0 h-5 w-5 -translate-y-1/2 transform" />
                        </div>
                    </div>

                    <div v-if="props.websites.length > 0" class="custom-scrollbar flex w-full items-center gap-3.5 overflow-auto p-2 md:p-4">
                        <Button
                            v-for="website in props.websites"
                            :key="website.id"
                            @click="fetchPlans(website)"
                            type="button"
                            variant="ghost"
                            size="lg"
                            class="size-35 flex-col rounded-md"
                            :class="
                                selectedWebsite?.id === website.id
                                    ? 'size-37 bg-gradient-to-br from-[var(--primary)] via-blue-800 to-[var(--primary)] dark:via-blue-600/40'
                                    : ''
                            "
                        >
                            <p class="text-base font-medium" :class="selectedWebsite?.id === website.id ? 'text-white' : 'text-active'">
                                {{ website.name }}
                            </p>
                            <p class="text-xs" :class="selectedWebsite?.id === website.id ? 'text-white' : 'text-body-muted'">
                                {{ website.website_type.type }}
                            </p>
                        </Button>
                    </div>

                    <div v-else class="w-full text-center p-2 md:p-4">
                        <div class="mb-4 flex flex-col items-center gap-1">
                            <SearchX class="text-body-muted size-6" />
                            <p class="text-body-muted text-sm font-medium">No Approved Website Founded</p>
                        </div>

                        <Link :href="route('websiteBuilder.index')" v-if="search === '' && status === ''">
                            <Button type="button">Create</Button>
                        </Link>
                    </div>
                </div>

                <div class="bg-card flex min-h-70 flex-col rounded-md">
                    <h3 class="text-active border-muted border-b pb-2 font-medium pt-2 px-2 md:px-4 md:pt-4">Plans</h3>

                    <div v-if="plans.length > 0 && !isFetching" class="custom-scrollbar flex flex-col md:flex-row h-full w-full items-center gap-3.5 overflow-x-auto 
                    p-2 md:p-4">
                        <button
                            v-for="plan in plans"
                            :key="plan.id"
                            type="button"
                            variant="ghost"
                            class="bg-content text-body-muted flex min-w-fit cursor-pointer flex-col rounded-md transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:scale-98 active:scale-96"
                            @click="addToCard(plan)"
                            :class="
                                paymentCart.some((item) => item.plan_id === plan.id && item.website_id === selectedWebsite?.id) ||
                                selectedPlan?.id === plan.id
                                    ? '!bg-[var(--primary)]/15 p-1 md:p-2 dark:!bg-[var(--primary)]/5'
                                    : ''
                            "
                        >
                            <div class="flex items-center justify-between border-b border-black/4 pb-2 dark:border-white/4 pt-2 px-2 md:px-4 md:pt-4">
                                <p class="text-active text-lg font-bold">{{ plan.name }}</p>

                                <CheckCircle
                                    class="size-4"
                                    :class="
                                        paymentCart.some((item) => item.plan_id === plan.id && item.website_id === selectedWebsite?.id) ||
                                        selectedPlan?.id === plan.id
                                            ? 'text-active-link size-5'
                                            : ''
                                    "
                                />
                            </div>

                            <div class="text-center pt-2 md:pt-4 pb-2">
                                <p class="font-bold">
                                    <span class="text-active text-xl">{{ plan.price }}$</span>
                                    <span class="text-sm">/{{ plan.duration }}</span>
                                </p>
                            </div>

                            <div class="mt-3 flex flex-col gap-3.5 p-2 md:p-4">
                                <p v-for="(feature, index) in plan.features" :key="index" class="flex items-center gap-1.5">
                                    <Check class="size-4 text-[var(--success)]" />
                                    <span class="text-xs">{{ feature }}</span>
                                </p>
                            </div>
                        </button>
                    </div>

                    <div v-else class="flex h-full w-full items-center justify-center p-2 md:p-4">
                        <div v-if="!isFetching" class="flex flex-col items-center gap-1">
                            <PackageSearch class="text-body-muted size-6" />
                            <p class="text-body-muted text-sm font-medium">No Plans Founded</p>
                            <p class="text-body-muted text-sm font-medium">Select a Website First</p>
                        </div>
                        <div v-else class="flex w-full items-center justify-center">
                            <LoaderCircle class="size-8 animate-spin" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 lg:col-span-3 flex flex-col gap-5">
                <div class="bg-card flex h-full flex-col rounded-md">
                    <h3 class="text-active border-muted border-b pb-2 pt-2 px-2 md:px-4 md:pt-4 font-medium">Your Cart</h3>

                    <template v-if="paymentCart.length > 0">
                        <div class="custom-scrollbar flex max-h-35 flex-col gap-3 overflow-y-auto p-2 md:p-4">
                            <div
                                v-for="(item, index) in paymentCart"
                                :key="index"
                                class="bg-body border border-muted flex items-center justify-between rounded-md p-3"
                            >
                                <div class="flex flex-col">
                                    <p class="font-medium">{{ item.website_name }}</p>
                                    <p class="text-body-muted text-sm">{{ item.plan_name }} - {{ item.plan_price }}$</p>
                                </div>
                                <button @click="removeFromCard(item)" class="text-xs px-2.5 py-1.5 bg-red-500/90 text-white
                                dark:bg-red-800/90 cursor-pointer hover:bg-red-500 dark:hover:bg-red-800">Remove</button>
                            </div>
                        </div>

                        <div class="border-muted flex flex-col gap-4 border-t p-2 md:p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-medium">Total:</p>
                                <p class="text-active text-lg font-bold">{{ totalAmount }}$</p>
                            </div>

                            <!-- <div class="flex flex-col gap-2">
                                <label for="paymentMethod" class="text-sm font-medium">Select Payment Method:</label>
                                <select
                                    id="paymentMethod"
                                    v-model="selectedPaymentMethod"
                                    class="border-muted bg-input text-active focus:border-primary focus:ring-primary w-full rounded-md border p-2"
                                >
                                    <option value="whishMoney">Whish Money</option>
                                </select>
                            </div> -->

                            <!-- <div v-if="selectedPaymentMethod === 'whishMoney'" class="flex flex-col gap-3">
                                <h4 class="text-active text-md font-medium">Whish Money Details</h4>
                                <div>
                                    <label for="whishMoneyPhone" class="text-sm font-medium">Phone Number:</label>
                                    <input
                                        id="whishMoneyPhone"
                                        v-model="whishMoneyPhoneNumber"
                                        type="tel"
                                        placeholder="e.g., 70123456"
                                        class="border-muted bg-input text-active focus:border-primary focus:ring-primary w-full rounded-md border p-2"
                                    />
                                </div>
                                <div>
                                    <label for="whishMoneyOtp" class="text-sm font-medium">OTP:</label>
                                    <input
                                        id="whishMoneyOtp"
                                        v-model="whishMoneyOtp"
                                        type="text"
                                        placeholder="Enter OTP"
                                        class="border-muted bg-input text-active focus:border-primary focus:ring-primary w-full rounded-md border p-2"
                                    />
                                </div>
                            </div> -->

                            <Button class="w-full p-2 md:p-4">
                                <!-- <LoaderCircle v-if="isPaying" class="mr-2 size-4 animate-spin" /> -->
                                Pay Now
                            </Button>
                        </div>
                    </template>

                    <div v-else class="flex h-full w-full flex-col items-center justify-center text-center p-2 md:p-4">
                        <LucideShoppingCart class="text-body-muted mb-1 size-6" />
                        <p class="text-body-muted text-sm font-medium">Your cart is empty.</p>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 1px;
    height: 1px;
    scrollbar-width: thin;
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent !important;
}
</style>
