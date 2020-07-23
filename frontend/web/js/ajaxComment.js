$(function () {
    {
        let forms = $('.form-post-comment');
        for (let i = 0; i < forms.length; i++)
        {
            let form = forms[i];
            $(form).on('beforeSubmit', function() {
                let data = $(form).serialize();
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        console.log('sent ajax comment\n');
                        console.log(data);
                    },
                    error: function(jqXHR, errMsg) {
                        console.log(errMsg);
                        console.log(jqXHR);
                    }
                });
                return false; // prevent default submit
            });
        }
    }
})


