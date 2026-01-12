<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    projects: Array,
});

const form = useForm({
    project_id: "",
    title: "",
    description: "",
    attachment: null,
});

const submit = () => {
    form.post(route("tickets.store"), {
        onSuccess: () => form.reset(),
    });
};

// Função auxiliar para mostrar o nome do arquivo selecionado (UX)
const handleFileUpload = (e) => {
    form.attachment = e.target.files[0];
};
</script>

<template>
    <Head title="Novo Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestão de Tickets
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                >
                    &larr; Voltar ao Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100"
                >
                    <div class="p-6 border-b border-gray-100 bg-white">
                        <h3
                            class="text-lg font-bold text-gray-900 flex items-center gap-2"
                        >
                            <span
                                class="p-2 bg-indigo-50 text-indigo-600 rounded-lg"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v4.086c0 .705.273 1.389.75 1.918L6.675 16.05h.75c.621 0 1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125h.75l3.675-3.672c.477-.529.75-1.214.75-1.918V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"
                                    />
                                </svg>
                            </span>
                            Abrir Novo Chamado
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 ml-12">
                            Preencha os detalhes abaixo para criar uma ordem de
                            serviço.
                        </p>
                    </div>

                    <div class="p-6 md:p-8 bg-white">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel
                                        for="project_id"
                                        value="Projeto Relacionado"
                                    />
                                    <div class="relative mt-1">
                                        <select
                                            id="project_id"
                                            v-model="form.project_id"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full py-2.5 bg-white"
                                        >
                                            <option value="" disabled>
                                                Selecione...
                                            </option>
                                            <option
                                                v-for="project in projects"
                                                :key="project.id"
                                                :value="project.id"
                                            >
                                                {{ project.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <InputError
                                        :message="form.errors.project_id"
                                        class="mt-2"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="title"
                                        value="Assunto do Ticket"
                                    />
                                    <TextInput
                                        id="title"
                                        v-model="form.title"
                                        type="text"
                                        class="mt-1 block w-full py-2.5 rounded-lg"
                                        placeholder="Ex: Erro na integração de pagamento"
                                    />
                                    <InputError
                                        :message="form.errors.title"
                                        class="mt-2"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="description"
                                    value="Descrição Técnica Detalhada"
                                />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm h-32"
                                    placeholder="Descreva o problema ou solicitação..."
                                ></textarea>
                                <InputError
                                    :message="form.errors.description"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel value="Anexo (JSON ou TXT)" />
                                <div
                                    class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 hover:bg-gray-50 transition duration-150 cursor-pointer relative"
                                >
                                    <div class="text-center">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-300"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>

                                        <div
                                            class="mt-4 flex text-sm leading-6 text-gray-600 justify-center"
                                        >
                                            <label
                                                for="file-upload"
                                                class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
                                            >
                                                <span
                                                    >Fazer upload de um
                                                    arquivo</span
                                                >
                                                <input
                                                    id="file-upload"
                                                    name="file-upload"
                                                    type="file"
                                                    class="sr-only"
                                                    @change="handleFileUpload"
                                                />
                                            </label>
                                            <p class="pl-1">
                                                ou arraste e solte
                                            </p>
                                        </div>
                                        <p
                                            class="text-xs leading-5 text-gray-600"
                                        >
                                            JSON ou TXT até 2MB
                                        </p>

                                        <p
                                            v-if="form.attachment"
                                            class="mt-2 text-sm text-emerald-600 font-semibold bg-emerald-50 py-1 px-3 rounded-full inline-block"
                                        >
                                            Arquivo selecionado:
                                            {{ form.attachment.name }}
                                        </p>
                                    </div>
                                </div>
                                <InputError
                                    :message="form.errors.attachment"
                                    class="mt-2"
                                />
                            </div>

                            <div
                                class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6"
                            >
                                <Link
                                    :href="route('dashboard')"
                                    class="text-sm text-gray-600 hover:text-gray-900"
                                >
                                    Cancelar
                                </Link>

                                <PrimaryButton
                                    class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Criar Ticket
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <div
                    v-if="$page.props.flash.error"
                    class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3"
                >
                    <svg
                        class="h-5 w-5 text-red-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <p class="text-sm font-medium text-red-800">
                        {{ $page.props.flash.error }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
