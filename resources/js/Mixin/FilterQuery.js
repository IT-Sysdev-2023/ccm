
import { ref } from 'vue';
import debounce from 'lodash/debounce';
import axios from 'axios';

export function searchFunctionMixin() {

    const isRetrieving = ref(false);
    const optionsEmployee = ref([]);
    const optionsBunit = ref([]);
    const optionsDepartment = ref([]);
    const optionsCompany = ref([]);
    const optionsBank = ref([]);
    const optionsCustomer = ref([]);

    const debouncedSearchEmployee = debounce(async function (query) {
        try {
            if (query.trim().length) {

                isRetrieving.value = true;
                optionsEmployee.value = [];

                const { data } = await axios.get(
                    route("search.employeeName", { search: query })
                );

                optionsEmployee.value = data.map((userOption) => ({
                    title: userOption.name,
                    value: userOption.name,
                    label: userOption.name,
                    value1: userOption.emp_no,
                }));
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    const debouncedBunitName = debounce(async function (query) {
        try {
            if (query.trim().length) {

                isRetrieving.value = true;
                optionsBunit.value = [];

                const { data } = await axios.get(
                    route("search.bunit", { search: query })
                );

                optionsBunit.value = data.map((data) => ({
                    title: data.bname,
                    value: data.businessunit_id,
                    label: data.bname,
                }));
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    const debouncedDepartment = debounce(async function (query) {
        try {
            if (query.trim().length) {

                isRetrieving.value = true;
                optionsDepartment.value = [];

                const { data } = await axios.get(
                    route("search.checkfrom", { search: query })
                );

                optionsDepartment.value = data.map((data) => ({
                    title: data.department,
                    value: data.department_id,
                    label: data.department,
                }));
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    const debouncedCompany = debounce(async function (query) {
        try {
            if (query.trim().length) {

                isRetrieving.value = true;
                optionsCompany.value = [];

                const { data } = await axios.get(
                    route("search.company", { search: query })
                );

                optionsCompany.value = data.map((data) => ({
                    title: data.company,
                    value: data.company_id,
                    label: data.company,
                }));
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    const debounceBank = debounce(async function (query) {
        try {
            if (query.trim().length) {

                isRetrieving.value = true;

                optionsBank.value = [];

                const { data } = await axios.get(route('search.bankName', { search: query }))

                optionsBank.value = data.map(bank => ({
                    title: bank.bankbranchname,
                    value: bank.bank_id,
                    label: bank.bankbranchname
                }))
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    const debounceCustomer = debounce(async function (query) {
        try {
            if (query.trim().length) {
                isRetrieving.value = true;
                optionsCustomer.value = [];
                const { data } = await axios.get(route('search.customerName', { search: query }))
                optionsCustomer.value = data.map(customer => ({
                    title: customer.fullname,
                    value: customer.customer_id,
                    label: customer.fullname
                }))
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            isRetrieving.value = false;
        }
    }, 1000);

    return {
        isRetrieving,
        optionsEmployee,
        optionsBunit,
        debouncedSearchEmployee,
        debouncedBunitName,
        debouncedDepartment,
        optionsDepartment,
        debouncedCompany,
        debounceCustomer,
        optionsCompany,
        optionsBank,
        optionsCustomer,
        debounceBank,
    };
}






