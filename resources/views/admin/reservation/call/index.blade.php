@extends('layouts.admin')

@section('content')
<h1 class="display-1 mb-5">Client Status</h1>
        <div class="btn-position-status mb-5"><button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary">Add</button></div>
        <div class="status-container">
          <div class="status-user-info">
          
          <table class="table">

          @if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

          @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            
            <tr>
                <th scope="col">Reservation ID:</th>
                <td>{{$reservation->id}}</td>
            </tr>
                <th scope="col">Name:</th>
                <td>{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</td>
            <tr>

            </tr>
            <tr>
                <th scope="col">Contact No. :</th>
                <td>{{$reservation->user->contact_number}}</td>
            </tr>
            <tr>
                <th scope="col">Date of Event:</th>
                <td>{{$reservation->event_date}}</td>
            </tr>
            <tr>
                <th scope="col">Type of Event:</th>
                <td>
    @if ($reservation->reservationSelection)
        @if ($reservation->reservationSelection->choice == 'premade')
            @if ($reservation->premades && $reservation->premades->servicePackage && $reservation->premades->servicePackage->serviceSelection)
                {{ $reservation->premades->servicePackage->serviceSelection->services_category }}
            @endif
        @elseif ($reservation->reservationSelection->choice == 'customize')
            {{ $reservation->reservationSelection->categoryName }}
        @endif
    @endif
</td>
            </tr>   
            
          </table>
        </div>
          <div class="status-call" >
           
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Remarks</th>
                  </tr>
                </thead>

                <tbody >
                @if ($callStatuses)
                @foreach ($callStatuses as $call)
                  <tr>
                    <td>{{ $call->call_status_date }}</td>
                    <td>{{ $call->call_status_time }}</td>
                    <td>{{ $call->call_status }}</td>
                    <td>{{ $call->call_remarks }}</td>
                    
                  </tr>
                  @endforeach   
                  @endif
                </tbody>
              </table>
          </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('call-status.store', ['id' => $reservation->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
              <div class="modal-body">
               <div>
                <table class="table">
                    
                    <tr>
                        <th scope="col">Date:</th>
                        <td> <input type="date" name="call_status_date" class="form-control"></td>
                    </tr> 
                    <tr>
                        <th scope="col">time:</th>
                        <td> <input type="time" name="call_status_time" class="form-control"></td>
                    </tr>
                    <tr>
                        <th scope="col">Status:</th>
                        <td>
                            <select name="call_status" id="" class="form-select">
                                <option value=""></option>
                                <option value="Waiting">Waiting</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Approved">Approved</option>
                                <option value="Canceled">Canceled</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">Remarks:</th>
                        <td> <textarea class="form-control" name="call_remarks" aria-label="With textarea"></textarea></td>
                    </tr>
                  </table>
                
               </div>
              </div>
              <div class="modal-footer">
              
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        @endsection