 <!-- View User Details -->
 <div class="modal fade" id="Viewuser{{ $user->id }}" tabindex="-1" aria-labelledby="ViewuserLabel{{ $user->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="ViewuserLabel{{ $user->id }}">User Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
               <div class="view">
                <table class="table">
                  {{-- <img src="/images/background4.jpg" alt="" class="img-thumbnail img-menu"> --}}
                    <tr>
                      <th scope="col">User ID</th>
                      <td>{{$user->id}}</td>
                    </tr>
                    <tr>
                      <th scope="col">First Name</th>
                      <td>{{$user->first_name}}</td>

                    </tr>
                    <tr>
                      <th scope="col">Last Name</th>
                      <td>{{$user->last_name}}</td>

                    </tr>
                      
                      <tr>
                        <th scope="col">Email</th>
                        <td>{{$user->email}}</td>
                      </tr>
                      <tr>
                        <th scope="col">Contact No.</th>
                        <td>{{$user->contact_number}}</td>
                      </tr>
                      
                      <tr>
                        <th scope="col">Address</th>
                      <td>{{$user->address}}</td>
                      </tr>
                      
                    
                  
                  
                </table>
                
               </div>
              </div>
              <div class="modal-footer">
                
              </div>
            </div>
          </div>
        </div>