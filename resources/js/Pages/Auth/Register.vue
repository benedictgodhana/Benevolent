<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { Inertia } from '@inertiajs/inertia';
import {
  VContainer, VRow, VCol, VSelect, VTextField, VCheckbox, VBtn, VAlert, VCard, VCardTitle, VCardText, VCardActions, VToolbar, VSpacer, VIcon, VDatePicker, VMenu
} from 'vuetify/components';

// Define props
const props = defineProps(['departments']);

// Step and form states
const step = ref(1);
const form = useForm({
  // Add your form fields here
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  surname: '',
  otherNames: '',
  department_id: '', // Updated to match department_id
  employmentType: '',
  contract_duration: '',
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
  signedPdf: null,
});

// PDF blob URL state
const pdfBlobUrl = ref(null);
const alert = ref({
  visible: false,
  type: 'success',
  message: '',
});

// Generate PDF function
const generatePdf = () => {
  const doc = new jsPDF();

  // Add Logo (Placeholder, adjust if needed)

  // Add Title
  doc.setFontSize(14);
  doc.text('Strathmore University Benevolent Fund (SUBF)', 50, 25); // Adjust position as needed

  // Add Registration Details Table
  doc.setFontSize(12);
  autoTable(doc, {
    head: [['Field', 'Value']],
    body: [
      ['Name', form.name || ''],
      ['Email', form.email || ''],
      ['Password', form.password || ''],
      ['Surname', form.surname || ''],
      ['Other Names', form.otherNames || ''],
      ['Department', getDepartment(form.department_id) || ''],
      ['Employment Type', form.employmentType || ''],
      ['Contract Duration in (years)', form.contract_duration || ''],
      ['Employee No.', form.employeeNo || ''],
      ['Date of Birth', form.dateOfBirth || ''],
      ['Sex', form.sex || ''],
      ['Religion', form.religion || ''],
      ['Telephone (Residence)', form.telR || ''],
      ['Telephone (Cell)', form.telCell || ''],
      ['Current Address', form.currentAddress || ''],
      ['Residence', form.residence || ''],
      ['Postal Address', form.postalAddress || ''],
      ['Home District', form.homeDistrict || ''],
      ['Father\'s Name', form.fatherName || ''],
      ['Father\'s Date of Birth', form.fatherDOB || ''],
      ['Father\'s Occupation', form.fatherOccupation || ''],
      ['Mother\'s Name', form.motherName || ''],
      ['Mother\'s Date of Birth', form.motherDOB || ''],
      ['Mother\'s Occupation', form.motherOccupation || ''],
      ['Marital Status', form.maritalStatus || ''],
      ['Spouse\'s Name', form.spouseName || ''],
      ['Date of Marriage', form.dateOfMarriage || ''],
      ['Spouse\'s Telephone', form.spouseTel || ''],
      ['Number of Children', form.children.length || '0'],
      ['Number of Siblings', form.siblings.length || '0'],
    ],
    startY: 10, // Pull the table up even higher
    margin: { top: 10 } // Adjust top margin to ensure content isn't cut off
  });

  // Debugging log
  console.log('Table final Y position:', doc.autoTable.previous.finalY);

  // Add Declaration
  doc.setFontSize(10); // Set font size for declaration

  const declarationY = doc.autoTable.previous.finalY + 10;
  console.log('Declaration Y position:', declarationY); // Debugging log

  doc.text('Declaration:', 10, declarationY);
  doc.text('a) I grant SUBF permission to collect my monthly contribution through Strathmore University payroll with effect from ……………', 10, declarationY + 10);
  doc.text('b) I declare that the information presented above is true to the best of my knowledge.', 10, declarationY + 20);

  // Add Signature and Witness Spaces
  doc.text('Name _______________________________________________ Sign _______________ Date _______________', 10, declarationY + 40);
  doc.text('c) Witnessed by _________________________________ ID. No. ______________ Sign _______________ Date _______________', 10, declarationY + 50);

  // Reset font size to default (optional)
  doc.setFontSize(12);

  // Save PDF to blob and generate URL
  const pdfBlob = doc.output('blob');
  pdfBlobUrl.value = URL.createObjectURL(pdfBlob);
};

