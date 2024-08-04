<template>
    <Head title="Dashboard" />

    <AdminLayout>
      <v-container fluid>
        <v-row>
          <!-- Cards or sections for different dashboard elements -->
          <v-col cols="12" md="6" lg="4">
            <v-card class="pa-3" elevation="4" rounded>
              <v-card-title style="background-color: darkblue;color:white">Total Users</v-card-title>
              <v-divider></v-divider>
              <v-card-text style="font-size: 18px; font-weight: 700;">{{ totalUsers }}</v-card-text>
              <v-btn label width="100%" color="orange" style="text-transform: none;">View All Users</v-btn>
            </v-card>
          </v-col>

          <v-col cols="12" md="6" lg="4">
            <v-card class="pa-3" elevation="4" rounded>
              <v-card-title style="background-color: darkblue;color:white">Total Contributions</v-card-title>
              <v-divider></v-divider>
              <v-card-text style="font-size: 18px; font-weight: 700;">{{ totalContributions }}</v-card-text>
              <v-btn label width="100%" color="orange" style="text-transform: none;">View All Contributions</v-btn>
            </v-card>
          </v-col>

          <v-col cols="12" md="6" lg="4">
            <v-card class="pa-3" elevation="4" rounded>
              <v-card-title style="background-color: darkblue;color:white">Monthly Collection</v-card-title>
              <v-divider></v-divider>
              <v-card-text style="font-size: 18px; font-weight: 700;">{{ monthlyCollection }}</v-card-text>
              <v-btn label width="100%" color="orange" style="text-transform: none;">View All Monthly Collections</v-btn>
            </v-card>
          </v-col>

          <!-- Highcharts for Contribution Overview -->
          <v-col cols="12">
            <v-card class="pa-3" elevation="5">
              <v-card-title class="text-center" style="background-color: darkblue;color:white">Monthly Contribution Overview</v-card-title>
              <v-divider></v-divider>
              <highcharts :options="chartOptions" :key="chartKey"></highcharts>
              <v-btn-toggle v-model="selectedChartType" mandatory>
                <v-btn @click="switchGraph('line')" style="background-color: darkblue;color:white">
                  <v-icon>mdi-chart-line</v-icon>
                  Line
                </v-btn>
                <v-btn @click="switchGraph('bar')" style="background-color: orange;color:white" >
                  <v-icon>mdi-chart-bar</v-icon>
                  Bar
                </v-btn>
                <v-btn @click="switchGraph('pie')" style="background-color: green;color:white">
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
    contributionsData: Array,
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
    };
  },
  methods: {
    switchGraph(type) {
      this.chartOptions.chart.type = type;
      this.chartKey += 1;
    },
  },
  mounted() {
      Highcharts.setOptions({
        colors: ['green'], // Set custom colors if needed
      });
    },
};
</script>

  <style scoped>
  /* Add scoped styles here */
  </style>
