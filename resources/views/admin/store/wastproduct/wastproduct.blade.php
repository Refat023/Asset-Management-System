@extends('master')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-6 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto">
                @if (session('success_wast'))
                    <div class="container mt-3">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_wast') }}
                            <button type="button" class="fa fa-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="font-weight-bolder">Waste Product</h4>
                        <p class="mb-0">Enter all information for the waste product</p>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('wastproduct_store') }}" method="POST" enctype="multipart/form-data"
                            class="form-card">
                            @csrf
                            <div class="input-group input-group-outline mb-3">
                                <select id="asset_tag" name="asset_tag" class="form-control select2">
                                    <option value="">Select a Product</option>
                                    @foreach ($issued_products as $product)
                                        <option value="{{ $product->asset_tag }}" data-asset_tag="{{ $product->id }}">
                                            {{ $product->asset_tag }} - {{ $product->asset_type }} - {{ $product->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <input type="text" id="asset_type" class="form-control " value="" name="asset_type"
                                    readonly placeholder="Asset Type">
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <input type="text" id="model" class="form-control " value="" name="model"
                                    readonly placeholder="Model">
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <input type="text" id="purchase_date" class="form-control " value=""
                                    name="purchase_date" readonly placeholder="purchase_date">
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <input class="form-control" rows="3" id="asset_sl_no" name="asset_sl_no"
                                    placeholder="Serial No" required readonly></input>
                            </div>

                            <!-- Hidden: company id (stored in DB column `others`) -->
                            <input type="hidden" id="company_id_hidden" name="others">

                            <!-- Visible: company name -->
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" id="company_name" class="form-control" readonly placeholder="Company">
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <textarea class="form-control" rows="3" id="description" name="description" placeholder="description" required></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Date</label>
                                <input type="date" class="form-control" id="date" name="date" required
                                    placeholder="Date">
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <textarea class="form-control" rows="3" id="note" name="note" placeholder="Note"></textarea>
                            </div>

                            <button class="btn btn-lg btn-success btn-lg w-100 mt-4 mb-0">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const companies = @json($companies); // id => company name

        $(document).ready(function() {
            $('#asset_tag').on('change', function() {

                let asset_tag = $('#asset_tag option:selected').data("asset_tag");

                $.ajax({
                    url: "{{ route('search.product', '') }}/" + asset_tag,
                    success: function(result) {

                        $('#asset_type').val(result.data.asset_type);
                        $('#model').val(result.data.model);
                        $('#purchase_date').val(result.data.purchase_date);
                        $('#asset_sl_no').val(result.data.asset_sl_no);

                        let companyId = result.data.company_id;

                        // 👇 SHOW company name
                        $('#company_name').val(companies[companyId] ?? '');

                        // 👇 STORE company ID
                        $('#company_id_hidden').val(companyId);
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
