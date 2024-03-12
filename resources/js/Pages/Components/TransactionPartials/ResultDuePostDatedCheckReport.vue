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

            <a-timeline-item>
                <a-row :gutter="[16, 16]">
                    <a-col :span="4" class="mb-0">
                        <p>{{ buname }}</p>
                    </a-col>
                    <a-col :span="4" class="mb-0">
                        <p>{{ dataCount }}</p>
                    </a-col>
                </a-row>
            </a-timeline-item>


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
        dataCount: Number,
        buname: String,
    },
    methods: {
        downLoadExcel() {
            window.location.href = this.downloadExcel;
        },
        returnBack() {
            this.$inertia.get(route('duePdcReports.checks'));
        }
    }
}
</script>