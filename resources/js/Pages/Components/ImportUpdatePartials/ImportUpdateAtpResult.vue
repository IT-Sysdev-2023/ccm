<template>
    <a-result status="success" title="Successfully Updated atp database"
        sub-title="Dont have a good day have a great day!">
        <template #extra>
            <a-button key="console" class="mt-2" @click="returnBack">
                <template #icon>
                    <ArrowLeftOutlined />
                </template>
                return back
            </a-button>
        </template>

    </a-result>





</template>

<script>
export default {
    props: {
    },
    methods: {
        returnBack() {
            this.$inertia.get(route('indeximportupdates'));
        }
    }
}
</script>