// Add Sibling
const addSibling = () => {
  form.siblings.push({ name: '', dob: '' });
};

// Get Department Name
const getDepartment = (id) => {
  const department = props.departments.find(dept => dept.id === id);
  return department ? department.name : 'Unknown Department';
};

// Remove Sibling
const removeSibling = (index) => {
  form.siblings.splice(index, 1);
};

// Add Child
const addChild = () => {
  form.children.push({ name: '', dob: '' });
};

// Remove Child
const removeChild = (index) => {
  form.children.splice(index, 1);
};

// Next Step
const nextStep = () => {
  step.value += 1;
};

// Previous Step
const prevStep = () => {
  step.value -= 1;
};

// Submit Form
const submit = async () => {
  if (validateStep(step.value)) {
    const formData = new FormData();
    formData.append('formData', JSON.stringify(form));
    if (form.signedPdf) {
      formData.append('signedPdf', form.signedPdf);
    }

    try {
      await form.post(route('register'), {
        data: formData,
        onFinish: () => {
          form.reset();
          alert.value = {
            visible: true,
            type: 'success',
            message: 'Registration successful! Please check your email. Registration is pending.',
          };

          // Redirect using Inertia
          setTimeout(() => {
            Inertia.visit(route('register')); // Replace with your actual route name
          }, 2000); // Adjust delay as needed
        },
      });
    } catch (error) {
      console.error('Registration failed:', error);
      alert.value = {
        visible: true,
        type: 'error',
        message: 'Registration failed. Please try again.',
      };
    }
  } else {
    // Handle validation errors or notify the user
  }
};

// Validate Step
const validateStep = (step) => {
  switch (step) {
    case 1:
 // Check for required fields and password confirmation
 return form.name && form.email && form.password && form.password_confirmation &&
        form.password === form.password_confirmation &&
        form.password.length >= 8 &&
        /.+@strathmore\.edu$/.test(form.email);
   case 2:
      const basicValidation = form.surname !== '' &&
        form.otherNames !== '' &&
        form.department_id !== '' &&
        form.employeeNo !== '' &&
        form.dateOfBirth !== '' &&
        form.sex !== '' &&
        form.religion !== '' &&
        form.telR !== '' &&
        form.telCell !== '' &&
        form.currentAddress !== '' &&
        form.employmentType !== '' &&
        form.residence !== '' &&
        form.postalAddress !== '' &&
        form.homeDistrict !== '';

      const contractValidation = form.employmentType !== 'Contract' || form.contract_duration !== '';

      return basicValidation && contractValidation;
    case 3:
      if (form.maritalStatus === 'Married') {
        return form.fatherName !== '' && form.fatherDOB !== '' && form.fatherOccupation !== '' && form.motherName !== '' && form.motherDOB !== '' && form.motherOccupation !== '' && form.spouseName !== '' && form.dateOfMarriage !== '' && form.spouseTel !== '';
      } else {
        return form.fatherName !== '' && form.fatherDOB !== '' && form.fatherOccupation !== '' && form.motherName !== '' && form.motherDOB !== '' && form.motherOccupation !== '';
      }
    case 4:
      return form.agree;
    default:
      return true;
  }
};

// Handle File Upload
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.signedPdf = file;
  }
};
</script>


