/**************************************************************************************************
script.js
    jQuery to automatically place mouse cursor on the first blank field of a form.
    This file does not include any client side form validation.
    This file is only used for troubleshooting server side validation.
***************************************************************************************************/
$(document).ready(function() {
    if ( $("input:first").val() === "" ) {
         $("input:first").focus();  
    } 
});
