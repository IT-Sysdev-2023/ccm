<template>
    <Head title="My Setting"></Head>
        <a-row :gutter="[16, 16]">
            <a-col :span="8" style="margin: 0 auto;">
                <a-card class="flex justify-center items-center" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
              width: 100% ; height: 70vh">
                    <div class="max-w-xs text-center">
                        <img class="mx-auto mb-4 h-56 w-56 rounded-full outline outline-2 outline-white outline-offset-2"
                            :src="'http://172.16.161.34:8080/hrms' + $page.props.auth.user.employee3.applicant.photo"
                            alt="profile picture" />
                        <h4 class="text-xl font-semibold text-black">{{ $page.props.auth.user.name }}</h4>
                        <h3 class="font-semibold text-slate-600">{{ $page.props.auth.user.username }}
                            <span v-if="!isEditUsername">
                                <FormOutlined @click="editUsername" />
                            </span>
                            <span v-else>
                                <CloseOutlined @click="editUsername" />
                            </span>
                        </h3>
                        <a-card class="mt-5 mb-3" v-if="isEditUsername"
                            style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;">
                            <p class="font-bold text-start ml-2">
                                <user-outlined /> Username
                            </p>
                            <a-input v-model:value="editUser.username" placeholder="Username" class="mb-3" />

                            <a-button type="primary" ghost @click="updateUsername" :loading="editUser.processing">
                                <template #icon>
                                    <SaveOutlined />
                                </template>{{ editUser.processing ? 'Updating...' : 'Update' }}
                            </a-button>
                        </a-card>
                        <span class="inline-block text-start text-slate-500">
                            <p class="underline" @click="editPass" style="cursor: pointer">Change password?</p>
                        </span>

                        <a-card class="mt-5 mb-3" v-if="isEditPass"
                            style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;">
                            <p class="font-bold text-start ml-2">
                                <user-outlined /> Password
                            </p>
                            <a-input-password v-model:value="editPassword.password" placeholder="input password"
                                class="mb-3" />

                            <a-button type="primary" ghost @click="udpatePassword">
                                <template #icon>
                                    <SaveOutlined />
                                </template> Change
                            </a-button>
                        </a-card>
                    </div>
                </a-card>
            </a-col>
            <a-col :span="14" style="margin: 0 auto;">

            </a-col>
        </a-row>
</template>

<script>
import { message } from 'ant-design-vue';
import { useForm } from "@inertiajs/vue3";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
export default {
    layout: TreasuryLayout,
    data() {
        return {
            isEditUsername: false,
            isEditPass: false,

            editUser: useForm({
                username: null
            }),

            editPassword: useForm({
                password: null
            }),
        }
    },

    methods: {
        editUsername() {
            this.isEditUsername = !this.isEditUsername;
            this.isEditPass = false;
            this.editUser.username = this.$page.props.auth.user.username
        },
        editPass() {
            this.isEditPass = !this.isEditPass;
            this.isEditUsername = false;
        },
        updateUsername() {
            this.editUser.put(route('username.edit'), {
                onSuccess: () => {
                    this.editUser.reset();
                    this.isEditUsername = false;
                    message.success('Username Updated successfully')
                }
            });
        },
        udpatePassword() {

        }
    }
}
</script>
