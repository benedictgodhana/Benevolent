<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <v-container width="100%" style="max-width: 1600px;">
            <v-card class="profile-card" elevation="0" variant="outlined">
                <v-card-text>
                    <v-row>
                        <v-col cols="2">
                            <v-avatar size="90" class="initials-av  atar">{{ userInitials }}</v-avatar>
                        </v-col>
                        <v-col>
                            <v-card-title>
                                <strong class="profile-label">Name:</strong> <span class="profile-content">{{ user.name }}</span>
                            </v-card-title>
                        </v-col>
                        <v-col>
                            <v-card-title>
                                <strong class="profile-label">Email:</strong> <span class="profile-content">{{ user.email }}</span>
                            </v-card-title>
                        </v-col>
                        <v-col>
                            <v-card-title>
                                <strong class="profile-label">Employee Number:</strong> <span class="profile-content">{{ userProfile.employeeNo }}</span>
                            </v-card-title>
                        </v-col>
                        <v-col>
                            <v-card-title>
                                <strong class="profile-label">Phone Number:</strong> <span class="profile-content">{{ userProfile.telR }}</span>
                            </v-card-title>
                        </v-col>
                        <v-col>
                            <v-card-title>
                                <strong class="profile-label">Gender:</strong> <span class="profile-content">{{ userProfile.sex }}</span>
                            </v-card-title>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>

        <v-container class="py-12" width="100%" style="max-width: 1600px;">
            <v-row>
                <!-- Left Card -->
                <v-col cols="12" md="3" class="mb-4">
                    <v-card elevation="3" height="100%" variant="outlined">
                        <v-img
                            src="/Images/beautiful-scenery-greenfield-cloudy-sky.jpg"
                            alt="Sample Image"
                            class="white--text align-end"
                            height="150px"
                            cover
                        >
                            <div class="overlay"></div>
                            <v-card-title class="overlay-title">Total Contribution</v-card-title>
                        </v-img>
                        <v-card-text class="text-center py-4">
                            <div class="total-amount"> Ksh. {{ totalAmount }}</div>
                            <v-divider></v-divider>
                            <div class="recent-activities-title">Recent Activities</div>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Center Table -->
                <v-col cols="12" md="9" class="mb-4">
                    <v-card style="border-radius: 10px;" elevation="3" variant="outlined">
                        <v-img
                            src="/Images/pine-tree.jpg"
                            alt="Sample Image"
                            class="white--text align-end"
                            height="70px"
                            cover
                        >
                            <div class="overlay"></div>
                            <v-card-title class="text-center overlay-title" style="color:white;font-weight:bolder;">Contribution History</v-card-title>
                        </v-img>
                        <hr>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="search"
                                        prepend-inner-icon="mdi-magnify"
                                        label="Search"
                                        single-line
                                        variant="outlined"
                                        hide-details
                                        rounded
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="selectedFilter"
                                        :items="filters"
                                        label="Filter by"
                                        variant="outlined"
                                        rounded
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-btn @click="downloadCSV" style="text-transform: capitalize;" color="green" rounded>
                                        <v-icon>mdi-download</v-icon>Download Report
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-table>
                                <thead style="background-color: darkblue; color: white;">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount(Ksh)</th>
                                        <th>Transaction code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in filteredItems" :key="index">
                                        <td>{{ formatDate(item.date) }}</td>
                                        <td>{{ item.description }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.code }}</td>
                                        <td>
                                            <v-btn class="action-button" icon elevation="0" @click="viewDetails(item)">
                                                <v-icon>mdi-eye</v-icon>
                                            </v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                            <div class="text-center mt-6">
                                <v-pagination
                                    v-model="page"
                                    :length="4"
                                    next-icon="mdi-menu-right"
                                    prev-icon="mdi-menu-left"
                                    rounded
                                    elevation="0"
                                ></v-pagination>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Example usage of AccessControl component -->
            <AccessControl role="admin">



            </AccessControl>

            <AccessControl permission="view_special_section">
                <v-row>
                    <v-col cols="12" class="mb-4">
                        <v-card elevation="3" variant="outlined">
                            <v-card-title class="text-center">Special Section</v-card-title>
                            <v-card-text>
                                <!-- Content for users with 'view_special_section' permission goes here -->
                                This section is only visible to users with the 'view_special_section' permission.
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </AccessControl>
        </v-container>

        <!-- View Details Dialog -->
        <v-dialog v-model="dialog" max-width="600px">
            <v-card>
                <v-toolbar style="background-color:darkblue;color:white ;">
                    <v-toolbar-title >Contribution Details</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon dark v-bind="attrs" v-on="on" @click="dialog = false"  style="background-color:white;">
                                <v-icon color="black">mdi-close</v-icon>
                            </v-btn>
                        </template>
                        <span>Close</span>
                    </v-tooltip>
                </v-toolbar>
                <v-card-text>
                    <p><strong>Date:</strong> {{ formatDate(selectedItem.date) }}</p>
                    <br>
                    <hr>
                    <p class="mt-3"><strong>Description:</strong> {{ selectedItem.description }}</p>
                    <br>
                    <hr>
                    <p class="mt-3"><strong>Amount:</strong> {{ selectedItem.amount }}</p>
                    <br>
                    <hr>
                    <p class="mt-3"><strong>Transaction Code:</strong> {{ selectedItem.code }}</p>
                    <br>
                    <hr class="mt-3">

                    <v-btn style="background-color:darkblue;color:white;text-transform:capitalize;" class="mt-6" width="100%" @click="dialog = false" >Close</v-btn>

                </v-card-text>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';
