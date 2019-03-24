$(function(){
    
    // HTML's tags initialisation
    $('#confirm').attr('disabled', true);
    $('#username-length').css('color', 'red');
    $('#username-uniqueness').css('color', 'red')
    $('#password-lowercase').css('color', 'red');
    $('#password-uppercase').css('color', 'red');
    $('#password-number').css('color', 'red');
    $('#password-length').css('color', 'red');
    $('#password-repeat').css('color', 'red');
    
    //regexs for checkings
    var lowerCaseRegex = /[a-z]/;
    var upperCaseRegex = /[A-Z]/;
    var hasNumberRegex = /[0-9]/;
      
    //check username validity
    $('#pseudo').keyup(function(){
        
        $('#pseudo').val().length < 6                           ?   $('#username-length').css('color','red')        :   $('#username-length').css('color','green');
    });
    
    //check password validity
    $('#password').keyup(function(){
        
        !lowerCaseRegex.test($('#password').val())              ?   $('#password-lowercase').css('color','red')     :   $('#password-lowercase').css('color','green');
        !upperCaseRegex.test($('#password').val())              ?   $('#password-uppercase').css('color','red')     :   $('#password-uppercase').css('color','green');
        !hasNumberRegex.test($('#password').val())              ?   $('#password-number').css('color','red')        :   $('#password-number').css('color','green');
        $('#password').val().length < 8                         ?   $('#password-length').css('color','red')        :   $('#password-length').css('color','green');
        $('#password').val() !== $('#password-confirm').val()   ?   $('#password-repeat').css('color','red')        :   $('#password-repeat').css('color','green');
    });
    $('#password-confirm').keyup(function(){
        
        $('#password').val() !== $('#password-confirm').val()   ?   $('#password-repeat').css('color','red')        :   $('#password-repeat').css('color','green');
    });
    
    //check global inscription validity
    $('#register-form').keyup(function(){
        
        if( $('#pseudo').val().length < 6 ||
            !lowerCaseRegex.test($('#password').val()) ||
            !upperCaseRegex.test($('#password').val()) ||
            !hasNumberRegex.test($('#password').val()) ||
            $('#password').val().length < 8 ||
            $('#password').val() !== $('#password-confirm').val())
        {    
            $('#confirm').attr('disabled', true);
        }
        else
        {    
            $('#confirm').attr('disabled', false);
        }
    });
});