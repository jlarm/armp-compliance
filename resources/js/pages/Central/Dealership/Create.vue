<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import dealerships from '@/routes/dealerships';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { Plus, Trash2 } from 'lucide-vue-next';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Field, FieldGroup, FieldLabel, FieldSet } from '@/components/ui/field';
import { usePhoneFormatter } from '@/composables/usePhoneFormatter';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface Props {
    states: Record<string, string>;
    timezones: Record<string, string>;
}

const props = defineProps<Props>();

interface StoreFormData {
    id: string;
    name: string;
    address: string;
    city: string;
    state: string;
    zip: string;
    phone: string;
    timezone: string;
}

const stores = ref<StoreFormData[]>([]);
const { formatPhoneNumber } = usePhoneFormatter();

const addStore = () => {
    stores.value = [
        ...stores.value,
        {
            id: Math.random().toString(36).substring(2, 9),
            name: '',
            address: '',
            city: '',
            state: '',
            zip: '',
            phone: '',
            timezone: '',
        },
    ];
};

const removeStore = (id: string) => {
    stores.value = stores.value.filter((store) => store.id !== id);
};

const updateStore = (id: string, field: keyof StoreFormData, value: string) => {
    stores.value = stores.value.map((store) =>
        store.id === id ? { ...store, [field]: value } : store,
    );
};

const updateStorePhone = (id: string, value: string) => {
    const formatted = formatPhoneNumber(value);
    updateStore(id, 'phone', formatted);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Dealership',
        href: dealerships.create().url,
    },
];
</script>

