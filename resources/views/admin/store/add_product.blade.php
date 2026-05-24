@extends('master')
@section('content')
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card mt-2 mx-auto p-4 bg-light">
            <div class="card-body bg-light">
                <div class="container">
                    <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="controls">

                            <!-- Asset Type & Brand -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset Type *
                                            <span class="text-success" data-toggle="modal" data-target="#addAssetModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        </label>
                                        <select id="asset_type" name="asset_type" class="form-control select2" required>
                                            <option value="" selected disabled>-- Select Asset Type --</option>
                                            @foreach ($all_product_types as $product_type)
                                                <option value="{{ $product_type->id }}">{{ $product_type->product }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Brand *
                                            <span class="text-success" data-toggle="modal" data-target="#addBrandModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        </label>
                                        <select id="brand_id" name="brand_id" class="form-control select2" required>
                                            <option value="" selected disabled>-- Select Brand --</option>
                                            @foreach ($all_brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Model & Serial -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" name="model" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Serial</label>
                                        <input type="text" name="asset_sl_no" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Cost & Currency -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cost</label>
                                        <input type="number" name="cost" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control">
                                            <option value="" selected disabled>--Select Currency--</option>
                                            <option>BDT</option>
                                            <option>USD</option>
                                            <option>RMB</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Warranty & Durability -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Warranty</label>
                                        <input type="number" name="warrenty" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Durability</label>
                                        <select name="durablity" class="form-control">
                                            <option value="" selected disabled>--Select--</option>
                                            <option>Days</option>
                                            <option>Week</option>
                                            <option>Month</option>
                                            <option>Year</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity & Units -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="qty" class="form-control" value="1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Units <span class="text-danger">*</span></label>
                                        <span class="text-success" data-toggle="modal" data-target="#addUnitModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        <select name="units_id" class="form-control" required>
                                            <option value="" selected disabled>--Select Unit--</option>
                                            @foreach ($all_SizeMaseurment as $size)
                                                <option value="{{ $size->id }}">{{ $size->size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchase Date & Vendor -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase Date</label>
                                        <input type="date" name="purchase_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vendor *</label>
                                        <span class="text-success" data-toggle="modal" data-target="#addVendorModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        <select name="vendor" class="form-control select2" required>
                                            <option value="" selected disabled>--Select Vendor--</option>
                                            @foreach ($all_supplier as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Company & Location -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company *</label>
                                        <span class="text-success" data-toggle="modal" data-target="#addCompanyModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        <select name="company_id" class="form-control select2" required>
                                            <option value="" selected disabled>--Select Company--</option>
                                            @foreach ($all_company as $company)
                                                <option value="{{ $company->id }}">{{ $company->company }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="checkstatus" value="INSTOCK">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <span class="text-success" data-toggle="modal" data-target="#addLocationModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        <select name="department_id" class="form-control select2" required>
                                            <option value="" selected disabled>--Select Location--</option>
                                            @foreach ($all_departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <span class="text-success" data-toggle="modal" data-target="#addStatusModal"><i class="fa fa-plus" style="font-size:10px;"></i></span>
                                        <select name="status_id" class="form-control" required>
                                            <option value="" selected disabled>--Select Status--</option>
                                            @foreach ($all_status as $status)
                                                <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Challan Number</label>
                                        <input type="text" name="challan_no" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Note & Picture -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <input type="text" name="others" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Picture</label>
                                        <input type="file" name="picture" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-file-text"></i> Submit</button>
                                <button type="reset" class="btn btn-secondary"><i class="fa fa-undo"></i> Reset</button>
                                <a href="{{ route('store') }}" class="btn btn-info"><i class="fa fa-step-backward"></i> Back</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('.select2').select2(); // Initialize Select2
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endpush
