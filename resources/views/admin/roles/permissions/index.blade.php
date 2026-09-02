@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="row m-auto p-1">
                <h4 class="text-white ">Permission Management</h4>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('roles.index') }}" class="btn btn-info">Back</a>
                            <a href="{{ route('permissions.create') }}" class="btn btn-success">Add Permission</a>

                            <table class="table table-striped table-bordered mt-3">
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ route('permissions.destroy', $permission->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
