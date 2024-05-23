<template>

    <Head title="Checks Alta Report" />
    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <a-breadcrumb>
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
                <a-breadcrumb-item>Alta Cita Reports</a-breadcrumb-item>
            </a-breadcrumb>
            <div v-if="isGeneratingShow">
                <div class="flex justify-between mt-5">
                    <div>
                        <p> {{ progressBar.message }}{{ progressBar.currentRow
                            }}
                            to
                            {{
                                progressBar.totalRows }}</p>
                    </div>
                </div>
                <a-progress :stroke-color="{
                    from: '#108ee9', to: '#87d068',
                }" :percent="progressBar.percentage" status="active" />
            </div>

            <div class="flex justify-between">
                <a-date-picker :disabled="isFetching" v-model:value="dateMonth" @change="handleChangeMonth"
                    picker="month" style="width: 250px;" class="mt-5 mb-5" />
                <a-button class="mt-5 mb-5" type="primary" @click="startGenerating" :loading="isLoading"
                    :disabled="data.data?.length <= 0 || data.data === undefined">
                    <template #icon>
                        <CloudDownloadOutlined />
                    </template>
                    {{ isLoading ? "Generating Cheques on progress..." : "Generate Alta Cheques" }}
                </a-button>
            </div>
            <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'details'">
                        <a-button size="small" @click="details(record)">
                            <template #icon>
                                <SettingOutlined></SettingOutlined>
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination :datarecords="data" class="mt-5" />
        </div>
    </div>
    <a-modal v-model:open="openModal" style="width: 700px;" title="Check Alta Details" :footer="false">
        <div class="product-table  mt-5 mb-5" style="width: 100%;">

            <tr>
                <td class="px-6 py-1 font-bold text-gray-500">
                   Account Number
                </td>
                <td class="px-6 py-1  " style="width: 400px;">
                    {{ selectDataDetails.acc_num }}
                </td>
            </tr>
            <tr>
                <td class="px-6 py-1 font-bold text-gray-500">
                    Acccount Name
                </td>
                <td class="px-6 py-1 " style="width: 400px;">
                    {{ selectDataDetails.acc_name }}
                </td>
            </tr>
            <tr>
                <td class="px-6 py-1 font-bold text-gray-500">
                    Business Unit
                </td>
                <td class="px-6 py-1 " style="width: 400px;">
                    {{ selectDataDetails.business_unit }}
                </td>
            </tr>
            <tr>
                <td class="px-6 py-1 font-bold text-gray-500">
                    Department
                </td>
                <td class="px-6 py-1 " style="width: 400px;">
                    {{ selectDataDetails.dept_name }}
                </td>
            </tr>

        </div>
    </a-modal>
</template>
<script>
import dayjs from 'dayjs';
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
        dateBackEnd: String,
    },
    data() {
        return {
            isGeneratingShow: false,
            openModal: false,
            isFetching: false,
            isLoading: false,
            selectDataDetails: [],
            dateMonth: this.dateBackEnd ? dayjs(this.dateBackEnd) : null,
            progressBar: {
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                currentRow: 0,
            },
        }
    },
    methods: {
        details(data) {
            // console.log('here')
            axios.get(route('alta.details'), {
                params: {
                    check_id: data.check_id
                }
            })
                .then(response => {
                    this.selectDataDetails = response.data;
                    this.openModal = true;
                })
                .catch(error => {

                    console.error('Error fetching image details:', error);
                });
        },
        handleChangeMonth(monthObj, monthStr) {
            this.isFetching = true;
            this.$inertia.get(route('alta.reports'), {
                altaDates: monthStr,
            })
        },
        startGenerating() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.generate.alta'), {
                altaDates: this.dateBackEnd,
            })
        }
    },
    mounted() {
        this.$ws
            .private(`generating-general-checks.${this.$page.props.auth.user.id}`)
            .listen(".generating-reports", (e) => {
                this.progressBar = e;
            });
    }
}
</script>
<style scoped>
.product-table td {
    width: 50%;
    border: 1px solid #ddd;
}

.product-table th {
    border: 1px solid #b1b1b1;
    width: 100%;
    background: #d8d8d8;
}
</style>
