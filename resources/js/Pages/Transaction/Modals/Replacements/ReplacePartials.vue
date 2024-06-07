<template>
     <a-modal width="100%" :title="title" :footer="null"
        style="top: 50px">
        <a-card style="border: none">
            <a-table bordered :data-source="partialData" size="small" :columns="partialColumns">
            </a-table>
        </a-card>
    </a-modal>
</template>

<script>
export default {
    props: {
        partialData: Object,
        title: String
    }
}
</script>
