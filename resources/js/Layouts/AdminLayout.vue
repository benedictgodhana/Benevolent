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

    <!-- Navigation Links -->
    <v-list class="mt-16" nav>
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

      <v-list-group
            v-model="activeSettingsGroup"
            prepend-icon="mdi-cog"
            class="mt-2"
          >
            <template v-slot:activator="{ props }">
              <v-list-item v-bind="props" title="Settings" prepend-icon="mdi-cog" color="white" active-class="active-button"></v-list-item>
            </template>

            <v-list-item :href="route('adminsettings')" prepend-icon="mdi-account-settings" title="Account Settings" color="white" elevation="0" active-class="active-button"></v-list-item>
            <v-list-item :href="route('adminnotification')" prepend-icon="mdi-bell" title="Notifications Settings" color="white" elevation="0" active-class="active-button"></v-list-item>
            <v-list-item :href="route('profile')" prepend-icon="mdi-account" title="My profile" color="white" elevation="0" active-class="active-button"></v-list-item>
            <v-list-item :href="route('admincontribution')" prepend-icon="mdi-cash" title="My Contribution" color="white" elevation="0" active-class="active-button"></v-list-item>


          </v-list-group>

          <v-btn
    class="mr-4"
    elevation="2"
    color="red"
    width="100%"
    @click="$inertia.post(route('logout'))"
    style="color: white; background-color: red;text-transform: capitalize;margin-top: 100px">
    <v-icon left>mdi-logout</v-icon> Log Out
</v-btn>
    </v-list>

    <!-- Logout Button -->

  </v-navigation-drawer>

      <!-- App Bar -->
      <v-app-bar app class="border-b" height="90" elevation="2">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title style="background-color: ; white-space: normal;">
    <span class="mr-2">Welcome</span> {{ $page.props.auth.user.name }} !
</v-toolbar-title>

        <v-spacer></v-spacer>

        <!-- Search Bar -->

        <v-chip label elevation="5"  style="background-color: orange;" class="mr-4">
          <v-icon >mdi-bell-outline</v-icon> Notification
        </v-chip>
        <v-chip label class="mr-4" elevation="5" style="background-color: red;color:red" color="red">
            <ResponsiveNavLink :href="route('logout')" method="post" as="button" style="color:white;background-color: red;">
                                <v-icon>mdi-logout</v-icon> Log Out
                            </ResponsiveNavLink>
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
  import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

  const drawer = ref(true);
  const drawerWidth = '240px'; // Adjust as needed
  const search = ref('');

  const items = [
    { text: 'Dashboard', routeName: 'admin', icon: 'mdi-view-dashboard' },
    { text: 'Members', routeName: 'users', icon: 'mdi-account-multiple' },
    { text: 'Manage Roles', routeName: 'manage-roles', icon: 'mdi-account-group' },
    { text: 'Contributions', routeName: 'contributions', icon: 'mdi-cash' },
    { text: 'Manage Expenses', routeName: 'expenses', icon: 'mdi-cash' }

  ];
  </script>

  <style scoped>
  .list-item-icon {
    margin-right: 16px; /* Adjust the space between the icon and the text */
  }
  </style>
