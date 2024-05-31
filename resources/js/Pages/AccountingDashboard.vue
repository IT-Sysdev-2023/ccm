<template>

    <Head title="Accounting Dashboard" />
    <div class="py-5">
        <a-row :gutter="[16, 16]">
            <a-col :span="17">
                <a-row :gutter="[16, 16]">
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p>Dated Checks</p>
                            <p>100</p>
                        </div>
                    </a-col>

                </a-row>
            </a-col>
            <a-col :span="7">
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsa dolor blanditiis non totam hic ducimus
                recusandae asperiores perspiciatis, sit itaque rerum, commodi nemo eos maxime voluptate vitae explicabo
                consequatur quae.
            </a-col>
        </a-row>


    </div>
</template>

<script>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
export default {
    layout: AccountingLayout,
    props: {
        checkCount: Number,
        pdcCount: Number,
        datedCount: Number,
        depositedCount: Number,
        bouncedCount: Number,
        replacementCount: Number,
    }
}
</script>

<style scoped>
.page {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    max-width: 300px;
    font-family: cursive;
    font-size: 20px;
    height: 200px;
    border-radius: 10px;
    box-shadow: rgba(50, 50, 105, 0.15) 0px 2px 5px 0px, rgba(0, 0, 0, 0.05) 0px 1px 1px 0px;
    background: #fff;
    background-image: linear-gradient(#f5f5f0 1.1rem, #ccc 1.2rem);
    background-size: 100% 1.2rem;
    line-height: 1.2rem;
    padding: 1.4rem 0.5rem 0.3rem 4.5rem;
}

.page::before,
.page::after {
    position: absolute;
    content: "";
    bottom: 10px;
    width: 40%;
    height: 10px;
    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.7);
    z-index: -1;
    transition: all 0.3s ease;
}

.page::before {
    left: 15px;
    transform: skew(-5deg) rotate(-5deg);
}

.page::after {
    right: 15px;
    transform: skew(5deg) rotate(5deg);
}

.page:hover::before,
.page:hover::after {
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.4);
}

.margin {
    position: absolute;
    border-left: 1px solid #d88;
    height: 100%;
    left: 3.3rem;
    top: 0;
}

.page p {
    margin: 0;
    text-indent: 1rem;
    padding-bottom: 1.2rem;
    color: black;
    line-height: 20px;
}
</style>
