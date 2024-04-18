<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { ref, createVNode } from "vue";
const activeKey = ref("1");

const open = ref(false);
const ref1 = ref(null);
const ref2 = ref(null);
const ref3 = ref(null);
const ref4 = ref(null);
const ref5 = ref(null);
const current = ref(0);
const steps = [
    {
        title: "The title of this page",
        description:
            "This will be the guides or list of table of all the users that are active and existed",
        target: () => ref1.value && ref1.value.$el,
    },
    {
        title: "Generate Excel",
        description:
            "This button will generate an excel file, all the users will be generated",
        target: () => ref2.value && ref2.value.$el,
    },
    {
        title: "Tagging a user",
        description: "Tag a user if you want to tag it as resign",
        target: () => ref3.value && ref3.value.$el,
    },
    {
        title: "Edit a user",
        description: "This is the button to edit the users credentials..",
        target: () => ref4.value && ref4.value.$el,
    },
];
const handleOpen = (val) => {
    open.value = val;
};
</script>

<template>

    <Head title="Usermaintinance" />

    <AdminLayout>
        <template #header> </template>
        <a-float-button v-on:click="handleOpen(true)" :style="{
            right: '24px',
        }">
            <template #icon>
                <QuestionCircleOutlined />
            </template>
        </a-float-button>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb>
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">User</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Table</a-breadcrumb-item>
                </a-breadcrumb>
                <div class="">
                    <!-- <p>{{ get_users.data }}</p> -->
                    <a-tabs v-model:activeKey="activeKey" type="card" class="mt-4">
                        <a-tab-pane key="1" tab="Users List">
                            <a-card>
                                <a-row :gutter="[16, 16]" class="mt-4 flex justify-center">
                                    <a-col :span="5" v-for="(item, key) in get_users.data" :key="key">
                                        <div class="body" @click="settDetails(item.id)">
                                            <a class="card human-resources" href="#">
                                                <span style="
                                                    margin-left: 100px;
                                                    position: relative;
                                                    top: 30px;
                                                    z-index: 99;
                                                ">
                                                    <img style="height: 30px" src="svg/onlinestatus.svg" alt="" />
                                                </span>
                                                <div class="overlay"></div>
                                                <div class="circle">
                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                        viewBox="1855 26 66 77" height="77px" width="66px">
                                                        <desc>
                                                            Created with Sketch.
                                                        </desc>
                                                        <defs></defs>
                                                        <g transform="translate(1855.000000, 26.000000)"
                                                            fill-rule="evenodd" fill="none" stroke-width="1"
                                                            stroke="none" id="Page-1">
                                                            <path fill="#AFCEFF" id="Fill-8"
                                                                d="M4.28872448,42.7464904 C4.28872448,39.3309774 5.4159227,33.7621426 6.40576697,30.4912557 C10.5920767,32.1098991 14.3021264,35.1207513 18.69596,35.1207513 C30.993618,35.1207513 42.5761396,28.7162991 49.9992251,17.9014817 C56.8027248,23.8881252 60.8188351,33.0463165 60.8188351,42.7464904 C60.8188351,60.817447 47.6104607,76.6693426 32.5537798,76.6693426 C17.4970989,76.6693426 4.28872448,60.817447 4.28872448,42.7464904">
                                                            </path>
                                                            <path fill="#3B6CB7" id="Fill-10"
                                                                d="M64.3368879,31.1832696 L62.8424171,46.6027478 L60.6432609,46.7824348 L59.8340669,34.6791304 L47.6573402,25.3339478 C44.2906753,34.068487 34.3459503,40.2903304 24.4684093,40.2903304 C17.7559812,40.2903304 10.046244,37.4168 5.80469412,32.8004522 L5.80469412,34.6791304 L5.80469412,46.6027478 L4.28932167,46.6027478 L1.30187314,27.8802435 C1.30187314,20.9790957 3.52342407,15.5432 7.27229127,11.3578087 C13.132229,4.79558261 21.8124018,0.0492173913 30.5672235,0.342852174 C37.4603019,0.569286957 42.6678084,2.72991304 50.8299179,0.342852174 C51.4629405,1.44434783 51.8615656,3.00455652 51.5868577,5.22507826 C51.4629405,6.88316522 51.2106273,7.52302609 50.8299179,8.45067826 C58.685967,14.1977391 64.3368879,20.7073739 64.3368879,31.1832696">
                                                            </path>
                                                            <path fill="#568ADC" id="Fill-13"
                                                                d="M58.9405197,54.5582052 C62.0742801,54.8270052 65.3603242,52.60064 65.6350321,49.5386574 C65.772386,48.009127 65.2617876,46.5570226 64.3182257,45.4584487 C63.3761567,44.3613357 62.0205329,43.6162922 60.4529062,43.4818922 L58.9405197,54.5582052 Z">
                                                            </path>
                                                            <path fill="#568ADC" id="Fill-15"
                                                                d="M6.32350389,54.675367 C3.18227865,54.8492104 0.484467804,52.4957496 0.306803449,49.4264626 C0.217224782,47.8925496 0.775598471,46.4579757 1.75200594,45.3886191 C2.7284134,44.3192626 4.10792487,43.6165843 5.67853749,43.530393 L6.32350389,54.675367 Z">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <p>{{ item.username }}</p>
                                                <span>{{ item.name }}</span>
                                                <div class="font-bold text-black" :class="item.user_status == 'active'
                                                        ? 'text-green-600'
                                                        : 'text-red-600'
                                                    ">
                                                    {{ item.user_status }}
                                                </div>
                                            </a>
                                        </div>
                                        <div class="flex justify-between mt-1">
                                            <a-popconfirm :title="item.user_status == 'active'
                                                    ? 'tag user as resign?'
                                                    : 're-active this user?'
                                                " :footer="null" ok-text="Yes" cancel-text="No"
                                                @confirm="resignReactive(item)">
                                                <a-button block>
                                                    <template #icon>
                                                        <TagsOutlined />
                                                    </template>
                                                    tag
                                                </a-button>
                                            </a-popconfirm>
                                            <a-button block @click="edituserModal(item)">
                                                <template #icon>
                                                    <FormOutlined />
                                                </template>
                                                edit
                                            </a-button>
                                        </div>
                                    </a-col>
                                </a-row>
                                <div>
                                    <pagination class="mt-10" :datarecords="get_users" />
                                </div>
                            </a-card>
                        </a-tab-pane>
                        <a-tab-pane key="2" tab="Add Users">
                            <a-card style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                                <form>
                                    <div class="flex flex-wrap -mx-4">
                                        <!-- First Column -->
                                        <div class="w-full md:w-1/2 px-4 mb-4">
                                            <p class="mt-3">Employee Name</p>
                                            <a-select show-search placeholder="Search Employee name"
                                                :default-active-first-option="false" v-model:value="createUsers.name"
                                                style="width: 100%" :show-arrow="false" :filter-option="false"
                                                :not-found-content="isRetrieving ? undefined : null
                                                    " :options="optionsUser" @search="debouncedSearchUsers" @select="(_, val) =>
                                                (createUsers.empid =
                                                    val.value1)
                                                ">
                                                <template v-if="isRetrieving" #notFoundContent>
                                                    <a-spin size="small" />
                                                </template>
                                            </a-select>
                                            <div v-if="createUsers.errors.name" class="text-red-600 ml-2"
                                                style="font-size: 12px">
                                                *{{ createUsers.errors.name }}
                                            </div>

                                            <p class="mt-3">Business Unit Name</p>
                                            <a-select show-search placeholder="Search business unit"
                                                :default-active-first-option="false" v-model:value="createUsers.businessunit_id
                                                    " style="width: 100%" :show-arrow="false" :filter-option="false"
                                                :not-found-content="isRetrieving ? undefined : null
                                                    " :options="optionsBunit" @search="debouncedBunitName">
                                                <template v-if="isRetrieving" #notFoundContent>
                                                    <a-spin size="small" />
                                                </template>
                                            </a-select>
                                            <div v-if="
                                                createUsers.errors
                                                    .businessunit_id
                                            " class="text-red-600 ml-2" style="font-size: 12px">
                                                *{{
                                                    createUsers.errors
                                                        .businessunit_id
                                                }}
                                            </div>

                                            <p class="mt-3">Department</p>
                                            <a-select show-search placeholder="Search Department"
                                                :default-active-first-option="false" v-model:value="createUsers.department_id
                                                    " style="width: 100%" :show-arrow="false" :filter-option="false"
                                                :not-found-content="isRetrieving ? undefined : null
                                                    " :options="optionsDepartment" @search="debouncedDepartment">
                                                <template v-if="isRetrieving" #notFoundContent>
                                                    <a-spin size="small" />
                                                </template>
                                            </a-select>
                                            <div v-if="
                                                createUsers.errors.department_id
                                            " class="text-red-600 ml-2" style="font-size: 12px">
                                                *{{
                                                    createUsers.errors.department_id
                                                }}
                                            </div>

                                            <p class="mt-3">Company</p>
                                            <a-select show-search placeholder="Search Department"
                                                :default-active-first-option="false" v-model:value="createUsers.company_id
                                                    " style="width: 100%" :show-arrow="false" :filter-option="false"
                                                :not-found-content="isRetrieving ? undefined : null
                                                    " :options="optionsCompany" @search="debouncedCompany">
                                                <template v-if="isRetrieving" #notFoundContent>
                                                    <a-spin size="small" />
                                                </template>
                                            </a-select>
                                            <div v-if="createUsers.errors.company_id" class="text-red-600 ml-2"
                                                style="font-size: 12px">
                                                *{{ createUsers.errors.company_id }}
                                            </div>

                                            <p class="mt-3">Select Usertype</p>
                                            <a-select placeholder="Select user type" ref="select" v-model:value="createUsers.usertype_id
                                                " style="width: 100%" class="" @change="handleChange">
                                                <!-- <a-select-option>Select UserType</a-select-option> -->
                                                <a-select-option v-for="usert in userType" v-model:value="usert.usertype_id
                                                    ">{{
                                                    usert.usertype_name
                                                    }}</a-select-option>
                                            </a-select>
                                            <div v-if="
                                                createUsers.errors.usertype_id
                                            " class="text-red-600 ml-2" style="font-size: 12px">
                                                *{{
                                                    createUsers.errors.usertype_id
                                                }}
                                            </div>
                                        </div>

                                        <!-- Second Column -->
                                        <div class="w-full md:w-1/2 px-4 mb-4">
                                            <p class="mt-3">Username</p>
                                            <a-input v-model:value="createUsers.username" placeholder="Username">
                                                <template #prefix>
                                                    <UserOutlined />
                                                </template>
                                                <template #suffix>
                                                    <a-tooltip title="Username">
                                                        <InfoCircleOutlined style="
                                                            color: rgba(
                                                                0,
                                                                0,
                                                                0,
                                                                0.45
                                                            );
                                                        " />
                                                    </a-tooltip>
                                                </template>
                                            </a-input>
                                            <div v-if="createUsers.errors.username" class="text-red-600 ml-2"
                                                style="font-size: 12px">
                                                *{{ createUsers.errors.username }}
                                            </div>

                                            <p class="mt-3">Conact number</p>
                                            <a-input v-model:value="createUsers.ContactNo
                                                " placeholder="Contact Number">
                                                <template #prefix>
                                                    <ContactsOutlined />
                                                </template>
                                                <template #suffix>
                                                    <a-tooltip title="Contact Number">
                                                        <InfoCircleOutlined style="
                                                            color: rgba(
                                                                0,
                                                                0,
                                                                0,
                                                                0.45
                                                            );
                                                        " />
                                                    </a-tooltip>
                                                </template>
                                            </a-input>
                                            <div v-if="createUsers.errors.ContactNo" class="text-red-600 ml-2"
                                                style="font-size: 12px">
                                                *{{ createUsers.errors.ContactNo }}
                                            </div>

                                            <a-input v-model:value="createUsers.empid" class="hidden">
                                                <template #prefix>
                                                    <UserOutlined />
                                                </template>
                                                <template #suffix>
                                                    <a-tooltip title="Username">
                                                        <InfoCircleOutlined style="
                                                            color: rgba(
                                                                0,
                                                                0,
                                                                0,
                                                                0.45
                                                            );
                                                        " />
                                                    </a-tooltip>
                                                </template>
                                            </a-input>

                                            <p class="mt-6"></p>
                                            <a-button block type="primary" :loading="createUsers.processing"
                                                @click="saveUsers">
                                                <template #icon>
                                                    <SaveOutlined />
                                                </template>
                                                {{
                                                    createUsers.processing
                                                        ? "saving users please wait..."
                                                        : "continue saving user"
                                                }}
                                            </a-button>
                                        </div>

                                        <!-- Repeat the above pattern for the rest of your form fields -->
                                    </div>
                                </form>
                            </a-card>
                        </a-tab-pane>
                        <a-tab-pane key="3" tab="Generate">
                          <a-card>
                            <div class="flex justify-center">
                                <a-result title="Hi there! Don't have a good day, Have a great day"
                                    sub-title="If you cannot do great things, do small things in a great way">
                                    <template #icon>
                                        <SmileOutlined />
                                    </template>
                                    <template #extra>
                                        <a-button type="primary" :loading="isloading" v-on:click="generateButtonExcel">
                                            click here to generate data in the
                                            table
                                        </a-button>
                                    </template>
                                </a-result>
                            </div>
                          </a-card>
                        </a-tab-pane>
                    </a-tabs>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"></div>
            </div>
            <a-tour v-model:current="current" :open="open" :steps="steps" @close="handleOpen(false)">
                <template #indicatorsRender="{ current, total }">
                    <span>{{ current + 1 }} / {{ total }}</span>
                </template>
                <template #buttonRender>
                    <a-button type="primary" size="large">My Custom Button</a-button>
                </template>
            </a-tour>

            <a-modal v-model:open="openModal" width="1000px" title="Edit User" :footer="null">
                <a-form>
                    <div class="flex">
                        <p class="mt-3">Employee Name</p>
                        <p class="mt-2" style="color: red">*search</p>
                    </div>
                    <a-select show-search placeholder="Search Employee name" :default-active-first-option="false"
                        v-model:value="selectedData.name" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="optionsUser"
                        @search="debouncedSearchUsers"
                        @select="(_, val) => (createUsers.empid = val.value1)"></a-select>
                    <div class="flex">
                        <p class="mt-3">Username</p>
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
                        <p class="mt-3">Business Unit</p>
                        <p class="mt-2" style="color: red">*search</p>
                    </div>

                    <a-select show-search placeholder="Search business unit" :default-active-first-option="false"
                        v-model:value="selectedData.bname" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="optionsBunit" @search="debouncedBunitName" @select="(_, val) =>
                                (selectedData.businessunit_id = val.value)
                            ">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <a-input class="hidden" :value="selectedData.businessunit_id">
                    </a-input>

                    <div class="flex">
                        <p class="mt-3">Contact Number</p>
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
                        <p class="mt-3">Department</p>
                        <p class="mt-2" style="color: red">*search</p>
                    </div>
                    <a-select show-search placeholder="Search Department" :default-active-first-option="false"
                        v-model:value="selectedData.department" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="optionsDepartment" @search="debouncedDepartment" @select="(_, val) => (selectedData.department_id = val.value)
                            ">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <a-input class="hidden" :value="selectedData.department_id">
                    </a-input>
                    <div class="flex">
                        <p class="mt-3">Company</p>
                        <p class="mt-2" style="color: red">*search</p>
                    </div>

                    <a-select show-search placeholder="Search Department" :default-active-first-option="false"
                        v-model:value="selectedData.company" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="optionsCompany" @search="debouncedCompany" @select="(_, val) => (selectedData.company_id = val.value)
                            ">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <a-input :value="selectedData.company_id" class="hidden">
                    </a-input>
                    <div class="flex">
                        <p class="mt-3">Usertype</p>
                    </div>
                    <a-select ref="select" v-model:value="selectedData.usertype_id" style="width: 100%"
                        @change="handleChange">
                        <a-select-option v-for="usert in userType" v-model:value="usert.usertype_id">{{
                            usert.usertype_name
                            }}</a-select-option>
                    </a-select>

                    <div class="flex mt-10" style="display: flex; justify-content: center">
                        <a-button class="mb-5" block type="primary" :loading="selectedData.processing"
                            @click="updateUsers">
                            <template #icon>
                                <SaveFilled />
                            </template>
                            {{
                                selectedData.processing
                                    ? "updating credentials please wait..."
                                    : "update users credentials?"
                            }}
                        </a-button>
                    </div>
                </a-form>
            </a-modal>
        </div>
    </AdminLayout>
