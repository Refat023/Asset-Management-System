@extends('master')
@section('content')

<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header">
                <h3>Update Waste Product Info</h3>
            </div>
            <div class="card-body">

                <form action="{{route('wastproduct_update')}}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label for="" class="form-label">Asset Tag</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="id">
                        <input type="text"   class="form-control" name="asset_tag" value ={{$wastproduct->asset_tag}} readonly></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Asset Type</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="asset_type">
                        <input type="text"   class="form-control" name="asset_type" value ={{$wastproduct->asset_type}} readonly></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Model</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="model">
                        <input type="text"  class="form-control" name="model" value ={{$wastproduct->model}} readonly></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Purchase Date</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="purchase_date">
                        <input type="date"  class="form-control" name="purchase_date" value ={{$wastproduct->purchase_date}} readonly></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Asset Sl No</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="asset_sl_no">
                        <input type="text"   class="form-control" name="asset_sl_no" value ={{$wastproduct->asset_sl_no}} readonly></input>
                    </div>
                    
                    <div class="mb-3">
                        <label for="" class="form-label">Description</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="description">
                        <input  class="form-control" name="description" value ={{$wastproduct->description}} ></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Sate</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="date">
                        <input type="date"  class="form-control" name="date" value ={{$wastproduct->date}}></input>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Note</label>
                        <input type="hidden" value="{{ $wastproduct->id }}" name="note">
                        <input type="text"  class="form-control" name="note" value ={{$wastproduct->note}}></input>
                    </div>
                    

                    <button class="btn btn-success" type="submit">submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
