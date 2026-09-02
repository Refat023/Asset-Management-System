@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="row m-auto p-1">
                <h4 class="text-white ">Edit Permission</h4>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('permissions.index') }}" class="btn btn-info">Back</a>
                            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group mt-3">
                                    <label for="name">Permission Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
