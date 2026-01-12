<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    ticket: Object,
});

// Helper para formatar data
const formatDate = (dateString) => {
    if (!dateString) return "Pendente...";
    return new Date(dateString).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Helper para cor do status
const statusClass = computed(() => {
    switch (props.ticket.status) {
        case "open":
            return "bg-blued-100 text-blued-800 border-blued-200";
        case "pending":
            return "bg-yellow-100 text-yellow-800 border-yellow-200";
        case "done":
            return "bg-green-100 text-green-800 border-green-200";
        case "closed":
            return "bg-gray-100 text-gray-800 border-gray-200";
        case "failed":
            return "bg-red-100 text-red-800 border-red-200";
        default:
            return "bg-gray-100 text-gray-800 border-gray-200";
    }
});

// Helper para cor do Sentimento
const sentimentColor = computed(() => {
    const score = props.ticket.detail?.sentiment_score ?? 0;
    if (score <= -0.5) return "text-red-600"; // Muito negativo
    if (score < 0) return "text-orange-500"; // Negativo
    if (score > 0.5) return "text-green-600"; // Muito positivo
    return "text-gray-500"; // Neutro
});

const sentimentLabel = computed(() => {
    const score = props.ticket.detail?.sentiment_score ?? 0;
    if (score <= -0.5) return "Crítico";
    if (score < 0) return "Negativo";
    if (score > 0) return "Positivo";
    return "Neutro";
});
</script>

<template>
    <Head :title="`Ticket #${ticket.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Ticket #{{ ticket.id }}
                    </h2>
                    <span
                        :class="[
                            'px-3 py-1 rounded-full text-xs font-semibold border',
                            statusClass,
                        ]"
                    >
                        {{ ticket.status.toUpperCase() }}
                    </span>
                </div>
                <Link
                    :href="route('tickets.index')"
                    class="text-sm text-indigo-600 hover:text-indigo-800"
                >
                    &larr; Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ ticket.title }}
                            </h3>

                            <div class="mb-6">
                                <h4
                                    class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2"
                                >
                                    Descrição do Usuário
                                </h4>
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{
                                        ticket.detail?.description ||
                                        "Sem descrição."
                                    }}
                                </p>
                            </div>

                            <div
                                v-if="ticket.attachment_path"
                                class="mt-4 p-4 bg-gray-50 rounded border border-gray-200"
                            >
                                <span class="text-sm text-gray-500"
                                    >Anexo disponível:
                                </span>
                                <span class="text-sm font-mono text-gray-700">{{
                                    ticket.attachment_path
                                }}</span>
                            </div>
                        </div>

                        <div
                            v-if="ticket.detail?.processed_at"
                            class="bg-indigo-50 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-indigo-100"
                        >
                            <div class="flex items-center gap-2 mb-4">
                                <svg
                                    class="w-5 h-5 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    ></path>
                                </svg>
                                <h3 class="text-lg font-medium text-indigo-900">
                                    Análise Automática
                                </h3>
                            </div>

                            <p class="text-indigo-800 text-sm italic">
                                "{{ ticket.detail?.summary }}"
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h4
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"
                            >
                                Métricas de Análise
                            </h4>

                            <div class="mb-6 text-center">
                                <span class="block text-sm text-gray-500 mb-1"
                                    >Sentimento Detectado</span
                                >
                                <div
                                    class="text-3xl font-bold"
                                    :class="sentimentColor"
                                >
                                    {{ sentimentLabel }}
                                </div>
                                <span class="text-xs text-gray-400"
                                    >Score:
                                    {{ ticket.detail?.sentiment_score }}</span
                                >
                            </div>

                            <div v-if="ticket.detail?.keywords?.length">
                                <span class="block text-sm text-gray-500 mb-2"
                                    >Palavras-chave</span
                                >
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="keyword in ticket.detail
                                            .keywords"
                                        :key="keyword"
                                        class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded border border-gray-200"
                                    >
                                        {{ keyword }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h4
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"
                            >
                                Detalhes do Projeto
                            </h4>

                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Projeto</dt>
                                    <dd class="font-medium text-gray-900">
                                        {{ ticket.project?.name || "N/A" }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Criado em</dt>
                                    <dd class="text-gray-900">
                                        {{ formatDate(ticket.created_at) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Processado em</dt>
                                    <dd class="text-gray-900">
                                        {{
                                            formatDate(
                                                ticket.detail?.processed_at
                                            )
                                        }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
