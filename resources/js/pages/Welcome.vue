<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    BellRing,
    Boxes,
    CarFront,
    ClipboardList,
    CreditCard,
    ShieldCheck,
    Users,
    Wrench,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import { dashboard, login, register } from '@/routes';

const page = usePage();

const modules = [
    {
        title: 'Customer & Vehicle Records',
        copy: 'Keep service history, compliance dates, notes, and multiple vehicles in one place.',
        icon: Users,
    },
    {
        title: 'Job Cards & Workshop Flow',
        copy: 'Track complaints, mechanic assignment, progress, parts waiting, and delivery status.',
        icon: ClipboardList,
    },
    {
        title: 'Inventory & Spare Parts',
        copy: 'Manage stock, suppliers, low-stock alerts, and part consumption against jobs.',
        icon: Boxes,
    },
    {
        title: 'Invoices & Billing',
        copy: 'Generate labour and parts bills with payment status, PDF export, and clean summaries.',
        icon: CreditCard,
    },
];

const stats = [
    { label: 'Faster Intake', value: '3x' },
    { label: 'Live Job Tracking', value: '24/7' },
    { label: 'Reminder Ready', value: 'Auto' },
    { label: 'Modules Included', value: '8+' },
];

const highlights = [
    'Admin, Service Advisor, and Mechanic workflows',
    'Service reminders for insurance, PUC, and periodic maintenance',
    'Responsive dashboard with revenue, workload, and stock visibility',
];
</script>

<template>
    <Head title="Garage360" />

    <div class="min-h-screen bg-transparent text-foreground">
        <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-5 py-6 md:px-8 lg:px-10">
            <header class="flex items-center justify-between gap-4 rounded-full border border-border/70 bg-white/65 px-4 py-3 shadow-sm backdrop-blur dark:bg-slate-950/45">
                <div class="flex items-center gap-3">
                    <AppLogo />
                </div>

                <nav class="flex items-center gap-2">
                    <Link
                        v-if="page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center rounded-full border border-border/70 bg-background px-4 py-2 text-sm font-medium transition hover:border-primary hover:text-primary"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium text-muted-foreground transition hover:text-foreground"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="register()"
                            class="inline-flex items-center rounded-full bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-95"
                        >
                            Start free
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="flex flex-1 flex-col justify-center py-10 lg:py-14">
                <section class="grid items-center gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/60 bg-amber-100/70 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-900 dark:border-amber-700/40 dark:bg-amber-950/30 dark:text-amber-200">
                            <Wrench class="size-3.5" />
                            Garage Operations, Simplified
                        </div>

                        <div class="space-y-4">
                            <h1 class="max-w-3xl text-4xl font-semibold leading-tight tracking-tight text-balance md:text-5xl lg:text-6xl">
                                Run your garage like a modern service business, not a paper register.
                            </h1>
                            <p class="max-w-2xl text-base leading-7 text-muted-foreground md:text-lg">
                                Garage360 helps workshops manage customers, vehicles, repair jobs,
                                mechanics, spare parts, invoices, and service reminders from one clean system.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <Link
                                v-if="page.props.auth.user"
                                :href="dashboard()"
                                class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-95"
                            >
                                Open Dashboard
                                <ArrowRight class="size-4" />
                            </Link>
                            <template v-else>
                                <Link
                                    :href="register()"
                                    class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-95"
                                >
                                    Create Account
                                    <ArrowRight class="size-4" />
                                </Link>
                                <Link
                                    :href="login()"
                                    class="inline-flex items-center rounded-full border border-border/70 bg-white/75 px-5 py-3 text-sm font-medium transition hover:border-primary hover:text-primary dark:bg-slate-950/45"
                                >
                                    Existing user login
                                </Link>
                            </template>
                        </div>

                        <div class="grid gap-3 pt-2 sm:grid-cols-3">
                            <div
                                v-for="item in highlights"
                                :key="item"
                                class="rounded-2xl border border-border/70 bg-white/70 p-4 text-sm text-muted-foreground shadow-sm dark:bg-slate-950/35"
                            >
                                {{ item }}
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 -z-10 rounded-[2rem] bg-[radial-gradient(circle_at_top,_rgba(249,115,22,0.28),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(8,145,178,0.24),_transparent_42%)] blur-2xl" />

                        <div class="overflow-hidden rounded-[2rem] border border-border/70 bg-white/78 p-4 shadow-[0_30px_90px_rgba(15,23,42,0.16)] backdrop-blur dark:bg-slate-950/55">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl bg-[linear-gradient(145deg,rgba(249,115,22,0.95),rgba(245,158,11,0.8))] p-5 text-primary-foreground">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.18em] text-white/80">Live Workshop</p>
                                            <h2 class="mt-2 text-2xl font-semibold">12 Active Jobs</h2>
                                        </div>
                                        <CarFront class="size-9 text-white/90" />
                                    </div>
                                    <p class="mt-6 text-sm leading-6 text-white/85">
                                        Intake, inspection, waiting parts, in-progress, and ready-for-delivery jobs at a glance.
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-border/70 bg-background/70 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Today’s Revenue</p>
                                            <h3 class="mt-2 text-2xl font-semibold">₹48,600</h3>
                                        </div>
                                        <ShieldCheck class="size-8 text-primary" />
                                    </div>
                                    <div class="mt-5 space-y-3">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-muted-foreground">Mechanics active</span>
                                            <span class="font-medium">7 / 8</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-muted-foreground">Invoices pending</span>
                                            <span class="font-medium">5</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-muted-foreground">Low stock alerts</span>
                                            <span class="font-medium text-amber-700 dark:text-amber-300">3 items</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-border/70 bg-background/70 p-4">
                                    <div class="flex items-center gap-3">
                                        <BellRing class="size-5 text-cyan-600 dark:text-cyan-300" />
                                        <div>
                                            <p class="font-medium">Automated reminders</p>
                                            <p class="text-sm text-muted-foreground">Service, insurance, and PUC alerts ready.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-border/70 bg-background/70 p-4">
                                    <div class="flex items-center gap-3">
                                        <Boxes class="size-5 text-primary" />
                                        <div>
                                            <p class="font-medium">Parts linked to jobs</p>
                                            <p class="text-sm text-muted-foreground">Inventory usage stays connected to billing.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                                <div
                                    v-for="item in stats"
                                    :key="item.label"
                                    class="rounded-2xl border border-border/70 bg-background/75 p-4 text-center"
                                >
                                    <p class="text-2xl font-semibold">{{ item.value }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.16em] text-muted-foreground">
                                        {{ item.label }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-12 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <article
                        v-for="module in modules"
                        :key="module.title"
                        class="rounded-[1.6rem] border border-border/70 bg-white/72 p-5 shadow-sm backdrop-blur dark:bg-slate-950/35"
                    >
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-accent text-accent-foreground">
                            <component :is="module.icon" class="size-5" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold">{{ module.title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-muted-foreground">
                            {{ module.copy }}
                        </p>
                    </article>
                </section>
            </main>
        </div>
    </div>
</template>
