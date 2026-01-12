<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Mock de dados para visualização (depois virão do Backend)
const stats = [
    {
        title: "Total de Tickets",
        value: "12",
        icon: "M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776",
        color: "text-blue-600",
        bg: "bg-blue-50",
    },
    {
        title: "Em Processamento",
        value: "4",
        icon: "M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99",
        color: "text-amber-600",
        bg: "bg-amber-50",
    },
    {
        title: "Concluídos",
        value: "8",
        icon: "M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        color: "text-emerald-600",
        bg: "bg-emerald-50",
    },
];

const recentTickets = [
    {
        id: 102,
        title: "Erro na integração de pagamento",
        project: "E-commerce V1",
        status: "Aberto",
        date: "Hoje, 10:23",
    },
    {
        id: 101,
        title: "Ajuste de layout na home",
        project: "Landing Page",
        status: "Processando",
        date: "Ontem, 16:40",
    },
    {
        id: 99,
        title: "Bug no login social",
        project: "App Mobile",
        status: "Concluído",
        date: "10 Jan, 09:15",
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-900">
                        Visão Geral
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Bem-vindo de volta ao ServiceHub.
                    </p>
                </div>

                <Link
                    :href="route('tickets.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2 shadow-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>
                    Novo Ticket
                </Link>
            </div>
        </template>

        <div
            v-if="$page.props.flash.message"
            class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4"
        >
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert"
            >
                <strong class="font-bold">Sucesso! </strong>
                <span class="block sm:inline">{{
                    $page.props.flash.message
                }}</span>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="(stat, index) in stats"
                        :key="index"
                        class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center"
                    >
                        <div
                            :class="`p-3 rounded-full ${stat.bg} ${stat.color} mr-4`"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-8 h-8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    :d="stat.icon"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                {{ stat.title }}
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stat.value }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100"
                >
                    <div
                        class="p-6 border-b border-gray-100 flex justify-between items-center bg-white"
                    >
                        <h3 class="text-lg font-bold text-gray-900">
                            Tickets Recentes
                        </h3>
                        <Link
                            href="#"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                            >Ver todos &rarr;</Link
                        >
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100"
                            >
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Assunto</th>
                                    <th class="px-6 py-3">Projeto</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Data</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="ticket in recentTickets"
                                    :key="ticket.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        #{{ ticket.id }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ ticket.title }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ ticket.project }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                            :class="{
                                                'bg-blue-50 text-blue-700':
                                                    ticket.status === 'Aberto',
                                                'bg-amber-50 text-amber-700':
                                                    ticket.status ===
                                                    'Processando',
                                                'bg-green-50 text-green-700':
                                                    ticket.status ===
                                                    'Concluído',
                                            }"
                                        >
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ ticket.date }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <Link
                                            :href="route('tickets.show', 50)"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                        >
                                            Detalhes
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="recentTickets.length === 0"
                        class="p-12 text-center"
                    >
                        <p class="text-gray-500">Nenhum ticket encontrado.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
