<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ item: any | null; suppliers: Array<any> }>();
const form = useForm({
    supplier_id: props.item?.supplier_id ?? props.suppliers[0]?.id ?? '',
    sku: props.item?.sku ?? '',
    barcode: props.item?.barcode ?? '',
    name: props.item?.name ?? '',
    category: props.item?.category ?? '',
    purchase_price: props.item?.purchase_price ?? 0,
    selling_price: props.item?.selling_price ?? 0,
    quantity: props.item?.quantity ?? 0,
    minimum_stock: props.item?.minimum_stock ?? 0,
    unit: props.item?.unit ?? 'pcs',
    notes: props.item?.notes ?? '',
});
const submit = () => props.item ? form.put(`/inventory/${props.item.id}`) : form.post('/inventory');
</script>

<template>
    <Head :title="item ? 'Edit Inventory Item' : 'Add Inventory Item'" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="item ? 'Edit Inventory Item' : 'Add Inventory Item'" description="Track stock, pricing, and supplier source.">
            <Button as-child variant="outline"><Link href="/inventory">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 md:grid-cols-2 lg:grid-cols-3" @submit.prevent="submit">
            <label class="space-y-2"><span class="text-sm">Supplier</span><select v-model="form.supplier_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option value="">None</option><option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.name }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">SKU</span><Input v-model="form.sku" /></label>
            <label class="space-y-2"><span class="text-sm">Barcode</span><Input v-model="form.barcode" /></label>
            <label class="space-y-2"><span class="text-sm">Name</span><Input v-model="form.name" /></label>
            <label class="space-y-2"><span class="text-sm">Category</span><Input v-model="form.category" /></label>
            <label class="space-y-2"><span class="text-sm">Unit</span><Input v-model="form.unit" /></label>
            <label class="space-y-2"><span class="text-sm">Purchase Price</span><Input v-model="form.purchase_price" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Selling Price</span><Input v-model="form.selling_price" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Quantity</span><Input v-model="form.quantity" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Minimum Stock</span><Input v-model="form.minimum_stock" type="number" /></label>
            <label class="space-y-2 md:col-span-2 lg:col-span-3"><span class="text-sm">Notes</span><textarea v-model="form.notes" class="min-h-28 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <div class="md:col-span-2 lg:col-span-3"><Button type="submit">Save item</Button></div>
        </form>
    </div>
</template>
