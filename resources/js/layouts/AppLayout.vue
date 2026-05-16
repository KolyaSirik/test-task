<script setup lang="ts">
import SidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <SidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
    </SidebarLayout>
</template>
