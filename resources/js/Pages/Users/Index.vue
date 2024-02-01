<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';


import { ref, createVNode } from 'vue';
const open = ref(false);
const ref1 = ref(null);
const ref2 = ref(null);
const ref3 = ref(null);
const ref4 = ref(null);
const ref5 = ref(null);
const current = ref(0);
const steps = [
    {
        title: 'The title of this page',
        description: 'This will be the guides or list of table of all the users that are active and existed',
        target: () => ref1.value && ref1.value.$el,
    },
    {
        title: 'Add a User',
        description: 'This button will open up a modal so that you can add a new user to the list of users',
        target: () => ref2.value && ref2.value.$el,
    },
    {
        title: 'Generate Excel',
        description: 'This button will generate an excel file, all the users will be generated',
        target: () => ref3.value && ref3.value.$el,
    },
    {
        title: 'Tagging a user',
        description: 'Tag a user if you want to tag it as resign',
        target: () => ref4.value && ref4.value.$el,
    },
    {
        title: 'Edit a user',
        description: 'This is the button to edit the users credentials..',
        target: () => ref5.value && ref5.value.$el,
    },
];
const handleOpen = val => {
    open.value = val;
};

</script>

<template>
    <Head title="Usermaintinance" />

    <AdminLayout>
        <template #header>
        </template>
        <a-float-button v-on:click="handleOpen(true)" :style="{
            right: '24px',
        }">
            <template #icon>
                <QuestionCircleOutlined />
            </template>
        </a-float-button>

        <div class="py-4">


            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-page-header ref="ref1"
                    style=" margin-bottom: 2px; border: 1px solid rgb(235, 237, 240) ;border-radius: 10px; height: 50px; display: flex; align-items: center; "
                    title="Users Maintinance"
                    :avatar="{ src: 'https://avatars1.githubusercontent.com/u/8186664?s=460&v=4' }">
                </a-page-header>
                <div class="flex justify-end mx-1 py-3">
                    <a-tooltip placement="top">
                        <template #title>
                            <span>Add Users?</span>
                        </template>
                        <a-button class="mx-1" shape="square" ref="ref2" v-on:click="createuserModal">
                            Add
                        </a-button>
                    </a-tooltip>
                    <a-tooltip placement="top">
                        <template #title>
                            <span>Generate an excel</span>
                        </template>
                        <a-button class="mx-1" shape="square" ref="ref3" v-on:click="generateButtonExcel">
                            Generate
                        </a-button>
                    </a-tooltip>


                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <a-table :data-source="get_users" :columns="columns" class="components-table-demo-nested" bordered>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'operation'">
                                <div class="flex">
                                    <a-tooltip placement="top">
                                        <template #title>

                                            <span>Tag?</span>
                                        </template>
                                        <a-button class="mx-1" shape="square" ref="ref4"
                                            v-on:click="resignReactive(record)">
                                            <template #icon>
                                                <TagOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                    <a-tooltip placement="top">
                                        <template #title>
                                            <span>Edit?</span>
                                        </template>
                                        <a-button class="mx-1" shape="square" ref="ref5" v-on:click="edituserModal(record)">
                                            <template #icon>
                                                <EditOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                </div>
                            </template>
                            <template v-else-if="column.key === 'usertype_name'">
                                <span>
                                    <a-tag v-if="record.usertype_name === 'Treasury'" color="green">
                                        {{ record.usertype_name }}
                                    </a-tag>
                                    <a-tag v-else-if="record.usertype_name === 'Admin'" color="geekblue">
                                        {{ record.usertype_name }}
                                    </a-tag>
                                    <a-tag v-else color="purple">
                                        {{ record.usertype_name }}
                                    </a-tag>
                                </span>
                            </template>
                            <template v-else-if="column.key === 'user_status'">
                                <span>
                                    <a-tag v-if="record.user_status === 'active'" color="green">
                                        {{ record.user_status }}
                                    </a-tag>
                                    <a-tag v-else-if="record.user_status === 'resigned'" color="red">
                                        {{ record.user_status }}
                                    </a-tag>
                                </span>
                            </template>
                        </template>

                    </a-table>
                </div>
            </div>
            <a-tour v-model:current="current" :open="open" :steps="steps" @close="handleOpen(false)">
                <template #indicatorsRender="{ current, total }">
                    <span>{{ current + 1 }} / {{ total }}</span>
                </template>
                <template #buttonRender>
                    <a-button type="primary" size="large">My Custom Button</a-button>
                </template>
            </a-tour>

            <a-modal style="top: 20px" v-model:open="openModalCreate" width="1000px" title="Add User" :ok-button-props="{ hidden: true }"
                :cancel-button-props="{ hidden: true }">
                <form @submit.prevent="createFormSubmit">
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Employee Name
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>
                    <a-auto-complete v-model:value="createUsers.name" :options="optionsUser" style="width: 100%"
                        placeholder="input here" @search="handleSearchUsers" @select="handleSelectUser1" />
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Username
                        </p>
                    </div>
                    <a-input v-model:value="createUsers.username" placeholder="Username">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Business Unit
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>
                    <a-auto-complete class="" v-model="createUsers.businessunit_id" :options="optionsBunit"
                        style="width: 100%" placeholder="input here" @search="handleSearchBunit"
                        @select="handleSelectBunit1" />

                    <div class="flex">
                        <p class="font-bold mt-3">
                            Contact Number
                        </p>
                    </div>
                    <a-input v-model:value="createUsers.ContactNo" placeholder="Contact Number">
                        <template #prefix>
                            <ContactsOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Contact Number">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>

                    <div class="flex">
                        <p class="font-bold mt-3">
                            Department
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>
                    <a-auto-complete v-model="createUsers.department_id" :options="optionsDepartment" style="width: 100%"
                        placeholder="input here" @search="handleSearchDepartment" @select="handleSelectDepartment1" />


                    <div class="flex">
                        <p class="font-bold mt-3">
                            Company
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>

                    <a-auto-complete class="" v-model="createUsers.company_id" :options="optionsCompany" style="width: 100%"
                        placeholder="search company" @search="handleSearchCompany" @select="handleSelect1" />
                    <p class="font-bold mt-3">
                        Password
                    </p>
                    <a-input v-model:value="createUsers.password" placeholder="Password" type="password">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <p class="font-bold mt-3">
                        Confirm Passsword
                    </p>
                    <a-input v-model:value="createUsers.password_confirmation" type="password"
                        placeholder="Confirm Password">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-input v-model="createUsers.empid" hidden>
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>

                    <p class="font-bold mt-3">
                        Select user type
                    </p>
                    <a-select ref="select" v-model:value="createUsers.usertype_id" style="width: 100%" @focus="focus"
                        class="" @change="handleChange">

                        <!-- <a-select-option>Select UserType</a-select-option> -->
                        <a-select-option v-for="usert in userType" v-model:value="usert.usertype_id">{{ usert.usertype_name
                        }}</a-select-option>
                    </a-select>


                    <div class="flex">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-0 mt-4 ml-auto"
                            type="submit">
                            Submit
                        </button>
                    </div>
                </form>
            </a-modal>

            <a-modal v-model:open="openModal" width="1000px" title="Edit User" :ok-button-props="{ hidden: true }"
                :cancel-button-props="{ hidden: true }">
                <form @submit.prevent="editFormSubmit">
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Employee Name
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>
                    <a-auto-complete v-model:value="selectedData.name" v-model="searchName" :options="optionsUser"
                        style="width: 100%" placeholder="input here" @search="handleSearchUsers" />
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Username
                        </p>
                    </div>
                    <a-input v-model:value="selectedData.username" placeholder="Username">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div class="flex">
                        <p class="font-bold mt-3">
                          Business Unit
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
    
                    </div>

                    <a-auto-complete v-model:value="selectedData.bname" :options="optionsBunit" style="width: 100%"
                        placeholder="input here" @search="handleSearchBunit" @select="handleSelectBunit" />

                    <div class="flex">
                        <p class="font-bold mt-3">
                            Contact Number
                        </p>
                    </div>
                    <a-input v-model:value="selectedData.ContactNo" placeholder="Contact Number">
                        <template #prefix>
                            <ContactsOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Contact Number">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Department
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>
                    <a-auto-complete v-model:value="selectedData.department" :options="optionsDepartment"
                        style="width: 100%" placeholder="input here" @search="handleSearchDepartment"
                        @select="handleSelectDepartment" />
                    <div class="flex">
                        <p class="font-bold mt-3">
                            Company
                        </p>
                        <p class="mt-2" style="color:red">
                            *search
                        </p>
                    </div>

                    <a-auto-complete v-model:value="selectedData.company" v-model="searchCompany" :options="optionsCompany"
                        style="width: 100%" placeholder="input here" @search="handleSearchCompany" @select="handleSelect" />


                    <div class="flex">
                        <p class="font-bold mt-3">
                            Usertype
                        </p>
                    </div>
                    <a-select ref="select" v-model:value="selectedData.usertype_id" style="width: 100%" @focus="focus"
                        @change="handleChange">
                        <a-select-option v-for="usert in userType" v-model:value="usert.usertype_id">{{ usert.usertype_name
                        }}</a-select-option>
                    </a-select>

                    <div class="flex">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-0 mt-4 ml-auto"
                            type="submit">
                            Submit
                        </button>
                    </div>
                </form>
            </a-modal>
        </div>
    </AdminLayout>
