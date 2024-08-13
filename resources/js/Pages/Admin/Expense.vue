<template>
    <AdminLayout>
        <v-container>
            <v-card max-width="1500" elevation="0">
                <v-card-title
                    class="text-center"
                    style="background-color: darkblue; color: white"
                >
                    Expenses List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br />
                <v-card-text>
                   
                    <v-btn
                        @click="printExpenses"
                        class="mr-4"
                        label
                        elevation="5"
                        style="text-transform: capitalize"
                        color="red"
                    >
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn
                        color="green"
                        @click="exportExpenses"
                        class="mr-4"
                        label
                        elevation="5"
                        style="text-transform: capitalize"
                    >
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn
                        color="purple"
                        @click="dialog.add = true"
                        label
                        elevation="3"
                        style="text-transform: capitalize"
                    >
                        <v-icon left>mdi-currency-usd</v-icon> Add Expense
                    </v-btn>
                </v-card-text>
                <br />
                <v-row>
                    <v-col cols="12" sm="6" md="3">
                        <v-text-field
                            v-model="filters.search"
                            label="Search"
                            @input="filterExpenses"
                            variant="underlined"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <v-select
                            v-model="filters.member"
                            :items="members"
                            label="Filter by Member"
                            @change="filterExpenses"
                            variant="underlined"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <v-text-field
                            v-model="filters.startDate"
                            label="Start Date"
                            type="date"
                            variant="underlined"
                            @change="filterExpenses"
                        ></v-text-field>
                    </v-col>
                    <v-col>
                        <v-text-field
                            v-model="filters.endDate"
                            label="End Date"
                            type="date"
                            variant="underlined"
                            @change="filterExpenses"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <br />
                <v-data-table
                    :headers="headers"
                    :items="filteredExpenses"
                    :items-per-page="6"
                    class="elevation-0"
                    show-select
                    v-model:select="selectedItems"
                >
                    <template v-slot:item.user="{ item }">
                        <span>{{ item.user.name }}</span>
                    </template>
                    <template v-slot:item.amount="{ item }">
    <span style="font-weight: 900;color:green">{{ formatAmount(item.amount) }}</span>
</template>

                    <template v-slot:item.creator="{ item }">
                        <span>{{ item.creator.name }}</span>
                    </template>

                    <template v-slot:item.date="{ item }">
                        <span>{{ formatDateTime(item.date) }}</span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-chip
                            icon
                            color="white"
                            @click="openViewDialog(item)"
                        >
                            <v-icon color="primary">mdi-eye</v-icon>
                        </v-chip>

                        <v-chip
                            icon
                            color="white"
                            @click="dialog.delete = true"
                        >
                            <v-icon color="red">mdi-delete</v-icon>
                        </v-chip>
                    </template>
                </v-data-table>
                <v-card-title style="margin-top: -0px;background: green;color:white">Total Amount: Ksh. {{ formatAmount(totalAmount) }}</v-card-title>
            </v-card>
            <v-dialog v-model="dialog.add" max-width="800px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title
                            class="text-center"
                            style="font-weight: 900"
                            >Add Expense</v-card-title
                        >
                        <v-spacer></v-spacer>
                        <v-btn
                            @click="dialog.add = false"
                            icon
                            style="color: black; text-transform: capitalize"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>

                    <v-card-text>
                        <v-form @submit.prevent="saveNewExpense">
                            <v-text-field
                                v-model="form.date"
                                label="Date"
                                type="date"
                                variant="underlined"
                                prepend-inner-icon="mdi-calendar"
                                :rules="[rules.required]"
                            ></v-text-field>
                            <v-select
                                v-model="form.member"
                                :items="users"
                                label="Select Member (Optional)"
                                variant="underlined"
                                item-value="id"
                                required
                                item-title="name"
                                prepend-inner-icon="mdi-account"
                            ></v-select>
                            <v-select
                                v-model="form.contribution"
                                :items="contributions"
                                label="Select Contribution (Optional)"
                                variant="underlined"
                                item-value="id"
                                item-title="description"
                                required
                                prepend-inner-icon="mdi-text"
                            ></v-select>
                            <v-text-field
                                v-model="form.description"
                                label="Description"
                                variant="underlined"
                                :rules="[rules.required]"
                                required
                                prepend-inner-icon="mdi-text"
                            ></v-text-field>
                            <v-text-field
                                v-model="form.amount"
                                label="Amount"
                                type="number"
                                variant="underlined"
                                :rules="[rules.required]"
                                required
                                prepend-inner-icon="mdi-cash"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn
                            @click="saveNewExpense"
                            style="
                                background-color: green;
                                color: white;
                                text-transform: capitalize;
                            "
                            width="100%"
                        >
                            <v-icon>mdi-content-save-outline</v-icon> Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar
                v-model="snackbar.show"
                :timeout="3000"
                :color="snackbar.color"
            >
                {{ snackbar.text }}
            </v-snackbar>

            <v-dialog v-model="dialog.view" max-width="800px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title>View Expense</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.view = false" style="text-transform: capitalize;color:black;font-weight: 900"  icon><v-icon>mdi-close</v-icon></v-btn>


                    </v-toolbar>
                    <v-card-text>
                        <div>
                            Date: {{ formatDateTime(selectedExpense?.date) }}
                        </div>
                        <hr />
                        <br />
                        <div>
                            Description: {{ selectedExpense?.description }}
                        </div>
                        <hr />
                        <br />

                        <div style="font-weight: 900;color:green   ">Amount: Ksh. {{formatAmount(selectedExpense?.amount)  }}</div>
                        <hr />
                        <br />
                        <div>Expense Code: {{ selectedExpense?.code }}</div>
                        <hr />
                        <br />
                        <br>
                       <em> <div>
                            Created by: <strong> {{ selectedExpense?.creator_name }}</strong>
                        </div></em>
                        <!-- Assuming you have creator_name -->
                    </v-card-text>
                    <v-card-actions>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
    </AdminLayout>
