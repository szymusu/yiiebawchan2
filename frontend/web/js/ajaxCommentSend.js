let prepareForSend;
$(function () {
    prepareForSend = function(form) {
        $(form).removeAttr('onfocusin');
        console.log(form);
        $(form).on('beforeSubmit', function () {
            let data = $(form).serialize();
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: data,
                success: function (data) {
                    $(form).find('textarea').val('');
                    $($(form).closest('.post-item').find('.comment-container')[0]).append(data);
                },
                error: function (jqXHR, errMsg) {
                    console.log(errMsg);
                    console.log(jqXHR);
                }
            });
            return false; // prevent default submit
        });
    }

    let forms = $('.form-post-comment');
    for (let i = 0; i < forms.length; i++)
    {
        prepareForSend(forms[i]);
    }
})