<template>
  <v-container class="d-flex justify-center align-center min-vh-100 page-background" style="max-width: 1900px">
    <v-card max-width="800" width="100%" class="mx-auto" elevation="0">

      <v-img
        src="/Images/University-Logo-Vertical-01.png"
        contain
        max-width="200"
        class="mx-auto mt-16"
      ></v-img>

      <v-card-text>
        <v-toolbar flat style="color:black" color="white">
        <v-btn :href="route('login')" style="text-transform: capitalize;"><v-icon>mdi-chevron-left</v-icon>Login</v-btn>
        <v-card-title class="text-center d-none d-md-inline-flex" style="margin-left: 120px;" >Membership Registration</v-card-title>
        <v-spacer></v-spacer>
      </v-toolbar>
      </v-card-text>

      <v-alert
      v-if="alert.visible"
      :type="alert.type"
      dismissible
      @click:close="alert.visible = false"
    >
      {{ alert.message }}
    </v-alert>

      <v-alert
      v-if="error"
      type="error"
      dismissible
      class="mb-4"
    >
      {{ error }}
    </v-alert>
    <template v-if="step === 1">
  <!-- Step 1: Account Information -->
  <v-card-text>
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          label="Name"
          v-model="form.name"
          required
          variant="outlined"
          prepend-inner-icon="mdi-account"
          :rules="[
            v => !!v || 'Name is required'
          ]"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          label="Email"
          v-model="form.email"
          required
          variant="outlined"
          prepend-inner-icon="mdi-email-outline"
          :rules="[
            v => !!v || 'Email is required',
            v => /.+@strathmore\.edu$/.test(v) || 'Email must be a valid @strathmore.edu address'
          ]"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          label="Password"
          v-model="form.password"
          type="password"
          required
          variant="outlined"
          prepend-inner-icon="mdi-lock"
          :rules="[
            v => !!v || 'Password is required',
            v => (v && v.length >= 6) || 'Password must be at least 6 characters long'
          ]"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          label="Confirm Password"
          v-model="form.password_confirmation"
          type="password"
          required
          variant="outlined"
          prepend-inner-icon="mdi-lock"
          :rules="[
            v => !!v || 'Confirm password is required',
            v => v === form.password || 'Passwords must match'
          ]"
        ></v-text-field>
      </v-col>
    </v-row>
  </v-card-text>
  <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn
      style="background-color: darkblue; color: white; text-transform: capitalize;"
      @click="nextStep"
      :disabled="!validateStep(step)"
    >
      Next <v-icon>mdi-chevron-right</v-icon>
    </v-btn>
  </v-card-actions>
</template>


      <template v-if="step === 2">
  <!-- Step 2: Personal Information -->
  <v-card-text>
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field
          label="Surname"
          v-model="form.surname"
          required
          variant="outlined"
          prepend-inner-icon="mdi-account"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Other Names"
          v-model="form.otherNames"
          required
          variant="outlined"
          prepend-inner-icon="mdi-account"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          label="Department"
          v-model="form.department_id"
          :items="departments"
          item-title="name"
          item-value="id"
          required
          variant="outlined"
          prepend-inner-icon="mdi-briefcase"
        ></v-select>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Employee No."
          v-model="form.employeeNo"
          required
          variant="outlined"
          prepend-inner-icon="mdi-badge-account"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          label="Date of Birth"
          v-model="form.dateOfBirth"
          required
          variant="outlined"
          type="date"
          prepend-inner-icon="mdi-calendar"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-select
          :items="['Male', 'Female']"
          label="Gender"
          v-model="form.sex"
          required
          variant="outlined"
          prepend-inner-icon="mdi-gender-male-female"
        ></v-select>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          :items="['Catholic', 'Protestant', 'Other']"
          label="Religion"
          v-model="form.religion"
          required
          variant="outlined"
          prepend-inner-icon="mdi-religion"
        ></v-select>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Tel. (R)"
          v-model="form.telR"
          required
          variant="outlined"
          prepend-inner-icon="mdi-phone"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Tel. (Cell)"
          v-model="form.telCell"
          required
          variant="outlined"
          prepend-inner-icon="mdi-cellphone"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-select
          v-model="form.employmentType"
          :items="['Permanent', 'Contract']"
          label="Employment Type"
          required
          outlined
          variant="outlined"
          prepend-inner-icon="mdi-briefcase-check"
        ></v-select>
      </v-col>

      <!-- Conditionally render this field based on employment type -->
      <v-col v-if="form.employmentType === 'Contract'" cols="12" md="4">
        <v-select
          v-model="form.contract_duration"
          :items="[1, 2, 3]"
          label="Contract Duration (Years)"
          required
          outlined
          variant="outlined"
          prepend-inner-icon="mdi-calendar-clock"
        ></v-select>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          label="Residence"
          v-model="form.residence"
          required
          variant="outlined"
          prepend-inner-icon="mdi-home"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Postal Address"
          v-model="form.postalAddress"
          required
          variant="outlined"
          prepend-inner-icon="mdi-email-outline"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Home District"
          v-model="form.homeDistrict"
          required
          variant="outlined"
          prepend-inner-icon="mdi-map-marker"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Current Address"
          v-model="form.currentAddress"
          required
          variant="outlined"
          prepend-inner-icon="mdi-map-marker"
        ></v-text-field>
      </v-col>
    </v-row>
  </v-card-text>
  <v-card-actions>
    <v-btn style="background-color: darkblue; color:white; text-transform: capitalize;" @click="prevStep">
      <v-icon>mdi-chevron-left</v-icon>Previous
    </v-btn>
    <v-spacer></v-spacer>
    <v-btn style="background-color: darkblue; color:white; text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">
      Next <v-icon>mdi-chevron-right</v-icon>
    </v-btn>
  </v-card-actions>