</template>
<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import * as XLSX from 'xlsx';

const { props } = usePage();
const expenses = ref(props.expenses);
const users = ref(props.users); // Fetch users from props
const contributions = ref(props.contributions); // Fetch contributions from props

const filters = ref({
    search: "",
    member: "",
    startDate: "",
    endDate: "",
});


const formatAmount = (amount) => {
    return Math.floor(amount);
};


const headers = [
    { title: "Date", value: "date" },
    { title: "Contribution Title", value: "contribution_id" },
    { title: "Member", value: "user_id" },
    { title: "Description", value: "description" },
    { title: "Amount", value: "amount" },
    { title: "Expense Code", value: "code" },
    { title: "Created by", value: "creator_name" },
    { title: "Actions", value: "actions", sortable: false },
];

const rules = {
    required: (value) => !!value || "This field is required",
};

const totalAmount = computed(() => {
    return filteredExpenses.value.reduce((total, expense) => {
        // Ensure the amount is a number
        const amount = parseFloat(expense.amount);
        // Only add to total if amount is a valid number
        return total + (isNaN(amount) ? 0 : amount);
    }, 0);
});


const dialog = ref({
    edit: false,
    view: false,
    delete: false,
    add: false,
});

const openViewDialog = (expense) => {
    selectedExpense.value = expense;
    dialog.value.view = true;
};

const snackbar = ref({
    show: false,
    text: "",
    color: "",
});

const form = useForm({
    date: "",
    member: "",
    contribution: "",
    description: "",
    amount: "",
});

const selectedExpense = ref(null);

const filteredExpenses = computed(() => {
    const searchTerm = filters.value.search.toLowerCase();
    const startDate = filters.value.startDate
        ? new Date(filters.value.startDate)
        : null;
    const endDate = filters.value.endDate
        ? new Date(filters.value.endDate)
        : null;

    return expenses.value.filter((e) => {
        const date = new Date(e.date);
        return (
            (filters.value.search === "" ||
                (e.id && e.id.toString().includes(searchTerm)) ||
                (e.date && e.date.toLowerCase().includes(searchTerm)) ||
                (e.user_name && e.user_name.toLowerCase().includes(searchTerm)) ||
                (e.description && e.description.toLowerCase().includes(searchTerm)) ||
                (e.amount && e.amount.toString().includes(searchTerm)) ||
                (e.code && e.code.toLowerCase().includes(searchTerm))) &&
            (filters.value.member === "" ||
                (e.user_name && e.user_name === filters.value.member)) &&
            (!startDate || date >= startDate) &&
            (!endDate || date <= endDate)
        );
    });
});

