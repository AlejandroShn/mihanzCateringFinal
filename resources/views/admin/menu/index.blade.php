@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@if ($category === 'all')
<h1 class="display-1">Menu</h1>
    @else
    <h1 class="display-1">{{ $category }}</h1>
    @endif
    <div class="btn-position">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Addmenu">Add</button>
    </div>
    <div class="modal fade" id="Addmenu" tabindex="-1" aria-labelledby="AddmenuLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="AddmenuLabel">Add Menu</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('menu.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
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
                        <script>
                                $(document).ready(function () {
                                    $('#Addmenu').modal('show');
                                });
                            </script>
                    @endif
                            <tbody>
                                <tr>
                                    <th scope="col">Image</th>
                                    <td><input type="file" class="form-control" name="menus_image" id="inputGroupFile01"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Name</th>
                                    <td><input type="text" name="name" class="form-control" placeholder="Dish Name"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Type of Dish</th>
                                    <td>
                                        <select name="menu_selection_id" class="form-select">
                                            <option selected></option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->menu_category }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">Description</th>
                                    <td><textarea name="description" class="form-control" aria-label="With textarea"></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="col">Price</th>
                                    <td><input type="number" name="price" class="form-control" placeholder="Price"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Status</th>
                                    <td>
                                        <select name="status" class="form-select" aria-placeholder="Status of Dish">
                                            <option selected></option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">Serving</th>
                                    <td><input type="number" name="serving" class="form-control" placeholder="No. of serving"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-position">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
          <section class="Menu-section">
          <table class="table">
        <thead>
            <tr>
                <th scope="col">Menu ID</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>          
                <th scope="col">Serving</th>
                <th scope="col">Type of Dish</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                <tr>
                    <th scope="row">{{ $menu->id }}</th>
                    <td><img src="{{ asset('images/menu/' . $menu->menus_image) }}" alt="Menu Image" class="img-thumbnail"></td>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->price }}</td>
                    <td>{{ $menu->serving }}</td>
                    <td>{{ $menu->category_name }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Viewmenu{{ $menu->id }}">
                            View
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Editmenu{{ $menu->id }}">
                Edit
            </button>
            <form action="{{ route('menu.destroy', $menu->id) }}" method="post" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this menu item?')">Delete</button>
            </form>
                    </td>
                </tr>

                <!-- Include the modal view with data for each menu -->
          
            @endforeach
        </tbody>
    </table>
        </section>     
        
        @foreach($menus as $menu)
        
            @include('admin.menu.viewmenu', ['menu' => $menu])
            @include('admin.menu.editmenu', ['menu' => $menu])
        @endforeach


        @endsection