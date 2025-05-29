<template>
    <a-row class="flex justify-between mt-5" align="middle" v-if="datarecords !== undefined">
        <a-col>
            <a-typography-text>{{ `Showing ${datarecords?.from || 0} to ${datarecords?.to || 0} of
                ${datarecords?.total.toLocaleString()}
                records` }}
            </a-typography-text>
        </a-col>
        <a-col>
            <a-config-provider>
                <template v-for="(link, key) in datarecords.links" :key="`link-${key}`">
                    <a-button style="border-radius: 2px;" :type="link.active ? 'primary' : 'default'"
                        v-html="link.label" @click="$emit('onPagination', link)" />
                </template>
            </a-config-provider>
        </a-col>
    </a-row>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import axios from 'axios';

export default {
    components: {
        Link,
    },
    props: {
        datarecords: Object,
    },
}
</script>
