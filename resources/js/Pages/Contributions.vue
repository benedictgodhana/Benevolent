<template>
    <AdminLayout>
      <v-container>
        <v-card max-width="1500" elevation="0">
          <v-card-title class="text-center" style="background-color: darkblue; color: white;">
            Contributions List
            <v-spacer></v-spacer>
          </v-card-title>
          <br>
          <v-card-text>
            <v-chip color="red" @click="importContributions" class="mr-4" label elevation="5">
              <v-icon left>mdi-upload</v-icon> Import
            </v-chip>
            <v-chip @click="printContributions" class="mr-4" label elevation="5">
              <v-icon left>mdi-printer</v-icon> Print
            </v-chip>
            <v-chip color="green" @click="exportContributions" class="mr-4" label elevation="5">
              <v-icon left>mdi-download</v-icon> Export
            </v-chip>
            <v-chip color="purple" @click="addContribution" label elevation="5">
              <v-icon left>mdi-currency-usd</v-icon> Add Contribution
            </v-chip>
          </v-card-text>
          <br>
          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-text-field v-model="filters.search" label="Search" @input="filterContributions" variant="underlined"></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-select v-model="filters.member" :items="members" label="Filter by Member" @change="filterContributions" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="filters.startDate"
                label="Start Date"
                type="date"
                variant="underlined"
                @change="filterContributions"
              ></v-text-field>

            </v-col>
            <v-col>
                <v-text-field
                v-model="filters.endDate"
                label="End Date"
                type="date"
                variant="underlined"
                @change="filterContributions"
              ></v-text-field>
            </v-col>
          </v-row>
          <br>
          <v-data-table
            :headers="headers"
            :items="filteredContributions"
            :items-per-page="8"
            class="elevation-0"
          >
            <template v-slot:item.user="{ item }">
              <span>{{ item.user.name }}</span>
            </template>
            <template v-slot:item.date="{ item }">
              <span>{{ formatDateTime(item.date) }}</span>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-dialog v-model="dialog.edit" max-width="500px">
                <template v-slot:activator="{ on }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-chip icon color="white" v-bind="on">
                        <v-icon color="green">mdi-pencil</v-icon>
                      </v-chip>
                    </template>
                    <span>Edit</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-title>Edit Contribution</v-card-title>
                  <v-card-text>
                    <v-btn @click="saveEdit(item)">Save</v-btn>
                    <v-btn @click="dialog.edit = false">Cancel</v-btn>
                  </v-card-text>
                </v-card>
              </v-dialog>
              <v-dialog v-model="dialog.view" max-width="500px">
                <template v-slot:activator="{ on }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-chip icon color="white" v-bind="on">
                        <v-icon color="primary">mdi-eye</v-icon>
                      </v-chip>
                    </template>
                    <span>View</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-title>View Contribution</v-card-title>
                  <v-card-text>
                    <div>ID: {{ item.id }}</div>
                    <div>Date: {{ formatDateTime(item.date) }}</div>
                    <div>Description: {{ item.description }}</div>
                    <div>Amount: {{ item.amount }}</div>
                    <div>Contribution Code: {{ item.code }}</div>
                  </v-card-text>
                </v-card>
              </v-dialog>
              <v-dialog v-model="dialog.delete" max-width="500px">
                <template v-slot:activator="{ on }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-chip icon color="white" v-bind="on">
                        <v-icon color="red">mdi-delete</v-icon>
                      </v-chip>
                    </template>
                    <span>Delete</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-title>Delete Contribution</v-card-title>
                  <v-card-text>
                    <div>Are you sure you want to delete this contribution?</div>
                    <v-btn @click="confirmDelete(item)">Confirm</v-btn>
                    <v-btn @click="dialog.delete = false">Cancel</v-btn>
                  </v-card-text>
                </v-card>
              </v-dialog>
            </template>
          </v-data-table>
        </v-card>
      </v-container>
    </AdminLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  const { props } = usePage();
  const contributions = ref(props.contributions);
  const filters = ref({
    search: '',
    member: '',
    startDate: '',
    endDate: ''
  });

  const headers = [
    { title: 'Date', value: 'date' },
    { title: 'Member Name', value: 'user.name' },
    { title: 'Description', value: 'description' },
    { title: 'Amount', value: 'amount' },
    { title: 'Contribution Code', value: 'code' },
    { title: 'Actions', value: 'actions', sortable: false },
  ];

  const dialog = {
    edit: false,
    view: false,
    delete: false,
  };

  const members = computed(() => {
    return [...new Set(contributions.value.map(c => c.user.name))];
  });

  const filteredContributions = computed(() => {
    const searchTerm = filters.value.search.toLowerCase();
    const startDate = filters.value.startDate ? new Date(filters.value.startDate) : null;
    const endDate = filters.value.endDate ? new Date(filters.value.endDate) : null;

    return contributions.value.filter(c => {
      const date = new Date(c.date);
      return (
        (filters.value.search === '' ||
          c.id.toString().includes(searchTerm) ||
          c.date.toLowerCase().includes(searchTerm) ||
          c.user.name.toLowerCase().includes(searchTerm) ||
          c.description.toLowerCase().includes(searchTerm) ||
          c.amount.toString().includes(searchTerm) ||
          c.code.toLowerCase().includes(searchTerm)
        ) &&
        (filters.value.member === '' || c.user.name === filters.value.member) &&
        (!startDate || date >= startDate) &&
        (!endDate || date <= endDate)
      );
    });
  });

  const formatDateTime = (dateTime) => {
  const date = new Date(dateTime);

  // Utility function to get ordinal suffix for day
  const getOrdinalSuffix = (day) => {
    if (day > 3 && day < 21) return 'th'; // Special case for 11th-13th
    switch (day % 10) {
      case 1: return 'st';
      case 2: return 'nd';
      case 3: return 'rd';
      default: return 'th';
    }
  };

  // Format day with ordinal suffix
  const day = date.getDate();
  const dayWithSuffix = `${day}${getOrdinalSuffix(day)}`;

  // Format month and year
  const month = date.toLocaleString('default', { month: 'long' });
  const year = date.getFullYear();

  // Format time in 12-hour format with AM/PM
  let hours = date.getHours();
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();
  const ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  const formattedTime = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${ampm}`;

  // Combine date and time
  return `${dayWithSuffix} ${month} ${year} ${formattedTime}`;
};


  const saveEdit = (contribution) => {
    console.log('Saving edit:', contribution);
    dialog.edit = false;
  };

  const confirmDelete = (contribution) => {
    console.log('Deleting contribution:', contribution);
    dialog.delete = false;
  };

  const importContributions = async () => {
    try {
      const input = document.createElement('input');
      input.type = 'file';
      input.accept = '.csv, .xlsx';
      input.onchange = async (event) => {
        const file = event.target.files[0];
        if (file) {
          // Process the file here (for example, send it to the backend for parsing and importing)
          console.log('File selected:', file);
          const formData = new FormData();
          formData.append('file', file);

          // Example of sending the file to the backend
          const response = await fetch('/contributions/import', {
            method: 'POST',
            body: formData,
          });

          if (response.ok) {
            const importedContributions = await response.json();
            contributions.value = [...contributions.value, ...importedContributions];
          } else {
            console.error('Failed to import contributions');
          }
        }
      };
      input.click();
    } catch (error) {
      console.error('Error importing contributions:', error);
    }
  };

  const printContributions = () => {
    console.log('Printing contributions');
  };

  const exportContributions = () => {
    console.log('Exporting contributions');
  };

  const addContribution = () => {
    console.log('Adding a new contribution');
  };

  const filterContributions = () => {
    console.log('Filtering contributions', filters.value);
  };
  </script>

  <style scoped>
  /* Add scoped styles here */
  </style>
