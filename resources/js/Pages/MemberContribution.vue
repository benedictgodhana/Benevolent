<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
      <v-container width="100%" style="max-width: 1600px;">
        <v-row>
          <!-- Profile Card Section -->
          <v-col cols="12" md="3" class="mb-4">
            <v-card class="profile-card" elevation="15" style="border: 1px solid #e0e0e0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: darkblue">
              <v-card-text class="text-center py-4">
                <div>
                  <v-avatar size="200">
                    <img :src="`/storage/${profilePicUrl}`" alt="User Avatar" height="250">
                  </v-avatar>

                  <v-file-input
                    v-model="file"
                    label="Choose a file"
                    @change="onFileChange"
                    outlined
                    value=""
                    variant="underlined"
                    style="color: white"
                  ></v-file-input>
                  <v-btn @click="uploadProfilePic" style="text-transform: capitalize" width="100%" color="orange">Upload</v-btn>
                </div>

                <v-divider></v-divider>
                <div class="mt-3">
                  <v-divider></v-divider>
                  <v-list nav  style="background-color: darkblue; color: white">
                    <v-list-item v-for="(item, i) in links" :key="i">
                      <NavLink :href="item.routeName" class="v-list-item" style="color: white;">
                        <template v-slot:default="{ href, isActive, isExactActive, isLink }">
                          <v-list-item-icon v-if="item.icon" class="list-item-icon">
                            <v-icon :icon="item.icon" style="color: white"></v-icon>
                          </v-list-item-icon>
                          <v-list-item-content>
                            <v-list-item-title :class="{ 'primary--text': isExactActive }" v-text="item.text"></v-list-item-title>
                          </v-list-item-content>
                        </template>
                      </NavLink>
                    </v-list-item>
                  </v-list>
                </div>
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Form Section -->
          <v-col cols="12" md="9">
            <v-card elevation="15">
              <v-card-title style="background-color: darkblue; color: white" class="text-center">My Contribution History</v-card-title>
              <br>
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
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-select
                      v-model="selectedFilter"
                      :items="filters"
                      label="Filter by"
                      variant="outlined"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-btn @click="downloadCSV" style="text-transform: capitalize;" color="green">
                      <v-icon>mdi-download</v-icon> Download Report
                    </v-btn>
                  </v-col>
                </v-row>


                <v-table>
                  <thead style="background-color: darkblue; color: white;">
                    <tr>
                      <th>Date</th>
                      <th>Description</th>
                      <th>Amount (Ksh)</th>
                      <th>Transaction code</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in paginatedItems" :key="index">
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
                <br>

                <v-divider></v-divider>
                    <v-card-title class="text-h5" style="background-color:orange;color:black">Total Amount: Ksh. {{ totalAmount }}</v-card-title>
                    <v-divider></v-divider>

                <div class="text-center mt-6">
                  <v-pagination
                    v-model="page"
                    :length="pageCount"
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
      </v-container>

      <v-dialog v-model="dialog" max-width="600px">
        <v-card>
          <v-toolbar style="background-color: darkblue; color: white;">
            <v-toolbar-title>Contribution Details</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon dark v-bind="attrs" v-on="on" @click="dialog = false" style="background-color: white;">
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
            <v-btn style="background-color: darkblue; color: white; text-transform: capitalize;" class="mt-6" width="100%" @click="dialog = false">Close</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>
    </AuthenticatedLayout>
  </template>
<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavLink from '@/Components/NavLink.vue';

const links = [
  { text: 'My Profile', routeName: 'dashboard', icon: 'mdi-account' },
  { text: 'My Contribution', routeName: 'contribution', icon: 'mdi-cash' },
  { text: 'Notifications', routeName: 'notifications', icon: 'mdi-bell' },
  { text: 'My Settings', routeName: 'settings', icon: 'mdi-cog' }
];

const search = ref('');
const selectedFilter = ref(null);
const filters = ['All', 'Option 1', 'Option 2'];
const page = ref(1);
const itemsPerPage = 7;
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

const totalAmount = computed(() => {
  const sum = filteredItems.value.reduce((total, item) => total + parseFloat(item.amount), 0);
  return sum.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
});
;


const paginatedItems = computed(() => {
  const start = (page.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredItems.value.slice(start, end);
});

const pageCount = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage));

const profilePicUrl = ref(user.profile_pic ? `/storage/${user.profile_pic}` : '/default-avatar.png');
const form = useForm({
  profilePic: null,
});

// Fetch profile picture on mount
onMounted(() => {
  profilePicUrl.value = user.profile_pic || '/default-avatar.png'; // Fallback to a default avatar
});

const onFileChange = (event) => {
  form.profilePic = event.target.files[0];
};

const uploadProfilePic = () => {
  form.post('/user/profile-pic', {
    preserveScroll: true,
    onSuccess: (page) => {
      profilePicUrl.value = page.props.auth.user.profile_pic;
    },
  });
};

const downloadCSV = () => {
  const csvContent = [
    ['Date', 'Description', 'Amount (Ksh)', 'Transaction code'],
    ...filteredItems.value.map(item => [
      item.date,
      item.description,
      item.amount,
      item.code
    ])
  ].map(e => e.join(',')).join('\n');

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'contributions.csv';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

const formatDate = (date) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString(undefined, options);
};

const viewDetails = (item) => {
  selectedItem.value = item;
  dialog.value = true;
};

const selectedItem = ref({});
const dialog = ref(false);
</script>
