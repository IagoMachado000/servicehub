<script setup>
import { ref } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";

// Classes para links da Sidebar (Desktop)
const sidebarLinkClasses = (isActive) => {
    return isActive
        ? "flex items-center gap-3 px-4 py-3 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition-colors"
        : "flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors";
};

// Classes para links da Bottom Nav (Mobile)
const bottomNavLinkClasses = (isActive) => {
    return isActive
        ? "flex flex-col items-center justify-center w-full h-full text-indigo-600"
        : "flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-gray-900";
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <aside
            class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-100 fixed inset-y-0 z-50"
        >
            <div class="h-16 flex items-center px-6 border-b border-gray-50">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-2 font-bold text-xl text-gray-900 tracking-tight"
                >
                    ServiceHub
                </Link>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <Link
                    :href="route('dashboard')"
                    :class="sidebarLinkClasses(route().current('dashboard'))"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                        />
                    </svg>
                    Dashboard
                </Link>

                <Link
                    :href="route('tickets.index')"
                    :class="sidebarLinkClasses(route().current('tickets.*'))"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v4.086c0 .705.273 1.389.75 1.918L6.675 16.05h.75c.621 0 1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125h.75l3.675-3.672c.477-.529.75-1.214.75-1.918V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"
                        />
                    </svg>
                    Tickets
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs"
                    >
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-gray-700 truncate w-32">
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate w-32">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div
            class="flex-1 flex flex-col min-h-screen lg:pl-64 transition-all duration-300"
        >
            <nav
                class="bg-white border-b border-gray-100 h-16 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-3 lg:hidden">
                    <span class="font-bold text-lg text-gray-900"
                        >ServiceHub</span
                    >
                </div>

                <header class="flex flex-1 items-center" v-if="$slots.header">
                    <div class="w-full mr-3 sm:mr-5 lg:mr-10">
                        <slot name="header" />
                    </div>
                </header>

                <div class="flex items-center">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                            >
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm"
                                >
                                    {{ $page.props.auth.user.name.charAt(0) }}
                                </div>
                            </button>
                        </template>

                        <template #content>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>
                            <div class="border-t border-gray-100" />
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </nav>

            <main
                class="flex-1 overflow-y-auto bg-gray-50 p-4 pb-24 lg:pb-6 lg:p-8"
            >
                <slot />
            </main>
        </div>

        <nav
            class="lg:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 h-16 z-40 flex justify-around items-center px-2 shadow-lg"
        >
            <Link
                :href="route('dashboard')"
                :class="bottomNavLinkClasses(route().current('dashboard'))"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6 mb-1"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                    />
                </svg>
                <span class="text-[10px] font-medium">Dashboard</span>
            </Link>

            <Link
                :href="route('tickets.index')"
                :class="bottomNavLinkClasses(route().current('tickets.*'))"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6 mb-1"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v4.086c0 .705.273 1.389.75 1.918L6.675 16.05h.75c.621 0 1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125h.75l3.675-3.672c.477-.529.75-1.214.75-1.918V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"
                    />
                </svg>
                <span class="text-[10px] font-medium">Tickets</span>
            </Link>
        </nav>
    </div>
</template>