</template>

<script>

import {
    EditOutlined,
    TagOutlined,
    QuestionCircleOutlined,
    UserOutlined,
    InfoCircleOutlined,
    FileExcelOutlined,
    ContactsOutlined,
    ExclamationCircleOutlined

} from '@ant-design/icons-vue';
import axios from 'axios';
import { Modal } from 'ant-design-vue';

export default {
    data() {
        return {
            openModal: false,
            openModalCreate: false,
            optionsUser: [],
            optionsCompany: [],
            optionsDepartment: [],
            optionsBunit: [],
            password: '',

            selectedData: {
                name: '',
                username: '',
                businessunit_id: '',
                company_id: '',
                usertype_id: '',
                department_id: '',
                ContactNo: ''
            },
            createUsers: {
                empid: '',
                name: '',
                username: '',
                businessunit_id: '',
                company_id: '',
                usertype_id: '',
                department_id: '',
                ContactNo: '',
                password: '',
                password_confirmation: '',
            },

            columns: [
                {
                    title: 'Name',
                    dataIndex: 'name',
                    key: 'name',
                },
                {
                    title: 'Username',
                    dataIndex: 'username',
                    key: 'username',
                },
                {
                    title: 'Contact No.',
                    dataIndex: 'ContactNo',
                    key: 'contac_no',
                },
                {
                    title: 'Company',
                    dataIndex: 'acroname',
                    key: 'acroname',
                },
                {
                    title: 'Business Unit',
                    dataIndex: 'bname',
                    key: 'bname',
                },
                {
                    title: 'Department',
                    dataIndex: 'department',
                    key: 'department',
                },
                {
                    title: 'Usertype',
                    dataIndex: 'usertype_name',
                    key: 'usertype_name',
                    align: 'center'
                },
                {
                    title: 'Status',
                    dataIndex: 'user_status',
                    key: 'user_status',
                    align: 'center'
                },
                {
                    title: 'Action',
                    key: 'operation',
                    fixed: 'right',
                    width: 100

                },

            ],
        };
    },
    props: {
        get_users: Array,
        userType: Array,
    },
    methods: {
        edituserModal(data) {
            this.openModal = true;
            this.selectedData = data;
        },
        createuserModal() {
            this.openModalCreate = true;
        },
        handleChange(value) {
            console.log(`selected ${value}`);
        },
        createFormSubmit() {
            axios.post('/create/user/', this.createUsers)
                .then(response => {
                    this.openModalCreate = false;

                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        editFormSubmit() {
            axios.post(`/update_user/${this.selectedData.id}`, this.selectedData)
                .then(response => {
                    this.openModal = false;

                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                });
        },


        async handleSearchUsers(query) {
            try {
                const response = await axios.get(`/autoc_users/search?query=${query}`);
                const data = response.data;

                this.optionsUser = data.map((item) => ({
                    value: item.name,
                    value1: item.emp_no,
                    label: item.name,

                }));
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        filterMethod(value, option) {
            return option.label.toLowerCase().indexOf(value.toLowerCase()) !== -1;
        },
        handleSelectUser(value, option) {
            this.selectedData.name = value;
            this.selectedData.name = option.label;

        },
        handleSelectUser1(value, option) {
            this.createUsers.name = value;
            this.createUsers.empid = option.value1;
            this.createUsers.name = option.label;

            // console.log(this.empId)

        },

        async handleSearchCompany(query) {
            try {
                const response = await axios.get(`/autoc_company/search?query=${query}`);
                const data = response.data;

                this.optionsCompany = data.map((item) => ({
                    value: item.company,
                    value1: item.company_id,
                    label: item.company,
                }));
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        handleSelect(value, option) {
            this.selectedData.company_id = option.value1;
            this.selectedData.company = option.label;
        },
        handleSelect1(value, option) {
            this.createUsers.company_id = option.value1;
            this.createUsers.company = option.label;
        },


        async handleSearchBunit(query) {
            try {
                const response = await axios.get(`/autoc_bunit/search?query=${query}`);
                const data = response.data;

                this.optionsBunit = data.map((item) => ({
                    value: item.bname,
                    value1: item.businessunit_id,
                    label: item.bname,
                }));

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        handleSelectBunit(value, option) {
            this.selectedData.businessunit_id = option.value1;
            this.selectedData.bname = option.label;
        },
        handleSelectBunit1(value, option) {
            this.createUsers.businessunit_id = option.value1;
        },
        filterMethod(value, option) {
            return option.label.toLowerCase().indexOf(value.toLowerCase()) !== -1;
        },
        async handleSearchDepartment(query) {
            try {
                const response = await axios.get(`/autoc_department/search?query=${query}`);
                const data = response.data;

                this.optionsDepartment = data.map((item) => ({
                    value: item.department,
                    value1: item.department_id,
                    label: item.department,
                }));
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        handleSelectDepartment(value, option) {
            this.selectedData.department_id = option.value1;
            this.selectedData.department = option.label;
        },
        handleSelectDepartment1(value, option) {
            this.createUsers.department_id = option.value1;
            this.createUsers.department = option.label;
        },

        generateButtonExcel() {
            window.location.href = '/export-excel/users';
        },
        resignReactive(data) {
            if (data.user_status === 'active') {
                // Show confirmation modal
                Modal.confirm({
                    title: 'Confirmation',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: data.name + ': Tag this user as Resigned? ',
                    okText: 'Confirm',
                    cancelText: 'Cancel',
                    onOk() {
                        // If the user clicks "Confirm," make the Axios request
                        axios.post(`/resign-reactive/${data.id}`)
                            .then(response => {
                                // Reload the page after successful request
                                window.location.reload();
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    },
                    onCancel() {
                        // If the user clicks "Cancel," do nothing or provide additional actions
                    },
                });
            } else {
                Modal.confirm({
                    title: 'Confirmation',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: 'Reactive this user?',
                    okText: 'Confirm',
                    cancelText: 'Cancel',
                    onOk() {
                        // If the user clicks "Confirm," make the Axios request
                        axios.post(`/resign-reactive/${data.id}`)
                            .then(response => {
                                // Reload the page after successful request
                                window.location.reload();
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    },
                    onCancel() {
                        // If the user clicks "Cancel," do nothing or provide additional actions
                    },
                });
            }
        }
    }
};
</script>
<style></style>