const selectedItems = ref([]);

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);

    const getOrdinalSuffix = (day) => {
        if (day > 3 && day < 21) return "th";
        switch (day % 10) {
            case 1:
                return "st";
            case 2:
                return "nd";
            case 3:
                return "rd";
            default:
                return "th";
        }
    };

    const day = date.getDate();
    const dayWithSuffix = `${day}${getOrdinalSuffix(day)}`;
    const month = date.toLocaleString("default", { month: "long" });
    const year = date.getFullYear();
    const hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, "0");
    const amPm = hours >= 12 ? "PM" : "AM";
    const formattedHours = hours % 12 || 12;

    return `${month} ${dayWithSuffix}, ${year} at ${formattedHours}:${minutes} ${amPm}`;
};

const importExpenses = () => {
    // Logic for importing expenses
};
const printExpenses = () => {
    const doc = new jsPDF();

    // Define headers for print, excluding "Actions"
    const printHeaders = [
        { title: "Date" },
        { title: "Contribution Title" },
        { title: "Member" },
        { title: "Description" },
        { title: "Amount" },
        { title: "Expense Code" },
        { title: "Created by" }
    ];

    // Use selectedItems if they are selected, otherwise use all filteredExpenses
    const data = (selectedItems.value.length > 0 ? selectedItems.value : filteredExpenses.value).map(expense => [
        formatDateTime(expense.date),
        expense.contribution_id,
        expense.user_id, // Ensure you use the correct field name
        expense.description,
        formatAmount(expense.amount),
        expense.code,
        expense.creator_name
    ]);

    autoTable(doc, {
        head: [printHeaders.map(header => header.title)],
        body: data,
        margin: { top: 20 },
    });

    doc.save('expenses.pdf');
};


const exportExpenses = () => {
    const data = (selectedItems.value.length ? selectedItems.value : filteredExpenses.value).map(expense => ({
        Date: formatDateTime(expense.date),
        "Contribution Title": expense.contribution_id,
        Member: expense.user_id,
        Description: expense.description,
        Amount: formatAmount(expense.amount),
        "Expense Code": expense.code,
        "Created by": expense.creator_name,
    }));

    // Convert data to worksheet
    const ws = XLSX.utils.json_to_sheet(data);

    // Create a new workbook and add the worksheet to it
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Expenses");

    // Generate buffer
    const wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });

    // Create a Blob and download it
    const blob = new Blob([wbout], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "expenses.xlsx";
    link.click();
};
const saveNewExpense = async () => {
    if (!form.date || !form.description || !form.amount) {
        snackbar.value.text = "Please fill in all required fields.";
        snackbar.value.color = "red";
        snackbar.value.show = true;
        return;
    }

    try {
        const response = await form.post("/expenses", {
            onSuccess: () => {
                // Show success message
                snackbar.value.text = "Expense added successfully!";
                snackbar.value.color = "green";
                snackbar.value.show = true;

                // Open view dialog
                dialog.value.view = true;

                // Reset form and close add dialog
                form.reset();
                dialog.value.add = false;
            },
            onError: (errors) => {
                console.error("Failed to save expense:", errors);
                snackbar.value.text =
                    "Failed to save expense. Please try again.";
                snackbar.value.color = "red";
                snackbar.value.show = true;
            },
        });
    } catch (error) {
        console.error("Error:", error);
        snackbar.value.text = "An unexpected error occurred. Please try again.";
        snackbar.value.color = "red";
        snackbar.value.show = true;
    }
};

const saveEdit = (expense) => {
    // Logic for saving the edited expense
    dialog.edit = false;
};

const confirmDelete = (expense) => {
    // Logic for deleting the expense
    dialog.delete = false;
};

const filterExpenses = () => {
    // Trigger filtering logic
};



</script>
