<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="h-full">
                    <Link>
                    <a-button>Click here to go back home</a-button>
                    </Link>
                    <div class="border-b-2 block md:flex">

                        <div class="w-full md:w-2/5 p-4 sm:p-6 lg:p-8 bg-white shadow-md">
                            <div class="flex justify-between">
                                <span class="text-xl block">{{ user.name }}</span>
                            </div>

                            <span class="text-gray-600">{{ user.username }}</span>
                            <div class="w-full p-8 mx-2 flex justify-center">
                                <img id="showImage" class="max w-full items-center border rounded"
                                    src="https://images.unsplash.com/photo-1477118476589-bff2c5c4cfbb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=200&q=200"
                                    alt="">
                            </div>
                        </div>

                        <div class="w-full md:w-3/5 p-8 bg-white lg:ml-4 shadow-md">
                            <table class="min-w-full bg-white border border-gray-300">
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">Company</td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.company }}</td>
                                    </tr>
                                    <tr>
                                        <td class=" py-2 px-4 border-b border-l border-t border-r">Business Unit
                                        </td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.bname }}</td>
                                    </tr>
                                    <tr>
                                        <td class=" py-2 px-4 border-b border-l border-t border-r">Department</td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.department }}</td>
                                    </tr>
                                    <tr>
                                        <td class=" py-2 px-4 border-b border-l border-t border-r">Contact</td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.ContactNo }}</td>
                                    </tr>
                                    <tr>
                                        <td class=" py-2 px-4 border-b border-l border-t border-r">Usertype</td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.usertype_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" py-2 px-4 border-b border-l border-t border-r">Status</td>
                                        <td class="py-2 px-4 border-b border-l border-t border-r">{{ user.user_status }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
export default {
    props: {
        user: '',
    }
}
</script>
