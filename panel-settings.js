$(function(){
	let token = $("#app").data("token");
	let iConfirmWeb = 1, iConfirmVpn = 1, iConfirmTrial = 1;

	function getSettings(){
		$.post('/panel/settings/get', { _csrf : token }, function(res){
			$(".title").val(res.title);
			$(".description").val(res.description);
			$(".owner").val(res.owner);
			$(".logo").val(res.logo);
			$(".logoshow").text((res.logoshow > 0) ? 'Current : On' : 'Current : Off').val((res.logoshow > 0) ? '1' : '0');
			$(".maintenance").text((res.maintenance > 0) ? 'Current : On' : 'Current : Off').val((res.maintenance > 0) ? '1' : '0');
			$(".sessions").val(res.sessions);
			$(".theme").text(`Current : (${res.theme})`).val(res.theme);
			$(".summernote-simple").summernote("code", res.note);
			$(".trialcounter").val(res.trialcounter);
			$(".download1_name").val(res.link1_name)
			$(".download2_name").val(res.link2_name)
			$(".download3_name").val(res.link3_name)
			$(".download1_url").val(res.link1_url)
			$(".download2_url").val(res.link2_url)
			$(".download3_url").val(res.link3_url)
		}, "json")
	}

	function reloadPage()
	{
		setTimeout(function(){
			location.reload();
		}, 3000)
	}

	function submitFrom(api, form, btn)
	{
		$.post(api, $(form).serialize() , function(res){
			if(res.status === true){
				swal(`Success!`, `${res.message}`, `success`, {
					button: false,
					closeOnClickOutside: false
				});
				reloadPage();
			}else if(res.status === false){
				swal(`Ooops!`, `${res.message}`, `error`, {
					closeOnClickOutside: false
				});
			}else{
				let error  = '';
				for(let i = 0; i < res.errors.length; i++){error += res.errors[i].msg + '\n';}
				swal(`Ohh no!`, `${error}`, `error`, {
					closeOnClickOutside: false
				});
			}
			$(btn).removeClass("btn-progress");
		}, "json")
	}

	$(".btn-confirm-web").click(function(){
		$("#websettings > .btn-confirm-cancel").removeClass("d-none")
		$(this).text('Authorize').removeClass("btn-primary").addClass("btn-success")
		if(iConfirmWeb === 2)
		{
			$("#websettings > .btn-confirm-cancel").addClass("d-none")
			$(this).addClass("btn-progress");
			submitFrom('/panel/settings/website/set', '.websettings', '.btn-confirm-web')
			iConfirmWeb = 1
		}
		iConfirmWeb++
	})

	$(".btn-confirm-download").click(function(){
		$("#downloadsettings > .btn-confirm-cancel").removeClass("d-none")
		$(this).text('Authorize').removeClass("btn-primary").addClass("btn-success")
		if(iConfirmWeb === 2)
		{
			$("#downloadsettings > .btn-confirm-cancel").addClass("d-none")
			$(this).addClass("btn-progress");
			submitFrom('/panel/settings/download/set', '.downloadsettings', '.btn-confirm-download')
			iConfirmWeb = 1
		}
		iConfirmWeb++
	})

	$(".btn-confirm-vpn").click(function(){
		$("#vpnsettings > .btn-confirm-cancel").removeClass("d-none")
		$(this).text('Authorize').removeClass("btn-primary").addClass("btn-success")
		if(iConfirmVpn === 2)
		{
			$("#vpnsettings > .btn-confirm-cancel").addClass("d-none")
			$(this).addClass("btn-progress");
			submitFrom('/panel/settings/vpn/set', '.vpnsettings', '.btn-confirm-vpn')
			iConfirmVpn = 1
		}
		iConfirmVpn++
	})

	$(".btn-confirm-trial").click(function(){
		$("#trialsettings > .btn-confirm-cancel").removeClass("d-none")
		$(this).text('Authorize').removeClass("btn-primary").addClass("btn-success")
		if(iConfirmTrial === 2)
		{
			$("#trialsettings > .btn-confirm-cancel").addClass("d-none")
			$(this).toggleClass("btn-progress")
			submitFrom('/panel/settings/trialdelete', '.trialsettings', '.btn-confirm-trial')
			console.log(iConfirmTrial)
			iConfirmTrial = 1
		}

		iConfirmTrial++

	})

	$(".btn-confirm-cancel").click(function(){
		iConfirmWeb = 1
		iConfirmVpn = 1
		iConfirmTrial = 1
		let parentId = $(this).parent().attr('id')
					   $(this).addClass("d-none")
		$(`#${parentId} > #confirm`).text('Confirm').removeClass("btn-success").addClass("btn-primary")
		console.log()
	})

	getSettings()
})