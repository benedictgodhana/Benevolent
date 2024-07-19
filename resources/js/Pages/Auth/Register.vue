
<script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import {
      VContainer, VRow, VCol, VTextField, VCheckbox, VBtn, VAlert, VCard, VCardTitle, VCardText, VCardActions, VToolbar, VSpacer, VIcon, VDatePicker
  } from 'vuetify/components';

  const step = ref(1);
  const form = useForm({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      surname: '',
      otherNames: '',
      dept: '',
      employmentType: '',
      employeeNo: '',
      dateOfBirth: '',
      sex: '',
      religion: '',
      telR: '',
      telCell: '',
      currentAddress: '',
      residence: '',
      postalAddress: '',
      homeDistrict: '',
      fatherName: '',
      fatherDOB: '',
      fatherOccupation: '',
      motherName: '',
      motherDOB: '',
      motherOccupation: '',
      maritalStatus: '',
      spouseName: '',
      dateOfMarriage: '',
      spouseTel: '',
      children: [{ name: '', dob: '' }],
      siblings: [],
      agree: false,
  });


  const addSibling = () => {
      form.siblings.push({ name: '', dob: '' });
  };

  const removeSibling = (index) => {
      form.siblings.splice(index, 1);
  };

  const nextStep = () => {
      step.value += 1;
  };

  const prevStep = () => {
      step.value -= 1;
  };

  const addChild = () => {
      form.children.push({ name: '', dob: '' });
  };

  const removeChild = (index) => {
      form.children.splice(index, 1);
  };

  const submit = () => {
      form.post(route('register'), {
          onFinish: () => form.reset(),
      });
  };

  // Validation for each step
  const validateStep = (step) => {
    switch (step) {
      case 1:
        // Validate Step 1 fields
        return form.name !== '' && form.email !== '' && form.password !== '' && form.password_confirmation !== '';
      case 2:
        // Validate Step 2 fields including the new ones
        return form.surname !== '' && form.otherNames !== '' && form.dept !== '' && form.employeeNo !== '' && form.dateOfBirth !== '' && form.sex !== '' && form.religion !== '' && form.telR !== '' && form.telCell !== '' && form.currentAddress !== '' && form.employmentType !== '' && form.residence !== '' && form.postalAddress !== '' && form.homeDistrict !== '';
      case 3:
        // Validate Step 3 fields including the new ones
        if (form.maritalStatus === 'Married') {
          return form.fatherName !== '' && form.fatherDOB !== '' && form.fatherOccupation !== '' && form.motherName !== '' && form.motherDOB !== '' && form.motherOccupation !== '' && form.spouseName !== '' && form.dateOfMarriage !== '' && form.spouseTel !== '';
        } else {
          return form.fatherName !== '' && form.fatherDOB !== '' && form.fatherOccupation !== '' && form.motherName !== '' && form.motherDOB !== '' && form.motherOccupation !== '';
        }
      default:
        return true;
    }
  };
</script>

<template>
    <v-container class="d-flex justify-center align-center min-vh-100">
      <v-card max-width="800" width="100%" class="mx-auto" elevation="0">

        <v-img
                src="/Images/University-Logo-Vertical-01.png"
                contain
                max-width="200"
                class="mx-auto mt-4"
            ></v-img>

        <v-toolbar flat style="background-color: darkblue;color:white">
          <v-btn :href="route('login')" style="text-transform: capitalize;"><v-icon>mdi-chevron-left</v-icon>Login</v-btn>
          <v-toolbar-title class="text-center">Membership Registration</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <template v-if="step === 1">
          <!-- Step 1: Account Information -->
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Name" v-model="form.name" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Email" v-model="form.email" required variant="outlined"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Password" v-model="form.password" type="password" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Confirm Password" v-model="form.password_confirmation" type="password" required variant="outlined"></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">Next <v-icon>mdi-chevron-right</v-icon></v-btn>
          </v-card-actions>
        </template>

        <template v-if="step === 2">
          <!-- Step 2: Personal Information -->
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Surname" v-model="form.surname" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Other Names" v-model="form.otherNames" required variant="outlined"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Department" v-model="form.dept" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Employee No." v-model="form.employeeNo" required variant="outlined"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Date of Birth" v-model="form.dateOfBirth" required variant="outlined" type="date"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-select :items="['Male', 'Female']" label="Sex" v-model="form.sex" required variant="outlined"></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-select :items="['Catholic', 'Protestant', 'Other']" label="Religion" v-model="form.religion" required variant="outlined"></v-select>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Tel. (R)" v-model="form.telR" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Tel. (Cell)" v-model="form.telCell" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
  <v-select v-model="form.employmentType" :items="['Permanent', '2 yr contract']" label="Employment Type" required outlined variant="outlined"></v-select>
