$(document).ready(function () {

    $('a[name = reply]').on('click', function () {

        var inputHidden = $('#parent-comment-id');

        if(inputHidden) inputHidden.remove();

        var input = document.createElement('input');

        input.type = 'hidden';
        input.name = 'parent_comment_id';
        input.id = 'parent_comment_id';
        input.value = this.id;
        document.querySelector('#comment-form').appendChild(input);

    });


    $('#comment-form').on('click', '#submit', function () {

        event.preventDefault();
        sendForm();

    });//onClickForm


    function addCaptcha() {

        let captcha = document.createElement('div');

        captcha.id = 'recaptcha';
        captcha.className = 'g-recaptcha';

        let parentElem = document.getElementById('parentCaptcha');

        parentElem.appendChild(captcha);

        $('#name, #email, #text').attr('disabled', 'disabled');


    }//addCaptcha


    function activateCaptcha() {
        grecaptcha.render('recaptcha', {
            'sitekey' : '6LeQ1pwUAAAAAIr49rxlV-GQe0pi-JSZmxAQ9rbr',
            'callback' : verifyCallback,
            'theme':'dark'

        });

    }//activateCaptcha


    function verifyCallback(response) {

        let url = getCaptchaRoute();

        $.ajax({

            url: url,
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'POST',
            dataType: "JSON",
            data: ({response}),
            complete: function (result) {
                alert(result);
console.log(result);
                if (result.responseJSON === true){

                    let captcha = document.getElementById('recaptcha');
                    captcha.remove();
                    $('#name, #email, #text').removeAttr('disabled');

                    sendForm();

                } else {

                    grecaptcha.reset(activateCaptcha())
                }
            }
        });
    } //verifyCallback


    function sendForm() {

        let button = $(this).css('display','none');
        // let loader = $('#fountainG').css('display','block');

        let data =$('#comment-form').serializeArray();
        let url = $('#comment-form').attr('action');

        $.ajax({

            url: url,
            data: ({data}),
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            dataType: "JSON",
            success: function (data) {

                if (data === true) {

                    button.css('display', 'block');
                    // loader.css('display', 'none');
                    location.reload();

                } else if (data === false) {

                    window.location.replace('http://spacewars/email/verify')

                } else {

                    var errors = data.errors;

                    errors.forEach(function (item, i, errors) {

                        $('#'+item).css('border', '1px solid red');

                    });

                    button.css('display', 'block');
                    loader.css('display', 'none');
                }
            },
            complete: function(xhr) {

                if (xhr.status === 429) {

                    addCaptcha();
                    activateCaptcha();
                }
            }
        });//ajax
    }//sendForm



});//document.ready













