<template>
    <a-result status="success" title="Successfully imported the textfile"
        sub-title="Dont have a good day have a great day!">
        <template #extra>
            <a-button key="console" class="mt-2" @click="returnBack">
                <template #icon>
                    <ArrowLeftOutlined />
                </template>
                return back
            </a-button>
        </template>

        <!-- <a-timeline>
            <div v-for="(item, key) in dataRecord" :key="key">
                <a-timeline-item>
                    <a-row :gutter="[16, 16]">
                        <a-col :span="4" class="mb-0">
                            <p><strong>{{ item[0].department }}</strong></p>
                        </a-col>
                        <a-col :span="3" class="mb-0">
                            <p>{{ item.length }}</p>
                        </a-col>
                    </a-row>
                </a-timeline-item>
            </div>

            <a-button key="console" type="primary" block ghost style="width: 300px;" class="mt-2"
                @click="downLoadExcel">
                <template #icon>
                    <DownloadOutlined />
                </template>
                Download Excel
            </a-button>

        </a-timeline> -->
    </a-result>





</template>

<script>
export default {
    props: {
        downloadExcel: Object,
        dataRecord: Array,
    },
    methods: {
        returnBack() {
            this.$inertia.get(route('indeximportupdates'));
        }
    }
}
</script>