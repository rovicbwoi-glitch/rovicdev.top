$('document').ready(function()
{
    
    function getNotification(){
        $.ajax({
            url: "{$base_url}serverside/data/get_notification.php",
            type: "GET",
            dataType: "JSON",
    		cache: false,
            success: function(data)
            {
                var notifications = '';
    			if(data.response == 1)
    			{
    			    if (data.notiftotal === 0) {
    			        $(".notification-toggle").removeClass("beep");
    				    notifications = `<a href="javascript:void(0)" class="dropdown-item dropdown-item-unread">
                                        	<div class="dropdown-item-icon bg-primary text-white">
                                        		<i class="far fa-smile"></i>
                                        	</div>
                                        	<div class="dropdown-item-desc"> No available notifications to show for now. <div class="time text-primary">Stay safe and secure.</div>
                                        	</div>
                                        </a>`;
    			    }else{
    			        $(".notification-toggle").addClass("beep");
    			        notifications = data.notifica;
    			    }
    			    $(".profile-notifications").html(notifications);
    			}
    			if(data.response == 2)
    			{
    				swal(`Failed`, `Failed getting data from AJAX.`, `warning`, {
                        button: false,
                        closeOnClickOutside: false,
                        timer: 3000
                    }).then(() => {
                        location.reload()
                    });
    			}
    			if(data.response == 0){
    				swal(`Failed`, `Failed getting data from AJAX.`, `warning`, {
                        button: false,
                        closeOnClickOutside: false,
                        timer: 3000
                    }).then(() => {
                        location.reload()
                    });
    			}
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                swal(`Failed`, `Failed getting data from AJAX.`, `warning`, {
                    button: false,
                    closeOnClickOutside: false,
                    timer: 3000
                }).then(() => {
                    location.reload()
                });
            }
        });
    }
    
    $(".notificationlist").on("click", ".view-notification", function() {
        var id = $(this).data("id");
        var type = $(this).data("type");
        var date = $(this).data("date");
    
        $.ajax({
            url: "{$base_url}serverside/data/read_notification.php",
            data: "id="+id,
            type: "GET",
            dataType: "JSON",
    		cache: false,
            success: function(data)
            {
    			if(data.response == 1){
            		modalize(`<span class="badge badge-primary">`+type+`</span> Posted `+date+` `, data.content);
            	}
            	if(data.response == 2){
            		modalMessage('danger','Error', data.msg);
            	}
            	if(data.response == 3){
            		modalMessage('danger','Error', data.errormsg);
            	}
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                swal(`Failed`, `Failed getting data from AJAX.`, `warning`, {
                    button: false,
                    closeOnClickOutside: false,
                    timer: 3000
                }).then(() => {
                    location.reload()
                });
            },
            complete: function(){
    
    		}
        });
        
    })
  
getNotification()
});