</template>


<template v-if="step === 3">
  <!-- Step 3: Family Information -->
  <v-card-text>
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field
          label="Father’s Name"
          v-model="form.fatherName"
          required
          variant="outlined"
          prepend-inner-icon="mdi-account"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Father’s Date of Birth"
          v-model="form.fatherDOB"
          required
          variant="outlined"
          type="date"
          prepend-inner-icon="mdi-calendar"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          label="Mother’s Name"
          v-model="form.motherName"
          required
          variant="outlined"
          prepend-inner-icon="mdi-account"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Mother’s Date of Birth"
          v-model="form.motherDOB"
          required
          variant="outlined"
          type="date"
          prepend-inner-icon="mdi-calendar"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          label="Father’s Occupation"
          v-model="form.fatherOccupation"
          required
          variant="outlined"
          prepend-inner-icon="mdi-briefcase"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          label="Mother’s Occupation"
          v-model="form.motherOccupation"
          required
          variant="outlined"
          prepend-inner-icon="mdi-briefcase"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          :items="['Married', 'Single']"
          label="Marital Status"
          v-model="form.maritalStatus"
          required
          variant="outlined"
          prepend-inner-icon="mdi-heart"
        ></v-select>
      </v-col>
      <v-col cols="12" md="4" v-if="form.maritalStatus === 'Married'">
        <v-text-field
          label="Spouse Name"
          v-model="form.spouseName"
          variant="outlined"
          prepend-inner-icon="mdi-account-heart"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4" v-if="form.maritalStatus === 'Married'">
        <v-text-field
          label="Date of Marriage"
          v-model="form.dateOfMarriage"
          variant="outlined"
          type="date"
          prepend-inner-icon="mdi-calendar-heart"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4" v-if="form.maritalStatus === 'Married'">
        <v-text-field
          label="Tel No. of Spouse"
          v-model="form.spouseTel"
          variant="outlined"
          prepend-inner-icon="mdi-phone"
        ></v-text-field>
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
              prepend-inner-icon="mdi-account-child"
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
                  prepend-inner-icon="mdi-calendar"
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
          <v-col cols="4">
            <v-text-field
              label="Sibling's Name"
              v-model="sibling.name"
              required
              variant="outlined"
              prepend-inner-icon="mdi-account-group"
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
                  prepend-inner-icon="mdi-calendar"
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
    <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="prevStep">
      <v-icon>mdi-chevron-left</v-icon>Previous
    </v-btn>
    <v-spacer></v-spacer>
    <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">
      Next <v-icon>mdi-chevron-right</v-icon>
    </v-btn>
  </v-card-actions>
