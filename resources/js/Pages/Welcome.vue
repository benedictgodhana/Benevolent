<template>
    <v-app>
      <Navbar /> <!-- Use the Navbar component -->

      <!-- Carousel Section -->
      <v-carousel height="600" hide-delimiters="true" show-arrows="hover" cycle>
        <v-carousel-item src="/Images/happy-family-silhouette-sunset.jpg" cover>
          <div class="carousel-item-overlay">
            <div class="overlay-text">
              <h1 class="animate-text">Welcome to Strathmore University Benevolent Fund(SUBF)</h1>
              <p class="animate-text" style="font-size: 18px">Supporting our community with care and compassion.</p>
            </div>
          </div>
        </v-carousel-item>
        <!-- Additional carousel items if needed -->
      </v-carousel>

      <v-container>
        <v-row>
          <!-- Existing Sections -->
          <v-col cols="12" md="4">
            <v-card style="margin-top: -130px; border-radius: 20px;" elevation="5">
              <v-img src="/Images/emotional-screaming-young-african-man-with-money.jpg" height="200px" cover />
              <v-card-title>Contribution Programs</v-card-title>
              <v-card-text>
                <v-list nav>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-hand-heart</v-icon>
                    </template>
                    <v-list-item-title>Monthly Contributions</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-charity</v-icon>
                    </template>
                    <v-list-item-title>One-Time Donations</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-calendar-check</v-icon>
                    </template>
                    <v-list-item-title>Event Sponsorships</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card style="margin-top: -130px; border-radius: 20px;" elevation="5">
              <v-img src="/Images/8136206.jpg" height="200px" cover />
              <v-card-title>Eligibility Criteria</v-card-title>
              <v-card-text>
                <v-list nav>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-account-check</v-icon>
                    </template>
                    <v-list-item-title>Resident Status</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-calendar</v-icon>
                    </template>
                    <v-list-item-title>Minimum Age Requirement</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-file</v-icon>
                    </template>
                    <v-list-item-title>Document Verification</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card style="margin-top: -130px; border-radius: 20px;" elevation="5">
              <v-img src="/Images/application-form-employment-document-concept.jpg" height="200px" cover />
              <v-card-title>How to Apply</v-card-title>
              <v-card-text>
                <v-list nav>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-pencil</v-icon>
                    </template>
                    <v-list-item-title>Fill Application Form</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-file-send</v-icon>
                    </template>
                    <v-list-item-title>Submit Documents</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <template v-slot:prepend>
                      <v-icon color="orange">mdi-check-decagram</v-icon>
                    </template>
                    <v-list-item-title>Receive Confirmation</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="mt-4">
    <v-row class="justify-center">
      <v-col cols="12" >
        <v-card class="pa-6 overlay-card" elevation="10" tile>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6" class="pa-0">
                <v-img
                  src="/Images/close-up-women-holding-hands.jpg"
                  height="100%"
                  class="overlay-image"
                  cover
                />
              </v-col>
              <v-col cols="12" md="6">
                <div class="overlay-content">
                  <v-card-title class="font-weight-bold white--text">
                    About Us
                  </v-card-title>
                  <v-card-text class="white--text">
                    The Strathmore University Benevolent Fund (SUBF) is dedicated to providing financial and material support to individuals and families in need within our community. Our mission is to make a positive impact through various contribution programs, eligibility support, and assistance in times of need.
                  </v-card-text>
                  <v-card-title class="font-weight-bold white--text">
                    Our Mission
                  </v-card-title>
                  <v-card-text class="white--text">
                    To support our community with essential resources and services.
                  </v-card-text>
                  <v-card-title class="font-weight-bold white--text">
                    Our Impact
                  </v-card-title>
                  <v-card-text class="white--text">
                    Engaging with the community through events and programs to drive positive change.
                  </v-card-text>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>



  <v-container class="mt-4">
    <v-row>
      <v-col cols="12">
        <v-card elevation="5" class="pa-4">
          <v-card-title class="font-weight-bold">Updates</v-card-title>
          <v-row>
            <v-col cols="12" md="6">
              <v-card class="overlay-card" elevation="4">
                <v-img
                  src="/path-to-your-image1.jpg"
                  class="overlay-image"
                  height="200px"
                  cover
                />
                <v-card-text class="overlay-content">
                  <v-card-title class="white--text">New Contribution Program Launched</v-card-title>
                  <v-card-subtitle class="white--text">We have introduced a new monthly contribution program to further support our community efforts.</v-card-subtitle>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col cols="12" md="6">
              <v-card class="overlay-card" elevation="4">
                <v-img
                  src="/path-to-your-image2.jpg"
                  class="overlay-image"
                  height="200px"
                  cover
                />
                <v-card-text class="overlay-content">
                  <v-card-title class="white--text">Eligibility Criteria Updated</v-card-title>
                  <v-card-subtitle class="white--text">The eligibility criteria for applying for the benevolent fund have been updated. Please review the new requirements.</v-card-subtitle>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
      <!-- Downloads Section -->
      <v-container class="mt-4">
    <v-row>
      <v-col cols="12" >
        <v-card elevation="5" class="pa-4">
          <v-card-title class="font-weight-bold">Downloads</v-card-title>
          <v-card-text>
            <v-list dense>
              <v-list-item>
                <v-list-item-avatar>
                  <v-icon large color="red">mdi-file-pdf</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>
                    <a href="/downloads/guide.pdf" download class="download-link">Fund Guide (PDF)</a>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item>
                <v-list-item-avatar>
                  <v-icon large color="blue">mdi-file-word</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>
                    <a href="/downloads/application-form.docx" download class="download-link">Application Form (DOCX)</a>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
      <!-- About Us Section -->

      <!-- Updates Section -->


      <!-- Footer Section -->
      <v-footer class="footer" style="background-color: darkblue; color: white;">
        <v-container>
          <v-row>
            <v-col cols="12" md="4">
              <v-img src="/Images/logo.png" height="60" contain></v-img>
            </v-col>
            <v-col cols="12" md="4">
              <h3>Contact Us</h3>
              <p>Email: info@strathmore.edu</p>
              <p>Phone: +254 700 000 000</p>
            </v-col>
            <v-col cols="12" md="4">
              <h3>Follow Us</h3>
              <v-btn icon elevation="0" color="transparent">
                <v-icon>mdi-facebook</v-icon>
              </v-btn>
              <v-btn icon color="transparent" elevation="0">
                <v-icon>mdi-twitter</v-icon>
              </v-btn>
              <v-btn icon color="transparent" elevation="0">
                <v-icon color="pink">mdi-instagram</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-footer>
    </v-app>
  </template>

  <script setup>
  import { reactive } from 'vue';
  import Navbar from '@/Components/Navbar.vue'; // Import the Navbar component
  import {
    VCard,
    VCardTitle,
    VCardText,
    VImg,
    VContainer,
    VRow,
    VCol,
    VCarousel,
    VCarouselItem,
    VBtn,
    VIcon,
    VFooter,
    VList,
    VListItem,
    VListItemTitle,
    VDivider,
  } from 'vuetify/components'; // Import Vuetify components

  const props = defineProps({
    canLogin: {
      type: Boolean,
    },
    canRegister: {
      type: Boolean,
    },
    laravelVersion: {
      type: String,
      required: true,
    },
    phpVersion: {
      type: String,
      required: true,
    },
  });

  const state = reactive({
    carouselItems: [
      { src: '/Images/successful-happy-business-team.jpg' },
      { src: '/Images/african-american-business-woman-by-window.jpg' },
      { src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg' },
      { src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg' },
    ],
  });
  </script>

  <style scoped>
.download-link {
  text-decoration: none;
  color: inherit;
  font-weight: 600;
  font-size: 16px;
}

.download-link:hover {
  color: #1976d2; /* Highlight color on hover */
}

.pa-4 {
  padding: 16px !important; /* Ensures padding is applied */
}

.font-weight-bold {
  font-weight: 700 !important; /* Ensures font weight is applied */
}

.v-list-item-avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
}

.v-divider {
  margin: 8px 0;
}
.overlay-card {
  position: relative;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5); /* Optional: Add background color for better text readability */


}

.overlay-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0.3; /* Adjust opacity as needed */
}

.overlay-content {
  position: relative;
  z-index: 1;
  padding: 20px;
  background-color: rgba(0, 0, 0, 0.5); /* Optional: Add background color for better text readability */
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.white--text {
  color: white;
}

.font-weight-bold {
  font-weight: bold;
}
  .pt-32 {
    padding-top: 8rem;
  }
  .md\:py-12 {
    padding-top: 3rem;
    padding-bottom: 3rem;
  }
  .mt-16 {
    margin-top: 4rem;
  }
  .v-carousel-item .v-image__image--contain {
    object-fit: contain;
  }
  .footer {
    background-color: #f5f5f5; /* Light gray background */
    padding: 2rem 0; /* Padding for top and bottom */
    position: relative;
    bottom: 0;
    width: 100%;
  }
  .carousel-item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .overlay-text {
    color: white;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    z-index: 10;
    text-align: center;
  }
  .animate-text {
    animation: fadeInUp 2s ease-in-out;
  }
  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  </style>