<template>
    <Head title="Create Dealership" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto w-full max-w-4xl animate-in px-4 pt-6 pb-20 duration-500 fade-in slide-in-from-bottom-4"
        >
            <Form
                :action="dealerships.store().url"
                method="post"
                class="space-y-8"
                #default="{ errors, processing }"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">
                            Add Dealership Group
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Create a new group and assign initial stores.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <Button variant="outline" as-child type="button">
                            <Link :href="dealerships.index()">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="processing">
                            {{ processing ? 'Saving...' : 'Save Group' }}
                        </Button>
                    </div>
                </div>

                <Card class="w-full">
                    <CardHeader>
                        <CardTitle>Group Details</CardTitle>
                        <CardDescription
                            >General information about the dealership
                            group</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <FieldSet>
                            <FieldGroup>
                                <Field>
                                    <FieldLabel for="name"
                                        >Dealership Group Name</FieldLabel
                                    >
                                    <Input
                                        id="name"
                                        name="name"
                                        placeholder="e.g. Prestige Auto Group"
                                    />
                                    <p
                                        v-if="errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.name }}
                                    </p>
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                    </CardContent>
                </Card>

                <div class="space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-lg font-bold text-slate-900">Stores</h3>
                        <Button variant="ghost" type="button" @click="addStore">
                            <Plus />
                            Add Store
                        </Button>
                    </div>

                    <p v-if="errors.stores" class="px-1 text-sm text-red-600">
                        {{ errors.stores }}
                    </p>

                    <div
                        v-if="stores.length === 0"
                        class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-12 text-center"
                    >
                        <h4 class="text-sm font-semibold text-slate-900">
                            No stores added
                        </h4>
                        <p class="mt-1 mb-4 text-xs text-slate-500">
                            Add stores belonging to this dealership group.
                        </p>
                        <Button
                            variant="outline"
                            type="button"
                            @click="addStore"
                        >
                            <Plus />
                            Add Store
                        </Button>
                    </div>
                    <div v-else class="grid grid-cols-1 gap-4">
                        <Card
                            v-for="(store, index) in stores"
                            :key="store.id"
                            class="group relative animate-in p-6 duration-300 slide-in-from-bottom-2"
                        >
                            <Button
                                @click="removeStore(store.id)"
                                type="button"
                                variant="outline"
                                size="icon"
                                class="absolute top-4 right-4 opacity-0 transition-opacity group-hover:opacity-100"
                            >
                                <Trash2 />
                            </Button>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
                                <div
                                    class="mb-2 flex items-center gap-2 md:col-span-12"
                                >
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded bg-slate-100 text-xs font-bold text-slate-500"
                                    >
                                        {{ index + 1 }}
                                    </span>
                                    <h4
                                        class="text-sm font-bold text-slate-900"
                                    >
                                        Store Details
                                    </h4>
                                </div>

                                <Field class="md:col-span-12">
                                    <FieldLabel :for="`stores.${index}.name`"
                                        >Store Name</FieldLabel
                                    >
                                    <Input
                                        :id="`stores.${index}.name`"
                                        :name="`stores[${index}][name]`"
                                        type="text"
                                        placeholder="e.g. Downtown Ford"
                                        :model-value="store.name"
                                        @update:model-value="
                                            updateStore(
                                                store.id,
                                                'name',
                                                $event,
                                            )
                                        "
                                    />
                                    <p
                                        v-if="errors[`stores.${index}.name`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.name`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-6">
                                    <FieldLabel :for="`stores.${index}.timezone`"
                                    >Timezone</FieldLabel
                                    >
                                    <Select
                                        :name="`stores[${index}][timezone]`"
                                        :model-value="store.timezone"
                                        @update:model-value="
                                            updateStore(
                                                store.id,
                                                'timezone',
                                                $event,
                                            )
                                        "
                                    >
                                        <SelectTrigger
                                            :id="`stores.${index}.timezone`"
                                        >
                                            <SelectValue
                                                placeholder="Select timezone"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="(label, value) in timezones"
                                                :key="value"
                                                :value="value"
                                            >
                                                {{ label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p
                                        v-if="errors[`stores.${index}.timezone`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.timezone`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-6">
                                    <FieldLabel :for="`stores.${index}.phone`"
                                        >Phone Number</FieldLabel
                                    >
                                    <Input
                                        :id="`stores.${index}.phone`"
                                        :name="`stores[${index}][phone]`"
                                        type="tel"
                                        placeholder="(555) 123-4567"
                                        :model-value="store.phone"
                                        @update:model-value="
                                            updateStorePhone(store.id, $event)
                                        "
                                        maxlength="14"
                                    />
                                    <p
                                        v-if="errors[`stores.${index}.phone`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.phone`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-12">
                                    <FieldLabel :for="`stores.${index}.address`"
                                        >Street Address</FieldLabel
                                    >
                                    <Input
                                        :id="`stores.${index}.address`"
                                        :name="`stores[${index}][address]`"
                                        type="text"
                                        placeholder="e.g. 123 Auto Park Way"
                                        :model-value="store.address"
                                        @update:model-value="
                                            updateStore(
                                                store.id,
                                                'address',
                                                $event,
                                            )
                                        "
                                    />
                                    <p
                                        v-if="errors[`stores.${index}.address`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.address`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-5">
                                    <FieldLabel :for="`stores.${index}.city`"
                                        >City</FieldLabel
                                    >
                                    <Input
                                        :id="`stores.${index}.city`"
                                        :name="`stores[${index}][city]`"
                                        type="text"
                                        placeholder="e.g. Chicago"
                                        :model-value="store.city"
                                        @update:model-value="
                                            updateStore(
                                                store.id,
                                                'city',
                                                $event,
                                            )
                                        "
                                    />
                                    <p
                                        v-if="errors[`stores.${index}.city`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.city`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-4">
                                    <FieldLabel :for="`stores.${index}.state`"
                                        >State</FieldLabel
                                    >
                                    <Select
                                        :name="`stores[${index}][state]`"
                                        :model-value="store.state"
                                        @update:model-value="
                                            updateStore(
                                                store.id,
                                                'state',
                                                $event,
                                            )
                                        "
                                    >
                                        <SelectTrigger
                                            :id="`stores.${index}.state`"
                                        >
                                            <SelectValue
                                                placeholder="Select state"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="(label, value) in states"
                                                :key="value"
                                                :value="value"
                                            >
                                                {{ label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p
                                        v-if="errors[`stores.${index}.state`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.state`] }}
                                    </p>
                                </Field>

                                <Field class="md:col-span-3">
                                    <FieldLabel :for="`stores.${index}.zip`"
                                        >Zip Code</FieldLabel
                                    >
                                    <Input
                                        :id="`stores.${index}.zip`"
                                        :name="`stores[${index}][zip]`"
                                        type="text"
                                        placeholder="e.g. 60041"
                                        :model-value="store.zip"
                                        @update:model-value="
                                            updateStore(store.id, 'zip', $event)
                                        "
                                    />
                                    <p
                                        v-if="errors[`stores.${index}.zip`]"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors[`stores.${index}.zip`] }}
                                    </p>
                                </Field>
                            </div>
                        </Card>
                    </div>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
