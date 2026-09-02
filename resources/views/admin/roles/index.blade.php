@extends('master')

@section('content')
    <!-- Button trigger modal -->
    <div class="row">
        <div class="col-md-4">
            <div class="row m-auto p-1">
                <h4 class="text-white ">Role List </h4>

            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('roles.create') }}" class="btn btn-info">Create Role</a>
                            <a href="{{ route('permissions.index') }}" class="btn btn-warning">Manage Permission</a>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Permission</th>
                                    <th>Action</th>
                                </tr>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->email }}</td>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            @can('role-edit')
                                            <button class="border-0 bg-white"><a class="text-primary" href="{{route('roles_edit', $role->id)}}"><i
                                                        class="fa fa-edit " style="font-size:20px;"></a></i></button>
                                            @endcan

                                            @can('role-delete')
                                                
                                            <button class="border-0 bg-white"><a class="text-danger" href="{{route('destroy', $role->id)}}"><i
                                                        class="fa fa-trash " style="font-size:20px;"></a></i></button>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--display form end-->
    @endsection
