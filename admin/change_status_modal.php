
<div class="modal fade change_status_modal" id="change_status_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Are you sure you want to change the status to <span id="new_status_value"> </span>? </h4>
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

                  <input type="hidden" id="hidden_input_id" name="id" type="text" value="">

                  <input type="hidden" id="hidden_input_status" name="status" type="text" value="">
                </div>    
          </div>
        </div>
        <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="change_status_submit" class="btn btn-warning">Change Status</button>

          </form>
        </div>
      </div>
    </div>
</div>
  

