<template>

    <Head title="My Setting"></Head>
    <div class="flex justify-center py-10">
        <div class="max-w-xs text-center">
            <img class="mx-auto mb-4 h-56 w-56 rounded-full outline outline-2 outline-white outline-offset-2"
                :src="`/storage/users-image/${$page.props.auth.user.id}`" alt="profile picture" />
            <h3>{{ $page.props.auth.user.name }} </h3>
            <p class="font-bold text-gray-600 mt-2"> -- {{ $page.props.auth.user.username }} --</p>

            <div class="flex mt-5 mb-4">
                <a-button block @click="editUsername" class="mr-1">
                    <user-outlined /> Edit Username
                </a-button>
                <a-button block @click="editPass">
                    <UnlockOutlined /> Change Password
                </a-button>
            </div>
            <a-card class="mt-5 mb-3" v-if="isEditUsername"
                style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;">
                <p class="font-bold text-start ml-2">
                    <user-outlined /> Username
                </p>
                <a-input v-model:value="editUser.username" placeholder="Username" class="mb-3" />

                <a-button type="primary" ghost @click="updateUsername" :loading="editUser.processing">
                    <template #icon>
                        <SaveOutlined />
                    </template>{{ editUser.processing ? 'Updating username...' : 'Update Username' }}
                </a-button>
            </a-card>

            <a-card class="mt-5 mb-3" v-if="isEditPass" style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;">
                <p class="font-bold text-start ml-2">
                    <UnlockOutlined /> Password
                </p>
                <a-input-password v-model:value="editPassword.password" placeholder="input password" class="mb-3" />

                <a-button type="primary" ghost @click="udpatePassword">
                    <template #icon>
                        <SaveOutlined />
                    </template>{{ editPassword.processing ? "Updating Password..." : "Update Password" }}
                </a-button>
            </a-card>
        </div>

    </div>
</template>

<script>
import { message } from 'ant-design-vue';
import { useForm } from "@inertiajs/vue3";
import AccountingLayout from "@/Layouts/AccountingLayout.vue";
export default {
    layout: AccountingLayout,
    data() {
        return {
            isEditUsername: false,
            isEditPass: false,

            editUser: useForm({
                username: null
            }),

            editPassword: useForm({
                password: ''
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
            this.editPassword.put(route('new.password'),
                {
                    onSuccess: () => {
                        this.isEditPass = false;
                        this.editPassword.reset();
                    }
                })
        }
    }
}
</script>
