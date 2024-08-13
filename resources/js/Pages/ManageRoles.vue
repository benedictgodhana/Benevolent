<template>
    <AdminLayout>
      <v-container>
        <!-- Main content of the page -->
        <v-card max-width="1500" elevation="0">
          <v-card-title class="text-center" style="background-color: darkblue; color: white;">
            Roles Management
            <v-spacer></v-spacer>
            <!-- Import Button -->
          </v-card-title>
          <br>

          <v-card-text>
            <v-chip color="red" @click="importRoles" class="mr-4" label elevation="5">
              <v-icon left>mdi-upload</v-icon> Import
            </v-chip>
            <!-- Print Button -->
            <v-chip @click="printRoles" class="mr-4" label elevation="5">
              <v-icon left>mdi-printer</v-icon> Print
            </v-chip>
            <!-- Export Button -->
            <v-chip color="green" @click="exportRoles" class="mr-4" label elevation="5">
              <v-icon left>mdi-download</v-icon> Export
            </v-chip>
            <!-- Add Role Button -->
            <v-chip color="purple" @click="addRole" label elevation="5">
              <v-icon left>mdi-account-plus</v-icon> Add Role
            </v-chip>
          </v-card-text>

          <!-- Data Table with Search and Filters -->
          <v-card-text>
            <v-text-field
              v-model="search"
              label="Search Roles"
              append-icon="mdi-magnify"
              single-line
              hide-details
              class="mb-4"
              variant="underlined"
            ></v-text-field>

            <v-data-table
              :headers="headers"
              :items="filteredRoles"
              :items-per-page="10"
              class="elevation-0"
              show-select
            >
            <template v-slot:item.actions="{ item }">
            <v-menu bottom left>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  text
                  v-bind="attrs"
                  v-on="on"
                >
                  Actions
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="editRole(item)">
                  <v-list-item-title>Edit</v-list-item-title>
                </v-list-item>
                <v-list-item @click="deleteRole(item)">
                  <v-list-item-title>Delete</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-container>
    </AdminLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  const { props } = usePage();
  const roles = ref(props.roles); // Assuming 'roles' prop is passed from backend

  const headers = [
    { title: 'Name', value: 'name' },
    { title: 'Guard Name', value: 'guard_name' },
    { title: 'Actions', value: 'actions', sortable: false },
  ];

  const search = ref('');

  const filteredRoles = computed(() => {
    return roles.value.filter(role => {
      return role.name.toLowerCase().includes(search.value.toLowerCase()) ||
             role.guard_name.toLowerCase().includes(search.value.toLowerCase());
    });
  });

  // Dialog control
  const dialog = {
    edit: false,
    delete: false,
  };

  // Example function for editing a role
  const saveEdit = (role) => {
    // Implement your edit logic here
    console.log('Saving edit:', role);
    dialog.edit = false; // Close the dialog after saving
  };

  // Example function for deleting a role
  const confirmDelete = (role) => {
    // Implement your delete logic here
    console.log('Deleting role:', role);
    dialog.delete = false; // Close the dialog after deleting
  };

  // Example function for importing roles
  const importRoles = () => {
    // Implement your import logic here
    console.log('Importing roles');
  };

  // Example function for printing roles
  const printRoles = () => {
    // Implement your print logic here
    console.log('Printing roles');
  };

  // Example function for exporting roles
  const exportRoles = () => {
    // Implement your export logic here
    console.log('Exporting roles');
  };

  // Example function for adding a new role
  const addRole = () => {
    // Implement your add role logic here
    console.log('Adding a new role');
  };

  </script>

  <style scoped>
  /* Add scoped styles here */
  .v-data-table-header {
    background-color: #f5f5f5;
  }

  .v-data-table-header th {
    font-weight: bold;
    background-color: red;
  }
  </style>
