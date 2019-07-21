$(function() {
   
    // WYSSWIG TEXT EDITOR 

    ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    } );

    
// CHECK BOXES JQUERY
   $('#select_all_boxes').click(function(event){ 
    
    if(this.checked){

        $('.checkBoxes').each(function(){
            this.checked = true; 
        });}
     else{
        $('.checkBoxes').each(function(){
            this.checked = false; 
        });

     }    


    });



   

 ///////////////// MODALS ////////////////////
 
//  categories 

    //    // Edit Category make model pop up and delete with password
    $(".edit_button").on('click', function(){

        // get the title from the delete button that opens the modals .. value and set it to var
        var cat_title = $(this).attr("value");

        // get the id like we did with the title but this time we use rel instead of the value  
        var cat_id = $(this).attr("rel"); 

        // echo this var in the modal title
        $("#modal_edit_title").text(cat_title);
        
        $("#EditCatModal").modal('show');

        // give id and title a value for the update query
        $("#old_cat_name").val(cat_title);
        $("#old_cat_id").val(cat_id);

        // give the value of the hidden input the id so that we can use it in the delete query as php was not fetching unique id 
            $("#give_edit_id").val(cat_id);


    });


     // DELETE Category make model pop up and delete with password
    $(".delete_button").on('click', function(){

            // get the title from the delete button that opens the modals .. value and set it to var
            var cat_title = $(this).attr("value");
        
            // get the id like we did with the title but this time we use rel instead of the value  
            var cat_id = $(this).attr("rel"); 
        
            // echo title var in the modal title
            $("#modal_delete_title").text(cat_title);
            
            $("#exampleModal").modal('show');
        
            // give the value of the hidden input the id so that we can use it in the delete query as php was not fetching unique id 
                $("#give_del_id").val(cat_id);

    
    });    
      
// posts 
        
        $(".delete_button").on('click', function(){

            // get the title from the delete button that opens the modals .. value and set it to var
             var post_id = $(this).attr("value");
            
            // get the id like we did with the title but this time we use rel instead of the value  
            var post_title = $(this).attr("rel"); 
            
            // echo title var in the modal title
            $("#post_modal_delete_id").text(post_id); 
            $("#post_modal_delete_title").text(post_title);
            
            $("#deleteModal").modal('show');
            
            // give the value of the hidden input the id so that we can use it in the delete query as php was not fetching unique id 
                $("#hidden_input").val(post_id);
            

            
        });        
         
                        
        // users
        $(".delete_user_modal_btn").on('click', function(){

            let id_to_delete = $(this).val();
            let user_pic = $(this).attr('rel');
            let username = $(this).attr('data');

            $('#username_modal').html(username);
            $('#user_pic_modal').attr('src', '../images/users/'+user_pic);

            $('#hidden_input').val(id_to_delete);
        
            $('#delete_user_modal').modal('show');


        });


        // comments (change status)
        $(".change_status_modal_btn").on('click', function(){

            let id_to_delete = $(this).val();
            let status = $(this).attr('data');
            
            $('#new_status_value').html(status);

            $('#hidden_input_id').val(id_to_delete);
            $('#hidden_input_status').val(status);
        
            $('#change_status_modal').modal('show');


        });




// comments
     $(".delete_modal_btn").on('click', function(){

        let id_to_delete = $(this).val();

        $('#hidden_input').val(id_to_delete);
       
        $('#delete_modal').modal('show');


     });


     // comments (change status)
     $(".change_status_modal_btn").on('click', function(){
    
        let id_to_delete = $(this).val();
        let status = $(this).attr('data');
        
        $('#new_status_value').html(status);

        $('#hidden_input_id').val(id_to_delete);
        $('#hidden_input_status').val(status);
       
        $('#change_status_modal').modal('show');


     });



});


