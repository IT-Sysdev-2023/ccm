<template>
    <a-modal style="top: 25px" width="50%" title="Details" :footer="null">
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Customer Name" class="text-right">{{ datarecords.fullname }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check From" class="text-right">{{ datarecords.department }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check No." class="text-right">{{ datarecords.check_no }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Approving Officer." class="text-right">{{ datarecords.approving_officer }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check Class." class="text-right">{{ datarecords.check_class }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check Status." class="text-right">{{ datarecords.check_status }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check Date." class="text-right">{{ datarecords.check_date }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Account No." class="text-right">{{ datarecords.account_no }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check Received." class="text-right">{{ datarecords.check_received }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Account Name." class="text-right">{{ datarecords.account_name }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Received As." class="text-right">{{ datarecords.check_type }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Bank Name." class="text-right">{{ datarecords.bankbranchname }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Check Category." class="text-right">{{ datarecords.check_category }}</a-descriptions-item>
        </a-descriptions>
        <a-descriptions bordered size="small">
            <a-descriptions-item label="Amount." class="text-right"> {{ datarecords.check_amount }}</a-descriptions-item>
        </a-descriptions>
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
}
</script>
