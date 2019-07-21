$(document).ready(function () {
    


    $(function() {
    
        ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } ); 
    });

    
    

});

