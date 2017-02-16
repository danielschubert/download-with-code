jQuery(document).ready(function($) {

    // Code submission
    jQuery('#code_form').submit(function(e) {
        e.preventDefault(); // this disables the submit button so the user stays on the page

        // Validation
        if ( ! /([a-zA-Z0-9]){8}/.test( code ) ) {
          jQuery('#resp').html('Code is not Valid!');
        } else {

          data = {'code' : $('input[name=code]').val(),
                  'action' : 'submit_code',
                  'format' :  $('select[name=format]').val()
                };

          // ajax call
          $.ajax({
              type: "POST",
              dataType: "json",
              url: frontendajax.ajaxurl,
              data: data})
              .done(function(msg){
                        jQuery('#resp').html(msg.resp);
              })
              .fail( function(eins) { console.log("Error", eins);});
        }
    });
});