</v-col>

              <v-col cols="12" md="6">
                <v-text-field label="Residence" v-model="form.residence" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Postal Address" v-model="form.postalAddress" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Home District" v-model="form.homeDistrict" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Current Address" v-model="form.currentAddress" required variant="outlined"></v-text-field>
              </v-col>

            </v-row>



          </v-card-text>
          <v-card-actions>
            <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="prevStep"><v-icon>mdi-chevron-left</v-icon>Previous</v-btn>
            <v-spacer></v-spacer>
            <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">Next <v-icon>mdi-chevron-right</v-icon></v-btn>
          </v-card-actions>
        </template>

        <template v-if="step === 3">
          <!-- Step 3: Family Information -->
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Father’s Name" v-model="form.fatherName" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Father’s Date of Birth" v-model="form.fatherDOB" required variant="outlined" type="date"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Mother’s Name" v-model="form.motherName" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Mother’s Date of Birth" v-model="form.motherDOB" required variant="outlined" type="date"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field label="Father’s Occupation" v-model="form.fatherOccupation" required variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="Mother’s Occupation" v-model="form.motherOccupation" required variant="outlined"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-select :items="['Married', 'Single']" label="Marital Status" v-model="form.maritalStatus" required variant="outlined"></v-select>
              </v-col>
              <v-col cols="12" md="6" v-if="form.maritalStatus === 'Married'">
                <v-text-field label="Spouse Name" v-model="form.spouseName" variant="outlined"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" v-if="form.maritalStatus === 'Married'">
              <v-text-field label="Date of Marriage" v-model="form.dateOfMarriage" variant="outlined" type="date"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" v-if="form.maritalStatus === 'Married'">
              <v-text-field label="Tel No. of Spouse" v-model="form.spouseTel" variant="outlined"></v-text-field>
            </v-col>
          </v-row>
          <v-row v-if="form.maritalStatus === 'Married'">
            <v-col cols="12">
              <h3>Children's Details</h3>
              <br>
              <v-row v-for="(child, index) in form.children" :key="index" class="mb-3">
                <v-col cols="6">
                  <v-text-field
                    label="Child's Name"
                    v-model="child.name"
                    required
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-menu
                    v-model="child.dobMenu"
                    :close-on-content-click="false"
                    :return-value.sync="child.dob"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                  >
                    <template #activator="{ on, attrs }">
                      <v-text-field
                        v-bind="attrs"
                        v-on="on"
                        label="Date of Birth"
                        v-model="child.dob"
                        required
                        variant="outlined"
                        type="date"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="child.dob"
                      @input="child.dobMenu = false"
                      no-title
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" class="d-flex justify-end" style="margin-top: -100px;">
                  <v-btn icon @click="removeChild(index)" style="background-color: darkblue;color:white">
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
              <v-btn @click="addChild" style="background-color: darkblue;color:white;text-transform: capitalize;" width="100%">Add Child</v-btn>
            </v-col>
          </v-row>

          <v-row>
      <v-col cols="12">
        <h3>Siblings' Details</h3>
        <br>
        <v-row v-for="(sibling, index) in form.siblings" :key="index" class="mb-3">
          <v-col cols="6">
            <v-text-field
              label="Sibling's Name"
              v-model="sibling.name"
              required
              variant="outlined"
            ></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-menu
              v-model="sibling.dobMenu"
              :close-on-content-click="false"
              :return-value.sync="sibling.dob"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template #activator="{ on, attrs }">
                <v-text-field
                  v-bind="attrs"
                  v-on="on"
                  label="Date of Birth"
                  v-model="sibling.dob"
                  required
                  variant="outlined"
                  type="date"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="sibling.dob"
                @input="sibling.dobMenu = false"
                no-title
              ></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" class="d-flex justify-end" style="margin-top: -100px;">
            <v-btn icon @click="removeSibling(index)" style="background-color: darkblue;color:white">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-btn @click="addSibling" style="background-color: darkblue;color:white;text-transform: capitalize;" width="100%">Add Sibling</v-btn>
      </v-col>
    </v-row>
        </v-card-text>
        <v-card-actions>
          <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="prevStep"><v-icon>mdi-chevron-left</v-icon>Previous</v-btn>
          <v-spacer></v-spacer>
          <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">Next <v-icon>mdi-chevron-right</v-icon></v-btn>
        </v-card-actions>
      </template>

      <template v-if="step === 4">
        <!-- Step 4: Confirmation -->
        <v-card-text>

          <v-row>
            <v-col cols="12">
              <v-checkbox label="I agree to the terms and conditions" v-model="form.agree" required></v-checkbox>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="prevStep"><v-icon>mdi-chevron-left</v-icon>Previous</v-btn>
          <v-spacer></v-spacer>
          <v-btn :loading="form.processing" @click="submit" :disabled="!validateStep(step) || !form.agree" style="background-color: darkblue;color:white;text-transform: capitalize;">
            Register <v-icon>mdi-account-plus</v-icon>
          </v-btn>
        </v-card-actions>
      </template>
    </v-card>
  </v-container>
</template>

  <style>
  .min-vh-100 {
      min-height: 100vh;
  }
  .mx-auto {
      margin-left: auto;
      margin-right: auto;
  }
  </style>


