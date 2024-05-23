<template>
    <a-modal  style="top: 25px" width="100%" title="Details" :footer="null">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody>
                    <tr>
                        <td
                            class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Customer Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                            {{ datarecords.fullname }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check From
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.department }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Number
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Approving Officer
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.approving_officer }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Class
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_class }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Status
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_status }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Date
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_date }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Account No
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.account_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Received
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_received }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Account Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.account_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Received As
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_type }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Bank Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.bankbranchname }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Category
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_category }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Amount
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ datarecords.check_amount }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </a-modal>

</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    components: {
        Link,
    },
    props: {
        datarecords: Object,

    },
    methods: {
        paginate(link) {
            if (link.url) {
                this.$inertia.visit(link.url, {
                    preserveState: true,
                    preserveScroll: true
                })
            }
        }
    }
}
</script>

<style scoped>
.product-table {
    margin: 1%;
    padding: 10px;
    border-radius: 10px;
}

.product-table tr {
    border: 1px solid #ddd;
}

.product-table td {
    border: 1px solid #ddd;
}
</style>
