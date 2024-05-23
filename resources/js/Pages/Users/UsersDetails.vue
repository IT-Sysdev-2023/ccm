<template>

    <Head title="Dashboard" />
    <div class="py-4">
        <!-- {{ user }} -->
        <div class="flex justify-end mb-10">
            <a-button @click="() => $inertia.get(route('users.index'))">
                <template #icon>
                    <SwapLeftOutlined />
                </template>
                return to user list
            </a-button>
        </div>
        <a-row :gutter="[16, 16]">
            <a-col :span="15">
                <div id="card">
                    <img id="avatar" :src="`/storage/users-image/${user.id}`" alt="avatar" />
                    <div id="info">
                        <p id="name">{{ user.name }}</p>
                        <p>

                        </p>
                        <p id="activity">{{ user.usertype_name }}</p>
                        <div id="stats">
                            <p class="stats-text">
                                <UserOutlined />
                                <span>Position:</span>
                                {{ user.employee3.position }}
                            </p>
                            <p class="stats-text">
                                <UserOutlined />
                                <span>Employee Type:</span>
                                {{ user.employee3.emp_type }}
                            </p>
                        </div>
                        <a-tag color="green" class="text-center">{{ user.user_status }}</a-tag>
                    </div>
                </div>
            </a-col>
            <a-col :span="9">
                <p class="text-center">
                    More Informations
                </p>
                <div class="mt-5 pading" style="font-size: 15px; font-weight: 500; color: #394867;">
                    <div class="flex justify-between mb-3">
                        <p>Username:</p>
                        <p>{{ user.username }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Department:</p>
                        <p>{{ user.departments.department }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Company:</p>
                        <p>{{ user.company.company }} - {{ user.company.acroname }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Business unit:</p>
                        <p>{{ user.businessunit.bname }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Contact:</p>
                        <p>{{ user.ContactNo }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Special Skills:</p>
                        <p>{{ user.employee3.applicant.specialSkills }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Home Town:</p>
                        <p>{{ user.employee3.applicant.home_address }}</p>
                    </div>
                    <div class="flex justify-between mb-3">
                        <p>Hobbies:</p>
                        <p>{{ user.employee3.applicant.hobbies }}</p>
                    </div>
                </div>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { HomeOutlined, UserOutlined } from "@ant-design/icons-vue";
export default {
    layout: AdminLayout,
    props: {
        user: "",
    },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800&display=swap");

#card {
    width: 100%;
    height: 350px;
    margin: 0;
    padding: 15px;
    display: flex;
    flex-direction: row;
    background: #ffffff;
    border-radius: 8px;
}

.pading {
    padding: 15px;
    border: #cacaca solid 1px;
    border-radius: 5px;
}

#avatar {
    width: 400px;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

#info {
    width: 100%;
    height: 100%;
    margin: 0 15px;
    display: flex;
    flex-direction: column;
}

#name {
    font-size: 24px;
    font-weight: 800;
    margin: 0;
    padding: 0;
}

#activity {
    color: #999999;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: -0.5px;
    margin: 0;
    padding: 0;
}

#stats {
    margin: auto 0 15px 0;
    padding: 0;
    display: flex;
    justify-content: space-between;
}

.stats-text {
    color: #5b5b5b;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: -1px;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: row;
    align-items: center;
}

.stats-text svg {
    fill: #5b5b5b;
    width: 20px;
    height: 20px;
}

.stats-text span {
    color: #000000;
    font-weight: 500;
    margin: 0 5px;
}


#btn:hover {
    background: #2a26ff;
}
</style>