import AccessControl from '@/Components/AccessControl.vue';

import axiosInstance from '../plugins/api';

const search = ref('');
const selectedFilter = ref(null);
const filters = ['All', 'Option 1', 'Option 2'];

const user = usePage().props.auth.user;
const userProfile = user ? user.userProfile || {} : {};
const contributions = user ? user.contributions || [] : [];

const items = ref(contributions);

const filteredItems = computed(() => {
  return items.value.filter((item) => {
    const matchesFilter = !selectedFilter.value || selectedFilter.value === 'All' || item.description.includes(selectedFilter.value);
    const matchesSearch = !search.value || item.description.toLowerCase().includes(search.value.toLowerCase());
    return matchesFilter && matchesSearch;
  });
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('en-US', options);
};

const userInitials = computed(() => {
  const nameParts = user.name.split(' ');
  if (nameParts.length >= 2) {
    const firstNameInitial = nameParts[0].charAt(0).toUpperCase();
    const lastNameInitial = nameParts[1].charAt(0).toUpperCase();
    return firstNameInitial + lastNameInitial;
  } else {
    return user.name.charAt(0).toUpperCase() + (user.name.charAt(1) || '').toUpperCase();
  }
});

const downloadCSV = () => {
  const csvContent = 'data:text/csv;charset=utf-8,' + items.value.map(e => Object.values(e).join(',')).join('\n');
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', 'table_data.csv');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const totalAmount = computed(() => {
  return items.value.reduce((sum, item) => sum + parseFloat(item.amount.replace(/[^0-9.-]+/g,"")), 0).toFixed(0);
});

const dialog = ref(false);
const selectedItem = ref({});

const viewDetails = (item) => {
  selectedItem.value = item;
  dialog.value = true;
};
</script>

<style scoped>
.action-button {
    padding: 5px 10px;
    color: black;
    cursor: pointer;
}

.action-button:hover {
    background-color: YELLOW;
}

v-table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid black;
    padding: 8px;
}

v-table thead {
    background-color: darkblue;
    color: white;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.overlay-title {
    position: absolute;
    bottom: 0;
    width: 100%;
    text-align: center;
    color: white;
    font-weight: bolder;
    font-size: 1.25rem;
    padding: 0.5rem;
}

.total-amount {
    font-size: 2rem;
    font-weight: bold;
    color: darkblue;
}

.recent-activities-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-top: 1rem;
}

.activity-description {
    font-size: 1rem;
    font-weight: bold;
}

.activity-details {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: gray;
}

.profile-card {
    padding: 20px;
    background-color: darkblue;
    height: 100px;
    color: white;
}

.initials-avatar {
    background-color: white;
    color: black;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: -30px;
    margin-left: -20px;
}

.profile-label {
    font-size: 18px;
    font-weight: bold;
}

.profile-content {
    font-size: 18px;
}

@media (max-width: 600px) {
    .profile-label,
    .profile-content {
        font-size: 16px;
    }
}
</style>
