<script setup lang="ts">
import { cn } from '@/lib/utils';
import * as echarts from 'echarts';
import { HTMLAttributes, onMounted, ref } from 'vue';

interface Data {
    value: number;
    name: string;
    itemStyle: {
        color: string;
    };
}

interface Props {
    parentClass?: HTMLAttributes['class'];
    headerClass?: HTMLAttributes['class'];
    chartClass?: HTMLAttributes['class'];
    title: string;
    subtitle: string;
    data: Data[];
}

const props = defineProps<Props>();

const chartRefLight = ref<HTMLDivElement | null>(null);
const chartRefDark = ref<HTMLDivElement | null>(null);

onMounted(() => {
    if (chartRefLight.value) {
        const chart = echarts.init(chartRefLight.value, 'light');
        const option: echarts.EChartsOption = {
            backgroundColor: 'transparent',
            tooltip: {
                trigger: 'item',
                backgroundColor: '#fff',
                borderColor: '#fff',
            },
            series: [
                {
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    padAngle: 5,
                    itemStyle: {
                        borderRadius: 10,
                        borderWidth: 2,
                    },
                    label: { show: false },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 16,
                            fontWeight: 'bold',
                        },
                    },
                    data: props.data,
                },
            ],
        };
        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }
    if (chartRefDark.value) {
        const chart = echarts.init(chartRefDark.value, 'dark');
        const option: echarts.EChartsOption = {
            backgroundColor: 'transparent',
            tooltip: {
                trigger: 'item',
                backgroundColor: '#000',
                borderColor: '#000',
            },
            series: [
                {
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    padAngle: 5,
                    itemStyle: {
                        borderRadius: 10,
                        borderWidth: 2,
                    },
                    label: { show: false },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 16,
                            fontWeight: 'bold',
                        },
                    },
                    data: props.data,
                },
            ],
        };
        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }
});
</script>

<template>
    <div :class="cn('border-muted flex flex-col rounded-md border', props.parentClass)">
        <div
            :class="
                cn(
                    'border-muted flex flex-col rounded-t-md border-b px-3 pt-3 pb-2',
                    'bg-gradient-to-br from-black/8 via-transparent to-black/8 dark:from-white/8 dark:to-white/8',
                    props.headerClass,
                )
            "
        >
            <h3 class="text-acitve font-bold">{{ props.title }}</h3>
            <p class="text-body-muted text-xs">{{ props.subtitle }}</p>
        </div>
        <div :class="cn('relative flex h-65 xl:h-75 w-full items-center justify-center overflow-hidden', props.chartClass)">
            <div ref="chartRefLight" class="absolute start-0 top-0 h-full w-full dark:-start-[500px]"></div>
            <div ref="chartRefDark" class="absolute -start-[500px] top-0 h-full w-full dark:start-0"></div>
        </div>
    </div>
</template>
