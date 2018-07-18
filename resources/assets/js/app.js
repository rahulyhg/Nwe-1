
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('notification-user', require('./components/Notification.vue'));

const app = new Vue({
    el: '#app',
    data: {
    	notifications:''
    },
    created(){
    	axios.post('/notification/get').then(response =>{
    		this.notifications = response.data;
    	});
    	 
   		if($('meta[name="userId"]').length > 0){
    		var userId = $('meta[name="userId"]').attr('content');
    		Echo.private('App.User.'+ userId).notification((notification) =>{
    			this.notifications.push(notification);
    			document.getElementById('notify-sound').play();
    			if(window.location.pathname =="/user/cvs"){
    				if($('.job-detail[cv-id="'+notification.data.cv.id+'"]').length > 0){
    					var $this = $('.job-detail[cv-id="'+notification.data.cv.id+'"]');
    					if(notification.data.cv.status == "pending" && notification.data.cv.active_interview == "2"){
    						notification.data.cv.status = "interview";
    					}
    					if(notification.data.cv.status == "interview" && notification.data.cv.active_trial_work == "2"){
    						notification.data.cv.status = "trial_work";
    					}
    					if(notification.data.cv.status == "trial_work" && notification.data.cv.work == "2"){
    						notification.data.cv.status = "work";
    					}
    					if(notification.data.cv.status == "work" && notification.data.cv.active_complete_work == "2"){
    						notification.data.cv.status = "complete_work";
    					}
    					
						if( (notification.data.cv.status == "interview" && notification.data.cv.active_interview == "0") ||
						 (notification.data.cv.status == "trial_work" && notification.data.cv.active_trial_work == "0") || 
						 (notification.data.cv.status == "work" && notification.data.cv.active_work == "0") ||
						 (notification.data.cv.status == "complete_work" && notification.data.cv.active_complete_work == "0") ){
							$this.find('.progress [status="'+notification.data.cv.status+'"]').addClass('status-ongoing');
							$this.find('.button-group').html('<button class="btn btn-primary" cv-active-href="/cv/active-status/'+notification.data.cv.id+'" cv-update-active="1">Đồng ý</button>');
							if(notification.data.cv.status != "complete_work"){
								$this.find('.button-group').append('<button class="btn btn-red" cv-active-href="/cv/active-status/'+notification.data.cv.id+'" cv-update-active="2">Từ chối</button>');
							}
						} 
						if( (notification.data.cv.status =="interview" && notification.data.cv.active_interview == "1") ||
						 (notification.data.cv.status == "trial_work" && notification.data.cv.active_trial_work == "1") || 
						 (notification.data.cv.status == "work" && notification.data.cv.active_work == "1") ||
						 (notification.data.cv.status == "complete_work" && notification.data.cv.active_complete_work == "1") ){
							$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-pass');
						}
						if( (notification.data.cv.status == "interview" && notification.data.cv.active_interview == "2") ||
						 (notification.data.cv.status == "trial_work" && notification.data.cv.active_trial_work == "2") || 
						 (notification.data.cv.status == "work" && notification.data.cv.active_work == "2") || 
						 (notification.data.cv.status == "complete_work" && notification.data.cv.active_complete_work == "2") ){
							$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-fail');
						}  
    					
    				}
    			}
    			console.log(notification);
    		});
    		
    	}
        //console.log(userId);
    	if($('meta[name="employerId"]').length > 0){
    		var userId = $('meta[name="employerId"]').attr('content');
    		Echo.private('App.Employer.'+ userId).notification((notification) =>{
    			this.notifications.push(notification);
    			document.getElementById('notify-sound').play();
    			if(window.location.pathname =="/employer/jobs"){
    				if($('.applicant-item[applicant-id="'+notification.data.cv.id+'"]').length > 0){
    					var $this = $('.applicant-item[applicant-id="'+notification.data.cv.id+'"]');
    					if(notification.data.cv.status =="interview" && notification.data.cv.active_interview == "1"){
    						$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-pass');
							$this.find('.button-group').html('<button class="btn btn-primary" cv-update-href="/cv/update-status/'+notification.data.cv.id+'" cv-update-status="trial_work">Mời thử việc</button>'+
								'<button class="btn btn-red" cv-active-href="/employer/cv/active-status/'+notification.data.cv.id+'" cv-update-active="2">Từ chối</button>');
    					}
						 if(notification.data.cv.status == "trial_work" && notification.data.cv.active_trial_work == "1"){
						 	$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-pass');
							$this.find('.button-group').html('<button class="btn btn-primary" cv-update-href="/cv/update-status/'+notification.data.cv.id+'" cv-update-status="work">Mời làm việc</button>'+
								'<button class="btn btn-red" cv-active-href="/employer/cv/active-status/'+notification.data.cv.id+'" cv-update-active="2">Từ chối</button>');
						 } 
						 if(notification.data.cv.status == "work" && notification.data.cv.active_work == "1"){
						 	$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-pass');
							$this.find('.button-group').html('<button class="btn btn-primary" cv-update-href="/cv/update-status/'+notification.data.cv.id+'" cv-update-status="complete_work">Hoàn thành</button>'+
								'<button class="btn btn-red" cv-active-href="/employer/cv/active-status/'+notification.data.cv.id+'" cv-update-active="2">Chưa hoàn thành</button>');
						 }
						 if(notification.data.cv.status == "complete_work" && notification.data.cv.active_complete_work == "1") {
							$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-pass');
						}
						if( (notification.data.cv.status == "interview" && notification.data.cv.active_interview == "2") ||
						 (notification.data.cv.status == "trial_work" && notification.data.cv.active_trial_work == "2") || 
						 (notification.data.cv.status == "work" && notification.data.cv.active_work == "2") || 
						 (notification.data.cv.status == "complete_work" && notification.data.cv.active_complete_work == "2") ){
							$this.find('.progress [status="'+notification.data.cv.status+'"]').removeClass('status-ongoing').addClass('status-fail');
						}  
    				}
    			}
    		});
    		
    	}
    	
    }
});
