<template>
    <a-form layout="horizontal">
        <a-row :gutter="[16, 16]">
            <a-col :span="12">
                <a-form-item name="price" label="Cash" has-feedback :validate-status="error.amount ? 'error' : ''"
                    :help="error.amount">
                    <a-input  allow-clear v-model:value="form.amount" placeholder="Enter Amount" />
                </a-form-item>
                <a-form-item allow-clear  label="AR & DS"  has-feedback :validate-status="error.ards ? 'error' : ''" :help="error.ards">
                    <a-input  allow-clear v-model:value="form.ards" placeholder="Enter Ad Ds" />
                </a-form-item>
                <a-form-item label="Penalty Amount" has-feedback  :validate-status="error.penalty ? 'error' : ''" :help="error.penalty">
                    <a-input  allow-clear v-model:value="form.penalty"  placeholder="Enter Penalty" />
                </a-form-item>
                <a-form-item  label="Replacement Date" has-feedback  :validate-status="error.date ? 'error' : ''" :help="error.date">
                    <a-date-picker style="width: 100%;" @change="changeDate" />
                </a-form-item>
            </a-col>
            <a-col :span="12">
                <a-form-item label="Reason" has-feedback  :validate-status="error.date ? 'error' : ''" :help="error.reason">
                    <a-textarea :rows="9" v-model:value="form.reason" placeholder="Enter Reason" />
                </a-form-item>
            </a-col>
        </a-row>
        <div class="flex justify-end">
            <a-button type="primary"  style="width: 330px;" @click="submit" :loading="isSubmitting"
                :disabled="isDisable">
                <template #icon>
                    <CheckCircleFilled />
                </template>
                Submit Cash Replacement
            </a-button>
        </div>
    </a-form>
</template>
<script>
import { message, notification } from 'ant-design-vue';
import { useForm } from '@inertiajs/vue3';
import { pickBy } from 'lodash';
export default {
    props: {
        record: Object,
        modal: Boolean,
        type: Number,
    },
    data() {
        return {
            isSubmitting: false,
            error: {},
            inputError: '',
            isDisable: false,
            form: useForm({
                id: this.record.checks_id,
                amount: this.record.check_amount,
                penalty: '',
                ards: '',
                reason: '',
                date: '',
            }),
        }
    },
    methods: {
        submit() {
            
            const routeUrl = this.type == 1 ? 'pdc_cash.replacement' : 'pdc_cash_partial.replacement';
            const desc = this.type == 1 ? 'Replaced Cash Successfully' : 'Replaced Partial Cash Successfully';

            this.form.transform((data) => ({
                ...pickBy(data)
            })).post(route(routeUrl), {
                onStart: () => {
                    this.isSubmitting = true;
                    message.loading('Action in progress..', 0)
                },
                onSuccess: () => {
                    message.destroy();
                    this.isSubmitting = false;
                    this.isDisable = true;
                    notification['success']({
                        message: 'Success',
                        description: desc,
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 200);
                },
                onError: (errors) => {
                    this.isSubmitting = false;
                    message.destroy();
                    this.inputError = 'error';
                    this.error = errors;
                }
            })
        },
        changeDate(obj, str) {
            this.form.date = str;
        }
    }
}
</script>