</template>

<script>
import axios from "axios";
import { Modal } from "ant-design-vue";
// import _ from 'lodash';
import debounce from "lodash/debounce";
import { message } from "ant-design-vue";
import { useForm } from "@inertiajs/vue3";
import { SaveOutlined } from "@ant-design/icons-vue";

export default {
    data() {
        return {
            openModal: false,
            openModalCreate: false,
            optionsUser: [],
            optionsCompany: [],
            optionsDepartment: [],
            optionsBunit: [],
            password: "",
            isloading: false,
            data0: [],
            allData: [],
            isRetrieving: false,

            selectedData: useForm({
                name: "",
                username: "",
                businessunit_id: "",
                company_id: "",
                usertype_id: "",
                department_id: "",
                ContactNo: "",
                userId: null,
            }),

            createUsers: useForm({
                empid: "",
                name: null,
                username: "",
                businessunit_id: null,
                company_id: null,
                usertype_id: null,
                department_id: null,
                ContactNo: "",
                password: "",
                password_confirmation: "",
            }),
        };
    },
    props: {
        get_users: Object,
        userType: Object,
    },
    methods: {
        edituserModal(data) {
            this.openModal = true;
            this.selectedData = useForm(data);
            // this.selectedData.userId = useForm(data.id);
        },
        createuserModal() {
            this.openModalCreate = true;
        },
        saveUsers() {
            // window.alert('helklo world');
            this.createUsers.post(route("users.store"), {});
        },
        updateUsers() {
            this.selectedData
                .transform((data) => ({
                    name: data.name,
                    username: data.username,
                    businessunit_id: data.businessunit_id,
                    company_id: data.company_id,
                    usertype_id: data.usertype_id,
                    department_id: data.department_id,
                    ContactNo: data.ContactNo,
                    userId: data.id,
                }))
                .post(route("users.update"), {});
        },
        settDetails(id) {
            window.location.href = "/user/details/" + id;
        },

        debouncedSearchUsers: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.optionsUser = [];

                    const { data } = await axios.get(
                        route("search.employeeName", { search: query })
                    );

                    this.allData = data;

                    this.optionsUser = this.allData.map((userOption) => ({
                        title: userOption.name,
                        value: userOption.name,
                        label: userOption.name,
                        value1: userOption.emp_no,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),

        debouncedBunitName: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.optionsBunit = [];

                    const { data } = await axios.get(
                        route("search.bunit", { search: query })
                    );

                    this.allData = data;

                    this.optionsBunit = this.allData.map((bunitOption) => ({
                        title: bunitOption.bname,
                        value: bunitOption.businessunit_id,
                        label: bunitOption.bname,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000), // Adjust the debounce time (in milliseconds) as needed
        debouncedDepartment: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.optionsDepartment = [];

                    const { data } = await axios.get(
                        route("search.checkfrom", { search: query })
                    );

                    this.allData = data;

                    this.optionsDepartment = this.allData.map(
                        (departOption) => ({
                            title: departOption.department,
                            value: departOption.department_id,
                            label: departOption.department,
                        })
                    );
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000), // Adjust the debounce time (in milliseconds) as needed
        debouncedCompany: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.optionsCompany = [];

                    const { data } = await axios.get(
                        route("search.company", { search: query })
                    );

                    this.allData = data;

                    this.optionsCompany = this.allData.map((companyOption) => ({
                        title: companyOption.company,
                        value: companyOption.company_id,
                        label: companyOption.company,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000), // Adjust the debounce time (in milliseconds) as needed
        filterMethod(value, option) {
            return (
                option.label.toLowerCase().indexOf(value.toLowerCase()) !== -1
            );
        },

        generateButtonExcel() {
            this.isloading = true;
            setTimeout(() => {
                try {
                    window.location.href = "/export-excel/users";
                } catch {
                } finally {
                    this.isloading = false;
                }
            }, 2500);
        },
        resignReactive(data) {
            this.$inertia.post(
                route("users.resrec"),
                {
                    userId: data.id,
                },
                {
                    onSuccess: () => {
                        if (data.user_status == "active") {
                            message.success("Successfully tag as resign");
                        } else {
                            message.success("Successfully re-active user");
                        }
                    },
                }
            );
        },
    },
};
</script>
<style scoped>
.human-resources {
    --bg-color: #dce9ff;
    --bg-color-light: #f1f7ff;
    --text-color-hover: #4c5656;
    --box-shadow-color: rgba(220, 233, 255, 0.48);
}

.card {
    width: 100%;
    height: 310px;
    background: #fff;
    border-top-right-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    /* box-shadow: 0 14px 26px rgba(0, 0, 0, 0.04); */
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
        rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    transition: all 0.3s ease-out;
    text-decoration: none;
}

.card:hover {
    transform: translateY(-5px) scale(1.005) translateZ(0);
    box-shadow: 0 24px 36px rgba(0, 0, 0, 0.11),
        0 24px 46px var(--box-shadow-color);
}

.card:hover .overlay {
    transform: scale(4) translateZ(0);
}

.card:hover .circle {
    border-color: var(--bg-color-light);
    background: var(--bg-color);
}

.card:hover .circle:after {
    background: var(--bg-color-light);
}

.card:hover p {
    color: var(--text-color-hover);
}

.card:active {
    transform: scale(1) translateZ(0);
    box-shadow: 0 15px 24px rgba(0, 0, 0, 0.11),
        0 15px 24px var(--box-shadow-color);
}

.card p {
    font-size: 17px;
    color: #4c5656;
    margin-top: 30px;
    z-index: 1000;
    transition: color 0.3s ease-out;
}

.circle {
    width: 131px;
    height: 131px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid var(--bg-color);
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease-out;
}

.circle:after {
    content: "";
    width: 118px;
    height: 118px;
    display: block;
    position: absolute;
    background: var(--bg-color);
    border-radius: 50%;
    transition: opacity 0.3s ease-out;
}

.circle svg {
    z-index: 10000;
    transform: translateZ(0);
}

.overlay {
    width: 118px;
    position: absolute;
    height: 118px;
    border-radius: 50%;
    background: var(--bg-color);
    top: 70px;
    left: 50px;
    z-index: 0;
    transition: transform 0.3s ease-out;
}
</style>
