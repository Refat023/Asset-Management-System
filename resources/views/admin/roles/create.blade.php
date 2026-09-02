@extends('master')

@section('content')
    <!-- Button trigger modal -->
    <div class="row">
        <div class="col-md-4">
            <div class="row m-auto p-1">
                <h4 class="text-white ">Role Managment </h4>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('roles.index') }}" class="btn btn-info">Back</a>

                            <div class="mt-3 mb-3">
                                <h3>Permission:</h3>
                                <form action="{{ route('permissions.store') }}" method="POST" class="d-flex mb-3" style="max-width: 500px;">
                                    @csrf
                                    <input type="text" name="name" class="form-control" placeholder="Add new permission" required>
                                    <button type="submit" class="btn btn-success ms-2">Add Permission</button>
                                </form>
                                <a href="{{ route('permissions.index') }}" class="btn btn-warning btn-sm mb-2">Manage Permission</a>
                            </div>

                            <form action="{{ route('roles_store') }}" Method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="text">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="mt-3 mb-3">
                                    <h3>Permission:</h3>
                                    @foreach ($permission as $permission)
                                        <label><input type="checkbox" name="permission[]" value="{{ $permission->name }}">{{ $permission->name }}</label><br/>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-info">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection