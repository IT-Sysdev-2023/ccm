<template>
    <a-row class="flex justify-between" align="middle">
        <a-col>
            <a-typography-text>{{ `Showing ${datarecords.meta.from || 0} to ${datarecords.meta.to || 0} of ${datarecords.meta.total.toLocaleString()}
                records` }}
            </a-typography-text>
        </a-col>
        <a-col>
            <a-config-provider>
                <template v-for="(link, key) in datarecords.meta.links" :key="`link-${key}`">
                    <a-button style="border-radius: 2px;" :type="link.active ? 'primary' : 'default'"
                        v-html="link.label" @click="paginate(link)" />
                </template>
            </a-config-provider>
        </a-col>
    </a-row>
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
