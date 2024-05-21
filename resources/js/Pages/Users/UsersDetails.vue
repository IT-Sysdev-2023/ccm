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
                        <img
                            id="avatar"
                            :src="'http://172.16.161.34:8080/hrms' + user.employee3.applicant.photo"
                            alt="avatar"
                        />
                        <div id="info">
                            <p id="name">{{ user.name }}</p>
                            <p id="activity">{{ user.usertype_name }}</p>
                            <div id="stats">
                                <p class="stats-text">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"
                                        />
                                    </svg>
                                    <span>5k</span>
                                    followers
                                </p>
                                <p class="stats-text">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M12 9a3.75 3.75 0 100 7.5A3.75 3.75 0 0012 9z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M9.344 3.071a49.52 49.52 0 015.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 01-3 3h-15a3 3 0 01-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 001.11-.71l.822-1.315a2.942 2.942 0 012.332-1.39zM6.75 12.75a5.25 5.25 0 1110.5 0 5.25 5.25 0 01-10.5 0zm12-1.5a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>147</span>
                                    photos
                                </p>
                                <p class="stats-text">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M5.566 4.657A4.505 4.505 0 016.75 4.5h10.5c.41 0 .806.055 1.183.157A3 3 0 0015.75 3h-7.5a3 3 0 00-2.684 1.657zM2.25 12a3 3 0 013-3h13.5a3 3 0 013 3v6a3 3 0 01-3 3H5.25a3 3 0 01-3-3v-6zM5.25 7.5c-.41 0-.806.055-1.184.157A3 3 0 016.75 6h10.5a3 3 0 012.683 1.657A4.505 4.505 0 0018.75 7.5H5.25z"
                                        />
                                    </svg>
                                    <span>93</span>
                                    posts
                                </p>
                            </div>
                            <p id="btn">{{ user.user_status }}</p>
                        </div>
                    </div>
                </a-col>
                <a-col :span="9">
                    <div class="mt-5 pading" style="font-size: 16px; font-weight: 600; color: #394867;">
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
                    </div>
                </a-col>
            </a-row>
        </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
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
    height: 220px;
    margin: 0;
    padding: 15px;
    display: flex;
    flex-direction: row;
    background: #ffffff;
    border-radius: 8px;
    /* box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px; */
}
.pading {
    padding: 15px;
}

#avatar {
    width: 200px;
    height: 200px;
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
    font-size: 18px;
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
    font-weight: 800;
    margin: 0 5px;
}

#btn {
    color: #ffffff;
    font-size: 20px;
    font-weight: 600;
    text-align: center;
    width: 100%;
    margin: 0;
    padding: 5px;
    background: #4bb61a;
    border-radius: 8px;
    cursor: pointer;
}

#btn:hover {
    background: #2a26ff;
}
</style>
