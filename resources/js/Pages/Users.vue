<template>
  <AdminLayout>
    <v-container>
      <!-- Main content of the page -->
      <v-card  width="100%"  elevation="0">
        <v-card-title class="text-center" style="background-color: darkblue; color: white; ">
          Users' List
          <v-spacer></v-spacer>
        </v-card-title>
        <br>
        <v-card-text>

          <!-- Print Button -->
          <v-btn @click="printUsers" class="mr-4" label elevation="2" color="red" style="text-transform: capitalize;">
            <v-icon left>mdi-printer</v-icon> Print
          </v-btn>
          <!-- Export Button -->
          <v-btn color="green" @click="exportUsers" class="mr-4" label elevation="2" style="text-transform: capitalize;">
            <v-icon left>mdi-download</v-icon> Export
          </v-btn>

          <v-btn @click="resetFilters" class="mr-4" color="primary" label elevation="2" style="text-transform: capitalize;">
  <v-icon left>mdi-refresh</v-icon> Reset
</v-btn>

          <!-- Add Member Button -->


          <v-row>
            <v-col>
                <v-text-field
            v-model="search"
            label="Search"
            class="mt-4"
            append-icon="mdi-magnify"
            variant="underlined"

          ></v-text-field>
            </v-col>
            <v-col>
                <v-select
            v-model="selectedStatus"
            :items="status"
            label="Filter by Membership Status"
            class="mt-4"
            :clearable="true"
            variant="underlined"
          ></v-select>
            </v-col>
          </v-row>
        </v-card-text>
        <!-- Data Table -->
        <v-data-table
  :headers="headers"
  :items="filteredUsers"
  :items-per-page="8"
  class="elevation-0 "
  style="text-transform: none"
  show-select
    fixed-header

>


<template v-slot:item.profile_pic="{ item }">
  <v-avatar size="50">
    <img :src="item.profile_pic ? `/storage/${item.profile_pic}` : '/Images/male-avatar-icon.png'" alt="User Avatar" height="65">
  </v-avatar>
</template>
<template v-slot:item.signedPdf="{ item }">
      <v-tooltip bottom>
        <template v-slot:activator="{ props }">
          <v-btn
            v-if="item.profile && item.profile.signedPdf"
            :href="`/storage/${item.profile.signedPdf}`"
            target="_blank"
            color="white"
            elevation="0"
            v-bind="props"
          >
            <v-icon>mdi-file-pdf-box</v-icon>
          </v-btn>
          <span v-else>No PDF</span>

        </template>
        <span>View Signed PDF</span>
      </v-tooltip>
    </template>
<template v-slot:item.roles="{ item }">
    <span>{{ item.roles.join(', ') }}</span>
  </template>
  <template v-slot:item.actions="{ item }">
    <v-chip icon color="white" @click="openDialog('edit', item)" elevation="0">
      <v-icon color="green">mdi-pencil</v-icon>
    </v-chip>
    <v-chip icon color="white" @click="openDialog('view', item)" elevation="0">
      <v-icon color="primary">mdi-eye</v-icon>
    </v-chip>
    <v-chip icon color="white" @click="openDialog('delete', item)">
      <v-icon color="red">mdi-delete</v-icon>
    </v-chip>
  </template>
</v-data-table>


      </v-card>

      <!-- Edit Dialog -->
      <v-dialog v-model="dialog.edit" max-width="800px">
        <v-card>
          <v-toolbar color="orange">
            <v-card-title >Edit User</v-card-title>
            <v-spacer></v-spacer>
            <v-btn @click="dialog.edit = false" color="white" icon><v-icon>mdi-close</v-icon></v-btn>
          </v-toolbar>
          <v-card-text>
            <v-row>
              <v-col>
                <v-btn @click="saveEdit(selectedUser)" class="mr-4" width="100%" color="green"> <v-icon>mdi-content-save</v-icon>Save</v-btn>

              </v-col>
              <v-col>
                <v-btn @click="dialog.edit = false" width="100%" color="red"><v-icon>mdi-cancel</v-icon>Cancel</v-btn>

              </v-col>
            </v-row>
            <!-- Edit form or content here -->
          </v-card-text>
        </v-card>
      </v-dialog>

      <!-- View Dialog -->
      <!-- View Dialog -->
