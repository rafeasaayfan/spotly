<script setup lang="ts">
import Layout from '../Layout.vue';
import SettingLayout from './Layout.vue';
import DeleteUser from '../../components/user/DeleteUser.vue';

const props = defineProps<{
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();
</script>

<template>
    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <SettingLayout>
                <DeleteUser />
            </SettingLayout>
        </section>
    </Layout>
</template>
