<template>
    <v-app-bar
      app
      color="darkblue"
      dark
      elevation="5"
      class="nav-bar"
    >
      <v-container class="d-flex align-center justify-space-between">
        <!-- Logo -->
        <v-img
          src="/Images/logo.png"
          contain
          max-width="140"
          class="logo"
        ></v-img>

        <!-- Slots for additional items -->
        <template v-slot:additional-items>
          <!-- Example: Add your additional items here -->
          <v-btn text class="nav-link">Example Item 1</v-btn>
          <v-btn text class="nav-link">Example Item 2</v-btn>
          <!-- You can add more items as needed -->
        </template>

        <!-- Navigation Links (Uncomment if needed) -->
        <!-- <v-btn text class="nav-link">Home</v-btn>
        <v-btn text class="nav-link">About Us</v-btn>
        <v-btn text class="nav-link">Programs</v-btn>
        <v-btn text class="nav-link">Contact</v-btn> -->

        <!-- Search Bar (Uncomment if needed) -->
        <!-- <v-text-field
          solo
          flat
          hide-details
          prepend-inner-icon="mdi-magnify"
          label="Search"
          class="search-input"
        ></v-text-field> -->

        <!-- Auth Links -->

        <div class="auth-links">
          <template v-if="props.canLogin">
            <Link v-if="props.auth.user" :href="route('dashboard')" class="btn">
              Dashboard
            </Link>
            <template v-else>
              <Link v-if="props.canRegister" :href="route('register')" class="ms-4 nav-link" style="text-decoration: none;font-weight: 900;text-transform: uppercase;">
                Membership Registration
              </Link>
              <Link :href="route('login')" class=" ms-4 nav-link" style="text-decoration: none;font-weight: 900;text-transform: uppercase;">
                Log in
              </Link>
            </template>
          </template>
        </div>
      </v-container>
    </v-app-bar>
  </template>

  <script setup>
  import { Link, usePage } from '@inertiajs/vue3';
  import { VImg, VIcon } from 'vuetify/components';

  const { props } = usePage();
  const { canLogin, canRegister } = props;
  </script>

  <style scoped>
  .nav-bar {
    background-color: darkblue;
    height: 90px;
  }

  .logo {
    max-width: 150px;
    height: auto;
    margin-top: 20px;
  }

  .nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    transition: color 0.3s;
    margin-top: 20px;
  }

  .nav-link:hover {
    color: #ffb74d; /* Light orange color */
  }

  .btn {
    background-color: orange;
    color: white;
    text-transform: capitalize;
    margin-left: 1rem;
    font-weight: 500;
    transition: background-color 0.3s;
  }

  .btn:hover {
    background-color: #e64a19; /* Darker orange */
  }

  .ms-4 {
    margin-left: 1rem;
  }

  .search-input {
    max-width: 300px;
    background-color: white;
    border-radius: 25px;
    padding-left: 20px;
  }

  .auth-links {
    display: flex;
    align-items: center;
  }
  </style>