<!-- View Dialog -->
<v-dialog v-model="dialog.view" max-width="800px">
  <v-card width="800">
    <v-card-title style="background-color: orange;" class="text-center">View User</v-card-title>
    <v-card-text>
      <v-row>
        <!-- User Information -->
        <v-col cols="12" md="4">
          <v-text-field
            v-model="selectedUser.name"
            label="Name"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            v-model="selectedUser.email"
            label="Email"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            :value="formattedRoles"
            label="Roles"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>

        <!-- Profile Information -->
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.surname"
            label="Surname"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.otherNames"
            label="Other Names"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.dept"
            label="Department"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.employmentType"
            label="Employment Type"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.employeeNo"
            label="Employee Number"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.dateOfBirth"
            label="Date of Birth"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.sex"
            label="Sex"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.religion"
            label="Religion"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.telR"
            label="Residential Phone"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.telCell"
            label="Cell Phone"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.currentAddress"
            label="Current Address"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.residence"
            label="Residence"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.postalAddress"
            label="Postal Address"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.homeDistrict"
            label="Home District"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.fatherName"
            label="Father's Name"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.fatherDOB"
            label="Father's Date of Birth"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.fatherOccupation"
            label="Father's Occupation"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.motherName"
            label="Mother's Name"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.motherDOB"
            label="Mother's Date of Birth"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.motherOccupation"
            label="Mother's Occupation"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.maritalStatus"
            label="Marital Status"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.spouseName"
            label="Spouse's Name"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.dateOfMarriage"
            label="Date of Marriage"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.spouseTel"
            label="Spouse's Phone"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.children"
            label="Children"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.siblings"
            label="Siblings"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="6" lg="4">
          <v-text-field
            v-model="selectedUser.profile.status"
            label="Membership Status"
            readonly
            variant="outlined"
          ></v-text-field>
        </v-col>

      </v-row>
    </v-card-text>
  </v-card>
</v-dialog>



      <!-- Delete Dialog -->
      <v-dialog v-model="dialog.delete" max-width="500px">
        <v-card>
          <v-toolbar color="orange">
            <v-toolbar-title>Delete User</v-toolbar-title>
            <v-btn @click="dialog.delete = false" style="text-transform: capitalize;" color="white" icon elevation="0">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <!-- Delete confirmation message -->
            <div>Are you sure you want to delete "{{ selectedUser.name }}"?</div>
            <br>
            <v-row>
              <v-col>
                <v-btn @click="confirmDelete(selectedUser)" class="mr-4" style="text-transform: capitalize;" color="green" width="100%" rounded>
                  <v-icon>mdi-check</v-icon>Confirm
                </v-btn>
              </v-col>
              <v-col>
                <v-btn @click="dialog.delete = false" style="text-transform: capitalize;" color="red" width="100%" rounded>
                  <v-icon>mdi-cancel</v-icon>Cancel
                </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-container>
  </AdminLayout>