</template>


      <template v-if="step === 4">
  <!-- Step 4: Agreement and Generate PDF -->
  <v-card-text>
    <!-- Terms and Conditions Section -->
    <div style="height:300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
      <h3>Terms and Conditions</h3>
      <p><strong>1. Introduction</strong></p>
      <p>Welcome to the Strathmore University Benevolent Fund (SUBF). By participating in this fund, you agree to comply with and be bound by the following terms and conditions.</p>

      <p><strong>2. Membership Eligibility</strong></p>
      <p>Membership is open to all full-time employees of Strathmore University. Membership is voluntary and can be terminated at any time in accordance with these terms and conditions.</p>

      <p><strong>3. Contributions</strong></p>
      <p>a) Members agree to contribute a fixed monthly amount to the SUBF through payroll deduction.</p>
      <p>b) The contribution amount may be reviewed periodically by the SUBF committee and members will be notified of any changes.</p>

      <p><strong>4. Benefits</strong></p>
      <p>a) The SUBF provides financial support to members in times of bereavement or severe hardship.</p>
      <p>b) The amount and nature of the benefits will be determined by the SUBF committee based on available funds and the specific needs of the member.</p>

      <p><strong>5. Confidentiality</strong></p>
      <p>All personal information and circumstances shared with the SUBF will be treated with strict confidentiality and will only be used for the purpose of administering the fund.</p>

      <p><strong>6. Termination of Membership</strong></p>
      <p>a) A member may terminate their membership at any time by providing written notice to the SUBF committee.</p>
      <p>b) Membership may also be terminated if a member ceases to be employed by Strathmore University.</p>

      <p><strong>7. Dispute Resolution</strong></p>
      <p>Any disputes arising from the operation or administration of the SUBF shall be resolved amicably through discussions between the member and the SUBF committee. If a resolution cannot be reached, the matter may be referred to the University's Human Resources department for mediation.</p>

      <p><strong>8. Amendments</strong></p>
      <p>The SUBF committee reserves the right to amend these terms and conditions at any time. Members will be notified of any changes and continued participation in the fund will constitute acceptance of the new terms.</p>

      <p><strong>9. Governing Law</strong></p>
      <p>These terms and conditions shall be governed by and construed in accordance with the laws of Kenya.</p>

      <p><strong>10. Contact Information</strong></p>
      <p>For any queries or further information, please contact the SUBF committee at subf@strathmore.edu.</p>
    </div>

    <!-- Agreement Checkbox -->
    <v-checkbox v-model="form.agree" label="I agree to the terms and conditions" required></v-checkbox>
  </v-card-text>

  <!-- Navigation Buttons -->
  <v-card-actions>
    <v-btn style="background-color: darkblue; color:white; text-transform: capitalize;" @click="prevStep">
      <v-icon>mdi-chevron-left</v-icon> Previous
    </v-btn>
    <v-spacer></v-spacer>
    <v-btn style="background-color: darkblue; color:white; text-transform: capitalize;" @click="nextStep" :disabled="!validateStep(step)">
      Next <v-icon>mdi-chevron-right</v-icon>
    </v-btn>
  </v-card-actions>
</template>

      <template v-if="step === 5">
  <br>
  <!-- Generate PDF Button -->
  <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="generatePdf">
    Generate PDF
  </v-btn>

  <!-- Step 5: PDF Review -->
  <v-card-text>
    <br>
    <!-- Instructions -->
    <div>
      <p>
        <strong>Instructions:</strong>
      </p>
      <ol>
        <li>Click the "Generate PDF" button to create the PDF document.</li>
        <li>Review the generated PDF in the iframe below.</li>
        <li>Download the PDF by right-clicking on it and selecting "Save as...".</li>
        <li>Print the PDF and sign it.</li>
        <li>Scan the signed document and upload it using the file input below.</li>
        <li>Click the "Submit" button to complete the process.</li>
      </ol>
    </div>
    <br>

    <!-- Display PDF in iframe -->
    <iframe v-if="pdfBlobUrl" :src="pdfBlobUrl" width="100%" height="600px"></iframe>

    <!-- File Upload Input -->
    <input type="file" @change="handleFileUpload" />

  </v-card-text>
  <v-card-actions>
    <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="prevStep"><v-icon>mdi-chevron-left</v-icon>Previous</v-btn>

    <v-spacer></v-spacer>
    <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="submit">
      Submit
    </v-btn>
  </v-card-actions>
</template>

      <template v-if="step === 6">
        <!-- Final Step: Signed PDF Upload -->
        <v-card-text>
          <input type="file" @change="handleFileUpload" />
        </v-card-text>
        <v-card-actions>
          <v-btn @click="prevStep" :disabled="step === 1"> <v-icon>mdi-chevron-left</v-icon> Previous </v-btn>
          <v-spacer></v-spacer>
          <v-btn style="background-color: darkblue;color:white;text-transform: capitalize;" @click="submit" :disabled="!validateStep(step)">Submit</v-btn>
        </v-card-actions>
      </template>

    </v-card>
  </v-container>
</template>

<style scoped>

.min-vh-100 {
    min-height: 100vh;
}

.page-background {
    background-color:gray; /* Change this color to whatever you prefer */
}
  /* Add your custom styles here */
</style>
