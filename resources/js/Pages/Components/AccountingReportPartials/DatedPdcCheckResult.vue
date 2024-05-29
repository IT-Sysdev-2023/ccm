<template>
    <a-result status="success" title="Successfully generating the data to excel"
        sub-title="You can now download the excel file just click the dowload button">
        <template #extra>
            <a-button key="console" class="mt-2" @click="returnBack">
                <template #icon>
                    <ArrowLeftOutlined />
                </template>
                return back
            </a-button>
        </template>

        <a-timeline>

            <a-button key="console" type="primary" block ghost style="width: 300px;" class="mt-2"
                @click="downLoadExcel">
                <template #icon>
                    <DownloadOutlined />
                </template>
                Download Excel
            </a-button>

        </a-timeline>

    </a-result>




</template>

<script>
export default {
    props: {
        downloadExcel: Object,
        redirect: Number,

    },
    methods: {
        downLoadExcel() {
            window.location.href = this.downloadExcel;
        },
        returnBack() {
            if(this.redirect == 1){
                this.$inertia.get(route('datedpcchecks.accounting'));
            }else{
                this.$inertia.get(route('reports.dpdc'));
            }
        }
    }
}
</script>

