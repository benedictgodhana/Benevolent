<template>
    <v-app>
      <!-- Navigation Drawer -->
      <v-navigation-drawer
        app
        class="border-r"
        v-model="drawer"
        absolute
        :style="{ 'min-width': drawerWidth }"
        elevation="0"
        style="background-color: darkblue; color: white;"
      >
        <!-- Logo and Divider -->
        <v-list-item>
          <v-list-item-avatar>
            <v-img src="Images/logo.png" alt="tailus logo" class="mt-13"></v-img>
          </v-list-item-avatar>
        </v-list-item>
        <v-divider></v-divider>

        <!-- Navigation Links -->
        <v-list class="mt-10">
          <v-list-item v-for="(item, i) in items" :key="i">
            <NavLink :href="item.routeName" class="v-list-item" style="color: white;">
              <template v-slot:default="{ href, isActive, isExactActive, isLink }">
                <v-list-item-icon v-if="item.icon" class="list-item-icon">
                  <v-icon :icon="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title :class="{ 'primary--text': isExactActive }" v-text="item.text"></v-list-item-title>
                </v-list-item-content>
              </template>
            </NavLink>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>

      <!-- App Bar -->
      <v-app-bar app class="border-b" height="90" elevation="2">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title></v-toolbar-title>
        <v-spacer></v-spacer>

        <!-- Search Bar -->
        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          label="Search"
          hide-details
          class="hidden md:flex ma-4 mt-6"
          variant="outlined"
          rounded
        ></v-text-field>

        <v-chip label elevation="5" :href="route('dashboard')" class="mr-4" style="background-color: darkblue;color:white;"><v-icon>mdi-chevron-left</v-icon>My Contribution Table</v-chip>
        <v-chip label elevation="5"  style="background-color: orange;">
          <v-icon >mdi-bell-outline</v-icon> Notification
        </v-chip>
        <v-chip icon class="ma-4" label elevation="5" style="background-color: red;color:white">
          <v-icon>mdi-logout</v-icon> Logout
        </v-chip>
      </v-app-bar>

      <!-- Main Content Area -->
      <v-main>
        <!-- Slot for Page Content -->
        <slot></slot>
      </v-main>
    </v-app>
  </template>

  <script setup>
  import { ref } from 'vue';
  import NavLink from '@/Components/NavLink.vue';

  const drawer = ref(true);
  const drawerWidth = '240px'; // Adjust as needed
  const search = ref('');

  const items = [
    { text: 'Dashboard', routeName: 'admin', icon: 'mdi-view-dashboard' },
    { text: 'Users', routeName: 'users', icon: 'mdi-account' },
    { text: 'Manage Roles', routeName: 'manage-roles', icon: 'mdi-account-group' },
    { text: 'Contributions', routeName: 'contributions', icon: 'mdi-cash' }
  ];
  </script>

  <style scoped>
  .list-item-icon {
    margin-right: 16px; /* Adjust the space between the icon and the text */
  }
  </style>
