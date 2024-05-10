<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { LockOutlined, UserOutlined } from "@ant-design/icons-vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    username: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>

    <div class="bg-ccm" :style="{'background-image': 'linear-gradient(to bottom, rgba(0, 0, 0, 0.9), transparent), url(' + selectedImage + ')' }">
        <div class="example-box">
  <div class="background-shapes"></div>
  <a-row :gutter="[16, 16]">
            <a-col :span="12" style="height: 100vh;">

                <div class="flex justify-center items-center" style="height: 100%;">
                    <div style="width: 80%; margin: auto;">
                        <div class="text-center">
                            <img class="img-bg" src="/ccmlogo/lgremove.png" alt="">
                        </div>
                        <p class="text-center"
                            style="font-size: 20px; color: white; letter-spacing: 1px; line-height: 30px;">
                            Cheque Clearing and Monitoring
                            <br>
                            System
                        </p>
                    </div>

                </div>
            </a-col>
            <a-col :span="12">

                <div id="form-ui">
                    <form id="form">
                        <div id="form-body">
                            <div id="welcome-lines">
                                <div id="welcome-line-1">
                                    <img src="/ccmlogo/lgremove.png" alt="alt">

                                </div>
                                <div id="welcome-line-2">Welcome back</div>
                            </div>
                            <div id="input-area">
                                <div class="form-inp flex">
                                    <UserOutlined class="mr-2 text-black" />
                                    <input v-model="form.username" placeholder="Username" type="text" >
                                </div>
                                <div v-if="form.errors.username" class="text-white" style="

                            color: #ff6262;
                            font-size: 11px;
                            border-radius: 5px;
                            padding: 5px;
                        ">
                                    *{{ form.errors.username }}
                                </div>
                                <div class="form-inp flex">
                                    <KeyOutlined class="mr-2 text-black" />
                                    <input v-model="form.password" placeholder="Password" type="password" @keyup.enter="submit">
                                </div>
                                <div v-if="form.errors.password" class="text-white mb-3" style="

                            color: #ff6262;
                            font-size: 11px;
                            border-radius: 5px;
                            padding: 5px;
                        ">
                                    *{{ form.errors.password }}
                                </div>
                            </div>
                            <div id="submit-button-cvr">
                                <a-button :loading="form.processing" block type="primary" @click="submit"
                                    style="padding: 10px 0  30px 0;">
                                    <template #icon>
                                        <LoginOutlined />
                                    </template>
                                    {{ form.processing ? "Logging in.." : "Login" }}
                                </a-button>
                            </div>
                            <div id="forgot-pass">
                                <a href="#">Forgot password?</a>
                            </div>
                            <div id="bar"></div>
                        </div>
                    </form>
                </div>

            </a-col>

        </a-row>
</div>

    </div>
</template>
<script>
export default {
    data() {
        return {
            imageList: [
                '/ccmbg/logo1.jpg',
                '/ccmbg/bg2.jpg',
                '/ccmbg/bg4.jpg',
                '/ccmbg/bg5.jpg',
                '/ccmbg/bg6.jpg',
                '/ccmbg/bg7.jpg',
                '/ccmbg/bg7.jpg',
                '/ccmbg/bg8.jpg',
            ],
            selectedImage: ''
        }
    },
    created() {
        this.selectRandomImage();
    },
    methods: {
        selectRandomImage() {
            const randomIndex = Math.floor(Math.random() * this.imageList.length);
            this.selectedImage = this.imageList[randomIndex];
        }
    }
}
</script>
<style>
#form {


    width: 450px;
    height: 550px;
    padding: 70px;
    background: rgba(0, 0, 0, 0.13);
    /*
    background: -webkit-linear-gradient(to left, #283E51, #4B79A1);

    background: linear-gradient(to left, #283E51, #4B79A1); */


    box-shadow: rgba(255, 255, 255, 0.4) 0px 2px 4px, rgba(255, 255, 255, 0.849) 0px 7px 13px -3px, rgba(255, 255, 255, 0.842) 0px -3px 0px inset;
    border-radius: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

}

.bg-ccm {

    background-repeat: no-repeat;
    background-size: cover;
}

.img-bg {
    height: 200px;
    margin: auto
}

/* #form-body {
    background-image: url('ccmbg/logo.jpg');
} */

#welcome-lines {
    text-align: center;
    line-height: 1;
}

#welcome-lines img {
    height: 150px;
}

/* #welcome-line-1 {
    background-image: url('/public/Logo/treasury.png');
} */

#welcome-line-2 {
    color: white;
    font-size: 20px;
    letter-spacing: 2px;
    margin-top: 1px;
}

#input-area {
    margin-top: 20px;
}

.form-inp {
    padding: 11px 25px;
    background: rgb(255, 255, 255);
    border: 1px solid #0f0f0fb2;
    line-height: 1;
    border-radius: 8px;
}

/* .form-inp:focus {
    border: 1px solid #000000;
} */

.form-inp:nth-child(2) {
    margin-top: 15px;
}

.form-inp input {
    width: 100%;
    background: none;
    font-size: 13.4px;
    color: black !important;
    border: none;
    padding: 0;
    margin: 0;
}

.form-inp input:focus {
    outline: none;
}

#submit-button-cvr {
    margin-top: 20px;
}

#submit-button {
    display: block;
    width: 100%;
    color: #00FF7F;
    background-color: transparent;
    font-weight: 600;
    font-size: 14px;
    margin: 0;
    padding: 14px 13px 12px 13px;
    border: 0;
    outline: 1px solid #00FF7F;
    border-radius: 5px;
    line-height: 1;
    cursor: pointer;
    transition: all ease-in-out .3s;
}

#submit-button:hover {
    transition: all ease-in-out .3s;
    background-color: #00FF7F;
    color: #161616;
    cursor: pointer;
}

#forgot-pass {
    text-align: center;
    margin-top: 10px;
}

#forgot-pass a {
    color: #868686;
    font-size: 12px;
    text-decoration: none;
}

#bar {
    position: absolute;
    left: 50%;
    bottom: -50px;
    width: 28px;
    height: 8px;
    margin-left: -33px;
    background-color: #00FF7F;
    border-radius: 10px;
}

#bar:before,
#bar:after {
    content: "";
    position: absolute;
    width: 8px;
    height: 8px;
    background-color: #ececec;
    border-radius: 50%;
}

#bar:before {
    right: -20px;
}

#bar:after {
    right: -38px;
}


.example-box * {
  z-index: 2;
}

h1 {
  font-family: Montserrat, sans-serif;
  color: white;
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
}
.example-box {
  width: 100%;
  height: 100vh;
  align-items: center;
  position: relative;
  overflow: hidden;
  background-size: cover;
  color: white;
  font-family: sans-serif;
  font-weight: 200;
  z-index: 1;
}

.example-box * {
  z-index: 2;
}
.background-shapes {
  content: "";
  position: absolute;
  z-index: 2;
  left: 0;
  top: 0;
  width: 100%;
  height: 5076px;
  overflow: hidden;
  background-size: 100%;
  animation: 70s infiniteScroll linear infinite;

  background-image: url(https://cdn2.hubspot.net/hubfs/53/Pricing%202017%20Assets/marketing/Header_Circles-1.svg);
}

@-webkit-keyframes infiniteScroll {
  0% {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  100% {
    -webkit-transform: translate3d(0, -1692px, 0);
    transform: translate3d(0, -1692px, 0);
  }
}

@keyframes infiniteScroll {
  0% {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  100% {
    -webkit-transform: translate3d(0, -1692px, 0);
    transform: translate3d(0, -1692px, 0);
  }
}

</style>
