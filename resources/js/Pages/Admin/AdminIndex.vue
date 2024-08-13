<template>
    <Head title="Dashboard" />

    <AdminLayout >
        <v-container fluid>
            <v-row>
                <!-- Card for Total Users -->
                <v-col cols="12" md="3">
                    <v-card class="mt-10 overflow-visible" max-width="400">
                        <v-sheet class="v-sheet--offset mx-auto" color="red" elevation="12" max-width="calc(100% - 32px)" rounded="lg">
                            <v-sparkline :labels="labels" :model-value="value" color="white" line-width="2" padding="16"></v-sparkline>
                        </v-sheet>
                        <v-card-text class="pt-0">
                            <div class="text-h6 mt-4">
                                Total Users
                            </div>
                            <div class="subheading mt-3">
                                {{ totalUsers }}
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <v-icon class="me-2" size="small">
                                mdi-account
                            </v-icon>
                            <span class="text-caption text-grey font-weight-light">total users in the system</span>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Card for Total Contributions -->
                <v-col cols="12" md="3">
                    <v-card class="mt-10 overflow-visible" max-width="400">
                        <v-sheet class="v-sheet--offset mx-auto" color="green" elevation="12" max-width="calc(100% - 32px)" rounded="lg">
                            <v-sparkline :labels="labels" :model-value="value" color="white" line-width="2" padding="16"></v-sparkline>
                        </v-sheet>
                        <v-card-text class="pt-0">
                            <div class="text-h6 mt-4">
                                Total Contributions
                            </div>
                            <div class="subheading mt-3">
                              Ksh. {{ formatNumber(totalContributions) }}
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <v-icon class="me-2" size="small">
                                mdi-cash
                            </v-icon>
                            <span class="text-caption text-grey font-weight-light">total contributions made</span>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Card for Total Expense -->
                <v-col cols="12" md="3">
                    <v-card class="mt-10 overflow-visible" max-width="400">
                        <v-sheet class="v-sheet--offset mx-auto" color="orange" elevation="12" max-width="calc(100% - 32px)" rounded="lg">
                            <v-sparkline :labels="labels" :model-value="value" color="white" line-width="2" padding="16"></v-sparkline>
                        </v-sheet>
                        <v-card-text class="pt-0">
                            <div class="text-h6 mt-4">
                                Total Expense
                            </div>
                            <div class="subheading mt-3">
                              Ksh. {{ formatNumber(totalExpense) }}
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <v-icon class="me-2" size="small">
                                mdi-cash
                            </v-icon>
                            <span class="text-caption text-grey font-weight-light">total expenses incurred</span>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Card for Net Balance -->
                <v-col cols="12" md="3">
                    <v-card class="mt-10 overflow-visible" max-width="400">
                        <v-sheet class="v-sheet--offset mx-auto" color="blue" elevation="12" max-width="calc(100% - 32px)" rounded="lg">
                            <v-sparkline :labels="labels" :model-value="value" color="white" line-width="2" padding="16"></v-sparkline>
                        </v-sheet>
                        <v-card-text class="pt-0">
                            <div class="text-h6 mt-4">
                                Net Balance
                            </div>
                            <div class="subheading mt-3">
                                Ksh. {{ formatNumber(NetBalance) }}
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <v-icon class="me-2" size="small">
                                mdi-cash-multiple
                            </v-icon>
                            <span class="text-caption text-grey font-weight-light">net balance</span>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <!-- Highcharts for Contribution Overview -->
                <v-col cols="12">
                    <v-card class="pa-3 mt-4" elevation="0">
                        <highcharts :options="chartOptions" :key="chartKey"></highcharts>
                        <br>
                        <v-btn-toggle v-model="selectedChartType" mandatory>
                            <v-btn @click="switchGraph('line')" style="background-color: darkblue; color:white" class="mr-4">
                                <v-icon>mdi-chart-line</v-icon>
                                Line
                            </v-btn>
                            <v-btn @click="switchGraph('bar')" style="background-color: orange; color:white" class="mr-4">
                                <v-icon>mdi-chart-bar</v-icon>
                                Bar
                            </v-btn>
                            <v-btn @click="switchGraph('pie')" style="background-color: green; color:white">
                                <v-icon>mdi-chart-pie</v-icon>
                                Pie
                            </v-btn>
                        </v-btn-toggle>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AdminLayout>
</template>

<script>
import Highcharts from 'highcharts';
import HighchartsVue from 'highcharts-vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

export default {
    name: 'Dashboard',
    components: {
        Head,
        AdminLayout,
        highcharts: HighchartsVue.component,
    },
    props: {
        totalUsers: Number,
        totalContributions: Number,
        monthlyCollection: Number,
        totalExpense: Number,
        NetBalance: Number,
    },
    data() {
        return {
            chartKey: 0,
            chartOptions: {
                chart: {
                    type: 'line',
                },
                title: {
                    text: '',
                },
                xAxis: {
                    categories: [], // Array to hold month names
                    title: {
                        text: 'Months',
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Amount (Ksh)',
                    },
                },
                series: [{
                    name: 'Contributions',
                    data: [3000, 4000, 3500, 5000, 4900, 6000, 7000, 9100, 12500], // Replace with actual data
                    color: 'orange',
                }],
            },
            selectedChartType: 'line',
            labels: [
                '12am',
                '3am',
                '6am',
                '9am',
                '12pm',
                '3pm',
                '6pm',
                '9pm',
            ],
            value: [
                200,
                675,
                410,
                390,
                310,
                460,
                250,
                240,
            ],
        };
    },
    methods: {
        formatNumber(num) {
            return Math.floor(num).toLocaleString();
        },
        switchGraph(type) {
            this.chartOptions.chart.type = type;
            this.chartKey += 1;
        },
    },
};
</script>

<style scoped>
.v-sheet--offset {
    margin-top: -36px;
}
</style>
