<template>
    <a-card class="mb-3">
        <a-statistic class="text-center" :title="title" :value="count"  :value-style="{ color: '#3f8600' }"
            style="margin-right: 50px">
            <template #prefix>
                <LikeOutlined />
            </template>
        </a-statistic>
    </a-card>
</template>

<script>
export default {
    props: {
        title: String,
        count: Number
    }
}
</script>
