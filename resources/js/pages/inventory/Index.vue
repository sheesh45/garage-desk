<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ items: { data: Array<any>; links: Array<any> }; filters: { search?: string } }>();
const filters = reactive({ search: props.filters.search ?? '' });
const submit = () => router.get('/inventory', filters, { preserveState: true, preserveScroll: true });
</script>

<template>
    <Head title="Inventory" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Inventory" description="Spare parts, stock levels, and supplier linkage.">
            <Button as-child><Link href="/inventory/create">Add item</Link></Button>
        </PageHeader>
        <div class="flex gap-3 rounded-2xl border bg-card p-4">
            <Input v-model="filters.search" placeholder="Search by name, SKU, or barcode" />
            <Button @click="submit">Search</Button>
        </div>
        <div class="overflow-hidden rounded-2xl border bg-card">
            <table class="min-w-full text-sm">
                <thead class="bg-muted/40 text-left text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items.data" :key="item.id" class="border-t">
                        <td class="px-4 py-3"><div class="font-medium">{{ item.name }}</div><div class="text-muted-foreground">{{ item.category }}</div></td>
                        <td class="px-4 py-3">{{ item.sku }}</td>
                        <td class="px-4 py-3">{{ item.quantity }} / min {{ item.minimum_stock }}</td>
                        <td class="px-4 py-3">{{ item.supplier?.name ?? 'N/A' }}</td>
                        <td class="px-4 py-3"><Button as-child variant="outline" size="sm"><Link :href="`/inventory/${item.id}/edit`">Edit</Link></Button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <PaginationNav :links="items.links" />
    </div>
</template>
