<template>
    <SuccessAlert v-if="isSuccess" :message="'Created SuccessFully'" :desc="'User is created successfully'" />
    <ErrorAlert v-if="isError" :message="'Something Went Wrong!'"
        :desc="'Please Check the fields if there is blank!'" />
    <a-card>
        <form>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 px-4 mb-4">
                    <p class="mt-3">Employee Name</p>
                    <a-select show-search placeholder="Search Employee name" :default-active-first-option="false"
                        v-model:value="createUsers.name" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null
                            " :options="optionsEmployee" @search="debouncedSearchEmployee" @select="(_, val) =>
                            (createUsers.empid =
                                val.value1)
                                ">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="createUsers.errors.name" class="text-red-600 ml-2" style="font-size: 12px">
                        *{{ createUsers.errors.name }}
                    </div>

                    <p class="mt-3">Business Unit Name</p>
                    <a-select show-search placeholder="Search business unit" :default-active-first-option="false"
                        v-model:value="createUsers.businessunit_id
                            " style="width: 100%" :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
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
                    <a-select show-search placeholder="Search Department" :default-active-first-option="false"
                        v-model:value="createUsers.department_id
                            " style="width: 100%" :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsDepartment" @search="debouncedDepartment">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="createUsers.errors.businessunit_id" class="text-red-600 ml-2" style="font-size: 12px">
                        *{{ createUsers.errors.businessunit_id }}
                    </div>

                    <p class="mt-3">Company</p>
                    <a-select show-search placeholder="Search Department" :default-active-first-option="false"
                        v-model:value="createUsers.company_id
                            " style="width: 100%" :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsCompany" @search="debouncedCompany">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="createUsers.errors.company_id" class="text-red-600 ml-2" style="font-size: 12px">
                        *{{ createUsers.errors.company_id }}
                    </div>

                    <p class="mt-3">Select Usertype</p>
                    <a-select placeholder="Select user type" ref="select" v-model:value="createUsers.usertype_id
                        " style="width: 100%" class="" @change="handleChange">
                        <a-select-option v-for="usert in usertype" v-model:value="usert.usertype_id
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
                <div class="w-full md:w-1/2 px-4 mb-4">
                    <p class="mt-3">Username</p>
                    <a-input v-model:value="createUsers.username" placeholder="Username">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0,0,0,0.45);" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div v-if="createUsers.errors.username" class="text-red-600 ml-2" style="font-size: 12px">
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
                                <InfoCircleOutlined style="color: rgba(0,0,0,0.45);" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div v-if="createUsers.errors.ContactNo" class="text-red-600 ml-2" style="font-size: 12px">
                        *{{ createUsers.errors.ContactNo }}
                    </div>

                    <a-input v-model:value="createUsers.empid" class="hidden">
                        <template #prefix>
                            <UserOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Username">
                                <InfoCircleOutlined style="color: rgba(0, 0,0,0.45);" />
                            </a-tooltip>
                        </template>
                    </a-input>

                    <p class="mt-6"></p>
                    <a-button block type="primary" :loading="createUsers.processing" @click="saveUsers">
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        {{
                            createUsers.processing
                                ? "Saving Users Credentials..."
                                : "Continue Saving User"
                        }}
                    </a-button>
                </div>
            </div>
        </form>
    </a-card>
</template>


<script>
import { useForm } from '@inertiajs/vue3';
import { searchFunctionMixin } from '@/Mixin/FilterQuery';

export default {
    props: {
        usertype: Object,
    },
    setup() {
        return searchFunctionMixin();
    },
    data() {
        return {
            isSuccess: false,
            isError: false,
            createUsers: useForm({
                name: null,
                empid: null,
                username: "",
                businessunit_id: null,
                company_id: null,
                usertype_id: null,
                department_id: null,
                ContactNo: "",
                password: "",
                password_confirmation: "",
            }),
        }
    },
    methods: {
        saveUsers() {
            this.createUsers.post(route("users.store"), {
                onSuccess: () => {
                    this.createUsers.reset();
                    message.success('Added user sucessfully')
                    this.isSuccess = true;
                },
                onError: (e) => {
                    this.isError = true;
                }
            });
        },
    }
}
</script>
