<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
                <h4 class="modal-title text-center" id="deleteModalLabel">
                    Are you sure that you really want to delete post: <span id="post_modal_delete_id"> </span>  "<span id="post_modal_delete_title"> </span>"  ?
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
          <div class="container-fluid">
            <div class=" text-center"> <b> If you are then please enter the master password, confirm, then click delete</b></div> <br>
            <form action="" method="post">
                <div class="form-group col-sm-6">
                  <label for="my-input">Master Password</label>
                  <input class="form-control col-sm-6"  type="password" name="master_password"> 
                  <br>

                  <label for="my-input">Confirm Master Password</label>
                  <input class="form-control col-sm-6" type="password" name="master_password_confirm">

                  <input type="hidden" id="hidden_input" name="id_to_delete" type="text" value="">
                </div>    
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="delete_post_submit" class="btn btn-danger">Delete</button>
             </form>
          </div>
      </div>
    </div>
</div>
  

