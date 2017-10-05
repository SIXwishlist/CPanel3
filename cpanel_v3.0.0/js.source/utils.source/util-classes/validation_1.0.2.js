
/*! Validate */

function Validate() {
}

Validate.empty = function(value){

    if ( value ) {
        return false;
    } else {
        return true;
    }
};

Validate.required = function(value){

    if ( value == null || value == "" ) {
        return false;
    } else {
        return true;
    }
};

Validate.fullname     = function(value){

    //var nameRegex = /^[a-zA-Z \.]+$/;
    var nameRegex = /^[A-Za-z\s]+$/;

    if ( value != null && value != "" ) {
        if ( value.match(nameRegex) ){
            return true;
        }
        
        return false;

    } else {
        return false;
    }

};

Validate.username = function(value){

    var usernameRegex = /^[a-zA-Z0-9]+$/;

    if ( value != null && value != "" ) {
        if ( value.match(usernameRegex) ){
            return true;
        }
        
        return false;

    } else {
        return false;
    }

};

// Use positive lookahead assertions:
//
// var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
//
// Without it, your current regex only matches that you have 6 to 16 valid characters, it doesn't validate that it has at least a number, and at least a special character. That's what the lookahead above is for.
//
//    (?=.*[0-9]) - Assert a string has at least one number;
//    (?=.*[!@#$%^&*]) - Assert a string has at least one special character.
//

Validate.password = function(value){

    var passwordRegex = /^[a-zA-Z0-9!@#$%^&*]{6,16}$/;

    if ( value != null && value != "" ) {
        if ( value.match(passwordRegex) ){
            return true;
        }
        
        return false;

    } else {
        return false;
    }

};

Validate.color = function(value){

    //var colorRegex = /#[a-fA-F0-9]{6}/;
    
    //var colorHexPattern = "^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$";
    var colorHexPattern = /#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/;

    //var colorPattern1 = /#[a-fA-F0-9]{6}/;
    //var colorPattern2 = /#[a-fA-F0-9]{3}/;

    if ( value != null && value != "" ) {
        
        //if ( value.match(colorRegex).length > 0 ){
        //    return true;
        //}
        
        if ( colorHexPattern.test(value) ){
            return true;
        }
        
        //if ( colorPattern1.test(value) ){
        //    return true;
        //}else if ( colorPattern2.test(value) ){
        //    return true;
        //}
        
        return false;

    } else {
        return false;
    }

};

Validate.numbers = function(value){

    var re = /^\d+$/;

    return re.test(value);
};

Validate.numbers = function(value){

    var re = /^\d+$/;

    return re.test(value);
};

Validate.phone = function(value){

    var re = /^[0-9\-\+]{0,}$/;

    return re.test(value);
};

Validate.email = function(email) { 
    //check http://stackoverflow.com/a/46181/11236

    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return re.test(email);
};

Validate.email_old = function(value){

    apos   = value.indexOf("@");
    dotpos = value.lastIndexOf(".");

    if (apos<1||dotpos-apos<2){
        return false;
    } else {
        return true;
    }

};

function arabicOnly(e){

    var unicode=e.charCode? e.charCode : e.keyCode

    if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
      if (( unicode<48 || unicode>57) && (unicode < 0x0600 || unicode > 0x06FF)) //if not a number or arabic
        return false //disable key press
    }
}