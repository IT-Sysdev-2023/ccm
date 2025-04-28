<template>
    <!-- <p>{{ data }}</p> -->
     <div v-for="photo in data">
        <!-- {{ photo }} -->
         <img style="height: 200px; width: 200px;" :src="'http://172.16.161.34:8080/hrms/employee/'+ photo.photo" alt="">
     </div>
    <!-- hello -->
  </template>

  <script setup>
//   export default {
//     props: {
//       result: Object
//     }
//   }
defineProps({
    data: Object
})
  </script>
