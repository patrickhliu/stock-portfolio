/**************************************************************************************************
script.js
    jQuery to automatically place mouse cursor on the first blank field of a form
***************************************************************************************************/

$(document).ready(function() {
    if ( $("input:visible:first").val() === "" ) {
         $("input:visible:first").focus();  
    } 
}); 


