<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';


const props = defineProps<{
    options: {
        years: Array<{ start: number; end: number; }>;
        months: Array<{ name: string; value: number; }>;
        days: Array<{ name: string; value: number; }>;
        gender: Array<{ name: string; value: string; }>;
    },
}>()

const form = ref({
    year: {
        start: 2000,
    },
    month: {
        start: new Date().getMonth() + 1,
    },
    day: {
        start: new Date().getDate(),
    },
    gender: 'M',
});

const isActive = (value, valueForm) => {
    return value === valueForm;
};

const daysInMonth = (year, month) => {
  return new Date(year, month, 0).getDate()
}

const generate = async () => {
    await axios.post('/generator', {
        year: form.value.year.start,
        month: form.value.month.start,
        day: form.value.day.start,
        gender: form.value.gender
    });
};

watch(() => [form.value.year.start, form.value.month.start], ([newYear, newMonth]) => {
    const days = daysInMonth(newYear, newMonth);
    props.options.days = Array.from({ length: days }, (_, i) => ({ name: (i + 1).toString(), value: i + 1 }));

    if (form.value.day.start > days) {
        form.value.day.start = days;
    }
});
</script>

<template>
    <Head title="Pesel generator">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="main">
        <section class="p-4 text-2xl bg-sky-600 text-white">
            Pesel generator
        </section>

        <div class="grid grid-cols-1 justify-center">
            <section class="flex-1 p-4">
                <p>
                    <span class="font-bold">Let's get started!</span>
                    Semonthlect the settings below and generate a sample PESEL number.
                </p>
            </section>

            <section class="flex-1 p-4">
                <p class="text-2xl mb-4">
                    Year
                    <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                    </svg>
                </p>

                <ul class="inline-flex flex-wrap rounded-md overflow-hidden">
                    <li
                        v-for="year in props.options.years"
                        class="
                            bg-gray-200 p-2 no-warp whitespace-nowrap border-r border-gray-500 cursor-pointer
                            last:border-0
                            hover:bg-sky-600 hover:text-white
                        "
                        :class="[
                            isActive(year.start, form.year.start) && 'bg-sky-600 text-white'
                        ]"
                        @click="form.year.start = year.start"
                    >
                        {{ year.start }} - {{ year.end }}
                    </li>
                </ul>
            </section>

            <section class="flex-1 p-4">
                <p class="text-2xl mb-4">
                    Month
                    <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/>
                    </svg>
                </p>

                <ul class="inline-flex flex-wrap rounded-md overflow-hidden">
                    <li
                        v-for="month in props.options.months"
                        class="
                            bg-gray-200 p-2 no-warp whitespace-nowrap border-r border-gray-500 cursor-pointer
                            last:border-0
                            hover:bg-sky-600 hover:text-white
                        "
                        :class="[
                            isActive(month.value, form.month.start) && 'bg-sky-600 text-white'
                        ]"
                        @click="form.month.start = month.value"
                    >
                        {{ month.name }}
                    </li>
                </ul>
            </section>

            <section class="flex-1 p-4">
                <p class="text-2xl mb-4">
                    Day
                    <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M12 5V3m0 18v-2M7.05 7.05 5.636 5.636m12.728 12.728L16.95 16.95M5 12H3m18 0h-2M7.05 16.95l-1.414 1.414M18.364 5.636 16.95 7.05M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                    </svg>
                </p>

                <ul class="inline-flex flex-wrap rounded-md overflow-hidden">
                    <li
                        v-for="day in props.options.days"
                        class="
                            bg-gray-200 p-2 no-warp whitespace-nowrap border-r border-gray-500 cursor-pointer
                            last:border-0
                            hover:bg-sky-600 hover:text-white
                        "
                        :class="[
                            isActive(day.value, form.day.start) && 'bg-sky-600 text-white'
                        ]"
                        @click="form.day.start = day.value"
                    >
                        {{ day.name }}
                    </li>
                </ul>
            </section>

            <section class="flex-1 p-4">
                <p class="text-2xl mb-4">
                    Gender
                    <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.3" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                    </svg>
                </p>

                <ul class="inline-flex flex-wrap rounded-md overflow-hidden">
                    <li
                        v-for="gender in props.options.gender"
                        class="
                            bg-gray-200 p-2 no-warp whitespace-nowrap border-r border-gray-500 cursor-pointer
                            last:border-0
                            hover:bg-sky-600 hover:text-white
                        "
                        :class="[
                            isActive(gender.value, form.gender) && 'bg-sky-600 text-white'
                        ]"
                        @click="form.gender = gender.value"
                    >
                        {{ gender.name }}
                    </li>
                </ul>
            </section>

            <section class="flex-1 p-4">
                <p class="text-2xl mb-4">
                    Generated PESEL number
                    <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z"/>
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                    </svg>

                </p>
                
                <div class="flex gap-4 items-center justify-center">
                    <div>
                        <div class="inline-block bg-gray-100 p-2 rounded-sm cursor-pointer">
                            <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z"/>
                            </svg>

                            <svg hidden class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="p-4 bg-gray-200 rounded-sm cursor-pointer">
                            <span class="h-[37px] inline-block font-mono text-5xl leading-none">92091111111</span>
                        </p>
                    </div>
                    <div>
                        <div class="inline-block bg-lime-400 p-2 rounded-sm cursor-pointer" @click="generate">
                            <svg class="inline relative top-[-2px] w-[34px] h-[34px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
            </section>
        </div>
    </div>
</template>
