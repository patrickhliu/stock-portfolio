// jquery code to put cursor focus on the first text input field of a page

$(document).ready(function() {
    if ( $("input:text:visible:first").val() ==="" ) {
        $("input:text:visible:first").focus();  
    }   
}); 


