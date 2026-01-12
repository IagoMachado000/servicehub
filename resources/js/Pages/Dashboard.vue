<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Recebendo os dados reais do Controller
const props = defineProps({
    stats: Object,
    recentTickets: Array,
});
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
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500"
                    >
                        <div class="text-gray-500 text-sm font-medium">
                            Total Tickets
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.total }}
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-400"
                    >
                        <div class="text-gray-500 text-sm font-medium">
                            Na Fila (Pendente)
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.pending }}
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500"
                    >
                        <div class="text-gray-500 text-sm font-medium">
                            Processados (Open)
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.open }}
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500"
                    >
                        <div class="text-gray-500 text-sm font-medium">
                            Falhas
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.failed }}
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Tickets Recentes
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Título
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Projeto
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Data
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="ticket in recentTickets"
                                        :key="ticket.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            #{{ ticket.id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ ticket.title }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ ticket.project }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800':
                                                        ticket.status ===
                                                        'open',
                                                    'bg-yellow-100 text-yellow-800':
                                                        ticket.status ===
                                                        'pending',
                                                    'bg-red-100 text-red-800':
                                                        ticket.status ===
                                                        'failed',
                                                }"
                                            >
                                                {{
                                                    ticket.status.toUpperCase()
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ ticket.created_at_formatted }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'tickets.show',
                                                        ticket.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Detalhes
                                            </Link>
                                        </td>
                                    </tr>

                                    <tr v-if="recentTickets.length === 0">
                                        <td
                                            colspan="6"
                                            class="px-6 py-12 text-center text-gray-500"
                                        >
                                            Nenhum ticket encontrado.
                                            <Link
                                                :href="route('tickets.create')"
                                                class="text-indigo-600 underline"
                                                >Crie o primeiro!</Link
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
