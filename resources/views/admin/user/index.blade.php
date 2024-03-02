@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<h1 class="display-1">User</h1>
        <section class="User-section">
          
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
            <thead>
              <tr>
                <th scope="col">User ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last_name</th>
                <th scope="col">Email</th>
                <th scope="col">Contact</th>
                <th scope="col">Type of user</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
              <tr>
              
                <th scope="row">{{$user->id}}</th>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->contact_number}}</td>
                <td>{{ $user->role->name }}</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Viewuser{{ $user->id }}">
                            View
                        </button>
                  <!-- <button type="button" class="btn btn-secondary"><a href="edit.html">Edit</a></button> -->
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Edituser{{ $user->id }}">
                Edit
            </button>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
            </form>
                </td>
                
              </tr>
              @endforeach
            </tbody>
          </table>
        </section>
        @foreach($users as $user)
        
        @include('admin.user.viewuser', ['user' => $user])
        @include('admin.user.edituser', ['user' => $user])
    @endforeach
        
@endsection