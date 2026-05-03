$(function() {
	var clippy = new ClipboardJS('.btn-copy');
	clippy.on('success', function(e) {
		swal({
			title: 'Details copied successfuly!',
			icon: 'success'
		}); e.clearSelection();
	}); var token = $("#app").data("token");
	
	$(".btn-logout").click(function() {
		swal({
			title: 'Are you sure?',
			icon: 'warning',
			buttons: ["Cancel", "Proceed"],
			dangerMode: true
		}).then((doLogout) => {
			if (doLogout) {
				location.replace("/logout");
			}
		});
	})
	
	function modalize(title, body) {
		$(".modalize").modal({
			backdrop: "static"
		});
		$(".modal-title").html(title);
		$(".modal-html").html(body);
	}

})