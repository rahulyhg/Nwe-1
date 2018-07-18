<template>
    <li id="menu-noti" class="menu-icon">
         <span class="icon">
            <i class="fas fa-bell" aria-hidden="true"></i> <p>{{ notifications.length }}</p>
        </span>
        <div class="sub">
            <ul>
                <li v-for="(notification, key) in notifications" >
                    <a href="javascript:void(0)" v-on:click="MarkAsRead(notification);">{{ notification.data.cv.job_name }}
                        <p>{{ notification.data.cv.message }}</p>
                    </a>
                        
                </li>
                <li v-if="notifications.length == 0" >
                    Không có thông báo mới
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
    export default {
        props:['notifications'],
        methods:{
            MarkAsRead: function (notification) {
                var data = {
                    id: notification.id
                };
                axios.post('/notification/read', data).then(response =>{
                    if(response.data == "employers"){
                        window.location.href = '/employer/jobs#'+notification.data.cv.job_id;
                        this.notifications.splice(this.notifications.indexOf(notification), 1);
                    }
                    if(response.data == "users"){
                        window.location.href = '/user/cvs#'+notification.data.cv.job_id;
                        this.notifications.splice(this.notifications.indexOf(notification), 1);
                    }
                    console.log(response.data);
                });
            }
        },
        mounted() {
            
        }
    }
</script>
