<template>
    <a-alert class="mt-4 mb-2" :message="message" :description="desc" type="error" show-icon />
</template>

<script>
export default {
    props: {
        message: String,
        desc: String,
    }
}
</script>
