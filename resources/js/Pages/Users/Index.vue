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


    <a-float-button v-on:click="handleOpen(true)" :style="{
        right: '24px',
    }">
        <template #icon>
            <QuestionCircleOutlined />
        </template>
    </a-float-button>

    <div class="py-0">
        <a-breadcrumb>
            <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
            <a-breadcrumb-item><a href="">User</a></a-breadcrumb-item>
            <a-breadcrumb-item>Tables</a-breadcrumb-item>
        </a-breadcrumb>
        <div class="">
            <a-tabs v-model:activeKey="activeKey" type="card" class="mt-4">
                <a-tab-pane key="1">
                    <template #tab>
                        <span>
                            <UserOutlined />
                            All Userlist tables
                        </span>
                    </template>
                    <a-row :gutter="[16, 16]" class="mt-4 flex justify-between" style="width: 100%">
                        <a-col :span="6" v-for="(item, key) in get_users.data" :key="key">
                            <div class="body" @click="settDetails(item.id)">
                                <a class="card human-resources" href="#">
                                    <span style="
                                                    margin-left: 100px;
                                                    position: relative;
                                                    top: 30px;
                                                    z-index: 99;
                                                ">
                                    </span>
                                    <div class="overlay"></div>
                                    <div class="circle">
                                        <div class="svg">
                                            <img style="height: 115px; width: 115px; border-radius: 100%;"
                                                :src="'http://172.16.161.34:8080/hrms' + item.employee3.applicant.photo"
                                                alt="" />
                                        </div>
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

                </a-tab-pane>
                <a-tab-pane key="2">
                    <template #tab>
                        <span>
                            <UsergroupAddOutlined />
                            Add users account
                        </span>
                    </template>

                    <CreateUserForm  :usertype="userType"/>

                </a-tab-pane>
                <a-tab-pane key="3">
                    <template #tab>
                        <span>
                            <CloudUploadOutlined />
                            Generate Excel Data
                        </span>
                    </template>
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

    <a-modal v-model:open="openModal" width="1000px" style="top: 50px" title="Edit User" :footer="null">
        <a-form>
            <div class="flex">
                <p class="mt-3">Employee Name</p>
                <p class="mt-2" style="color: red">*search</p>
            </div>
            <a-select show-search placeholder="Search Employee name" :default-active-first-option="false"
                v-model:value="selectedData.name" style="width: 100%" :show-arrow="false" :filter-option="false"
                :not-found-content="isRetrieving ? undefined : null" :options="optionsUser"
                @search="debouncedSearchUsers" @select="(_, val) => (createUsers.empid = val.value1)"></a-select>
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
                v-model:value="selectedData.businessunit.bname" style="width: 100%" :show-arrow="false"
                :filter-option="false" :not-found-content="isRetrieving ? undefined : null" :options="optionsBunit"
                @search="debouncedBunitName" @select="(_, val) =>
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
                v-model:value="selectedData.departments.department" style="width: 100%" :show-arrow="false"
                :filter-option="false" :not-found-content="isRetrieving ? undefined : null" :options="optionsDepartment"
                @search="debouncedDepartment" @select="(_, val) => (selectedData.department_id = val.value)
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
                v-model:value="selectedData.company.company" style="width: 100%" :show-arrow="false"
                :filter-option="false" :not-found-content="isRetrieving ? undefined : null" :options="optionsCompany"
                @search="debouncedCompany" @select="(_, val) => (selectedData.company_id = val.value)
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
            <a-select ref="select" v-model:value="selectedData.usertype_id" style="width: 100%" @change="handleChange">
                <a-select-option v-for="usert in userType" v-model:value="usert.usertype_id">{{
                    usert.usertype_name
                }}</a-select-option>
            </a-select>

            <div class="flex mt-10" style="display: flex; justify-content: center">
                <a-button class="mb-5" block type="primary" :loading="selectedData.processing" @click="updateUsers">
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

</template>

<script>
import axios from "axios";
import debounce from "lodash/debounce";
import { message } from "ant-design-vue";
import { useForm } from "@inertiajs/vue3";
export default {
    layout: AdminLayout,
    data() {
        return {
            openModal: false,
            password: "",
            isloading: false,
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
        },
        createuserModal() {
            this.openModalCreate = true;
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
                .put(route("users.update"), {
                    onSuccess: () => {
                        this.selectedData.reset();
                        this.openModal = false;
                        message.success('Updated user sucessfully')
                    }
                });
        },
        settDetails(id) {
            window.location.href = "/users/details/" + id;
        },

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

.circle .svg {
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
