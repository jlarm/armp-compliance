<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import dealerships from '@/routes/dealerships';
import { create } from '@/routes/dealerships';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dealerships',
        href: dealerships.index().url,
    },
];
</script>

<template>
    <Head title="Dealerships" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div>Dealership Index</div>
        <Button as-child>
            <Link :href="create()">Create Dealership</Link>
        </Button>
    </AppLayout>
</template>
