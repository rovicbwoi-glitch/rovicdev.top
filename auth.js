$(function(){function errorMessage(type,title,message)
{$(".errors").html('<div class="alert alert-'+type+' alert-has-icon"><div class="alert-icon"><i class="far fa-lightbulb"></i></div><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><div class="alert-title">'+title+'</div>'+message+'</div></div>').slideDown();}
$(".authenticate").submit(function(e)
{e.preventDefault();$(".btn-submit").addClass("btn-progress");$.post('/api/login',$('.authenticate').serialize(),function(res)
{if(res.status==true){errorMessage('success','Success',res.message)
setTimeout(()=>{location.reload()},2000)}
else if(res.status==false){$(".btn-submit").removeClass("btn-progress");errorMessage('warning','Oops',res.message)}else{$(".btn-submit").removeClass("btn-progress");var error='';for(var i=0;i<res.errors.length;i++){error+='<li>'+res.errors[i].msg+'</li>';}
errorMessage('danger','Error',error);}},"json")})})