</template>
<script>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
  components: {
    AdminLayout,
  },
  data() {
    return {
      users: [],
      selectedUser: {
        name: '',
        email: '',
        roles: [],
        profile: {
          surname: '' // Add other default fields as needed
        }
      },

      status: ['approved', 'pending', 'Declined'], // Add your roles here
      search: '',
      selectedRole: null,
      selectedStatus: null,
      headers: [
      { title: 'Profile Pic', value: 'profile_pic' },
      { title: 'Signed Form', value: 'signedPdf' },
      { title: 'Staff No.', value: 'profile.employeeNo' },
        { title: 'Name', value: 'name' },
        { title: 'Email', value: 'email' },
        { title: 'Membership Status', value: 'profile.status' },
        { title: 'Roles', value: 'roles' },
        { title: 'Actions', value: 'actions', sortable: false },
      ],
      dialog: {
        edit: false,
        view: false,
        delete: false,
      },
    };
  },
  created() {
    const { props } = usePage();
    this.users = props.users;
  },
  computed: {
    formattedRoles() {
      return this.selectedUser.roles.join(', ');
    },
    filteredUsers() {
    return this.users.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                              user.email.toLowerCase().includes(this.search.toLowerCase()) ||
                              (user.profile && user.profile.employeeNo && user.profile.employeeNo.toLowerCase().includes(this.search.toLowerCase()));

        const matchesRole = user.roles.includes('member'); // Ensure only users with role 'member' are included

        // Check if user.profile is not null before accessing its properties
        const matchesStatus = this.selectedStatus ? user.profile && user.profile.status === this.selectedStatus : true;

        return matchesSearch && matchesRole && matchesStatus;
    });
}


  },
  methods: {
    openDialog(type, user) {
      const defaultProfile = {
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
        children: '',
        siblings: ''
      };

      this.selectedUser = {
        ...user,
        profile: user.profile ? { ...defaultProfile, ...user.profile } : defaultProfile
      };

      this.dialog[type] = true;

      if (type === 'edit') {
        console.log('Editing user:', this.selectedUser);
      } else if (type === 'view') {
        console.log('Viewing user:', this.selectedUser);
      } else if (type === 'delete') {
        console.log('Deleting user:', this.selectedUser);
      }
    },
    saveEdit(user) {
      console.log('Saving edit:', user);
      this.dialog.edit = false;
    },
    confirmDelete(user) {
      console.log('Deleting user:', user);
      this.dialog.delete = false;
    },
    importUsers() {
      console.log('Importing users');
    },

    printUsers() {
    const hasSearchOrFilter = this.search || this.selectedRole || this.selectedStatus;
    const usersToPrint = hasSearchOrFilter ? this.filteredUsers : this.users;

    const filteredUsersForPrint = usersToPrint.filter(user => user.roles.includes('member'));

    // Create a new jsPDF instance with custom page size
    const doc = new jsPDF({
      orientation: 'portrait', // Change to 'landscape' if needed
      unit: 'mm',
      format: [297, 210] // Custom page size in mm (A4 size: 210mm x 297mm)
    });

    // Define columns for the PDF table
    const columns = [
      { header: 'Name', dataKey: 'name', width: 30 },
      { header: 'Email', dataKey: 'email', width: 40 },
      { header: 'Role Name', dataKey: 'roles', width: 40 },
      { header: 'Dept', dataKey: 'department_id', width: 30 },
      { header: 'Employment Type', dataKey: 'employmentType', width: 30 },
      { header: 'Employee No', dataKey: 'employeeNo', width: 30 },
      { header: 'Gender', dataKey: 'sex', width: 20 },
      { header: 'Religion', dataKey: 'religion', width: 30 },
      { header: 'Tel R', dataKey: 'telR', width: 30 },
      { header: 'Status', dataKey: 'status', width: 30 },
    ];

    // Prepare the data for the PDF table
    const data = filteredUsersForPrint.map(user => ({
      name: user.name,
      email: user.email,
      roles: user.roles.includes('member') ? 'member' : '', // Filter roles to include only 'member'
      department_id: user.profile ? user.profile.department_id : '',
      employmentType: user.profile ? user.profile.employmentType : '',
      employeeNo: user.profile ? user.profile.employeeNo : '',
      sex: user.profile ? user.profile.sex : '',
      religion: user.profile ? user.profile.religion : '',
      telR: user.profile ? user.profile.telR : '',
      status: user.profile ? user.profile.status : '',
    }));

    // Add the table to the PDF document
    doc.autoTable({
      columns: columns,
      body: data,
      margin: { top: 10, left: 10, right: 10, bottom: 30 }, // Adjust bottom margin for summary section
      styles: { fontSize: 8 }, // Adjust font size if needed
    });

    // Add the total number of members at the bottom of the PDF
    const totalMembers = filteredUsersForPrint.length;
    doc.setFontSize(12);
    doc.text(`Total Members: ${totalMembers}`, 10, doc.internal.pageSize.height -20);

    // Save the PDF file
    doc.save('users.pdf');
  },
    exportUsers() {
      // Determine if the user has applied any search or filter criteria
      const hasSearchOrFilter = this.search || this.selectedRole || this.selectedStatus;

      // Use filteredUsers if there are search or filter criteria, otherwise use all users
      const usersToExport = hasSearchOrFilter ? this.filteredUsers : this.users;

      // Generate worksheet from selected users
      const worksheet = XLSX.utils.json_to_sheet(usersToExport.map(user => ({
        Name: user.name,
        Email: user.email,
        'Role Name': user.roles.join(', '), // Assuming roles is an array
        'Surname': user.profile ? user.profile.surname : '',
        'Other Names': user.profile ? user.profile.otherNames : '',
        'Dept': user.profile ? user.profile.department_id : '',
        'Employment Type': user.profile ? user.profile.employmentType : '',
        'Employee No': user.profile ? user.profile.employeeNo : '',
        'Date of Birth': user.profile ? user.profile.dateOfBirth : '',
        'Sex': user.profile ? user.profile.sex : '',
        'Religion': user.profile ? user.profile.religion : '',
        'Tel R': user.profile ? user.profile.telR : '',
        'Tel Cell': user.profile ? user.profile.telCell : '',
        'Current Address': user.profile ? user.profile.currentAddress : '',
        'Residence': user.profile ? user.profile.residence : '',
        'Postal Address': user.profile ? user.profile.postalAddress : '',
        'Home District': user.profile ? user.profile.homeDistrict : '',
        'Father Name': user.profile ? user.profile.fatherName : '',
        'Father DOB': user.profile ? user.profile.fatherDOB : '',
        'Father Occupation': user.profile ? user.profile.fatherOccupation : '',
        'Mother Name': user.profile ? user.profile.motherName : '',
        'Mother DOB': user.profile ? user.profile.motherDOB : '',
        'Mother Occupation': user.profile ? user.profile.motherOccupation : '',
        'Marital Status': user.profile ? user.profile.maritalStatus : '',
        'Spouse Name': user.profile ? user.profile.spouseName : '',
        'Date of Marriage': user.profile ? user.profile.dateOfMarriage : '',
        'Spouse Tel': user.profile ? user.profile.spouseTel : '',
        'Children': user.profile ? user.profile.children : '',
        'Siblings': user.profile ? user.profile.siblings : '',
      })));

      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Users');

      // Generate Excel file with a relevant filename
      XLSX.writeFile(workbook, hasSearchOrFilter ? 'filtered_users.xlsx' : 'all_users.xlsx');
    },
    addMember() {
      console.log('Adding a member');
    },

    resetFilters() {
    this.search = '';
    this.selectedRole = null;
    this.selectedStatus = null;
  }
  },
};
</script>

<style scoped>

header.data-table{
    background-color: blue;
}
/* Add scoped styles here */
</style>
