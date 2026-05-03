$(function () {

    function get_update() {
        $('.fetch-update').hide()
        $.get('/api/developerupdates', function (res) {
            if (res.status) {
                if (res.message.updates.length > 0) {
                    $('.fetch-update').show().addClass('show')
                    $('.panel-version').text(res.message.version)
                    res.message.updates.forEach((e) => {
                        $('.panel-update-list').append(`<li>${e}</li>`)
                    })
                }
                if (res.message.alert.title) {
                    swal(res.message.alert.title, res.message.alert.message, res.message.alert.type, {
                        button: true,
                        closeOnClickOutside: false,
                    })
                }
            } else {
                swal(`Oops`, res.message, `error`, {
                    button: false,
                    closeOnClickOutside: false,
                    timer: 2000
                })
            }
        }, "json")
    }
    if ($('.fetch-update')[0]) {
        get_update()
    }
})