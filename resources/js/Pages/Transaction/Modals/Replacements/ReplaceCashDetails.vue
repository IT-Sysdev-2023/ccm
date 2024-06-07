<template>
    <a-modal width="1000px" :title="title" :footer="null" style="top: 50px">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th colspan="2"
                            class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Check replaced to cash
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Bounced check amount.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount_paid }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Ar# and DS#
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.ar_ds }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Penalty amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.penalty }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a-card>
                <template #>
                    <div class="flex mb-3">
                        <InfoCircleOutlined style="color: blue" />
                        <p class="ml-1 font-bold">Reason for return</p>
                    </div>

                    <div class="ml-4">
                        {{ selectDataDetails.reason }}
                    </div>
                </template>
            </a-card>
        </div>
    </a-modal>
</template>

<script>
export default {
    props: {
        selectDataDetails: Object,
        title: String
    }
}
</script>
