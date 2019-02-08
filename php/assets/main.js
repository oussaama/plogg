// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');

        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


//hide the div of result
$('.result-modal').hide();

//submit modal and show result
$('#application').submit(function (e) {
    e.preventDefault();
    $(".result-random").html('<tr><td colspan="3" class="text-center text-info">Waiting Result</td></tr>');
    var request = {
        'baseline': $('#baseline').val(),
        'total': $('#total').val(),
        'startDate': $('#startDate').val(),
        'endDate': $('#endDate').val()
    };
    message = "";
    //control of input basline
    if (request.baseline == "") {
        message += '- you have to enter the baseline <br/>';
    }

    //control of input total
    if (request.total == "") {
        message += '- you have to enter the total <br/>';
    }

    //control of input start date
    if (request.startDate == "") {
        message += '- you have to enter the start date <br/>';
    }

    //control of input end date
    if (request.endDate == "") {
        message += '- you have to enter the end date <br/>';
    }

    //control of input end date
    if (request.endDate < request.startDate) {
        message += '- you have to enter the end date great than start date<br/>';
    }

    //show input field errors
    if(message !=""){
        $.alert({
            icon: 'fa fa-warning',
            columnClass: 'medium',
            title: 'Encountered an error!',
            content: message,
            type: 'red',
            typeAnimated: true,
        });
        return false;
    }

    $('.result-load').show();
    $.ajax({
        method: 'post',
        data: request,
        url: 'functions/traitement.php',
        success: function (result) {
            html = "";
            for (var i = 0; i < result.length; i++) {
                html += "<tr> <td>" + i + "</td><td>" + result[i].date + "</td><td>" + result[i].value + "</td></tr>"
            }
            $('.result-modal').show();
            $(".result-random").html(html);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
})