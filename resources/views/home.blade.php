@extends('master')

@section('content')
    <style>
        .company-link:hover h2 {
            color: #007bff !important;
            text-decoration: underline;
        }
    </style>
    </div>
    <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="container my-3">
            <div class="row g-3">

                <!-- Users -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#17c9c3; min-height:140px;">
                        <!-- Top content -->
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $userCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">Users</p>
                            </div>
                            <i class="fa fa-users text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <!-- Footer -->
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('users') }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Employees -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#e91e63; min-height:140px;">
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $employeeCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">Active Employees</p>
                            </div>
                            <i class="fa fa-id-card text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('employee_list') }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Assets -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#ff9800; min-height:140px;">
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $assetCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">All Assets</p>
                            </div>
                            <i class="fa fa-barcode text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('store') }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laptops -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#3f51b5; min-height:140px;">
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $laptopCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">Laptops</p>
                            </div>
                            <i class="fa fa-laptop text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('store', ['product_search' => 1]) }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Desktops -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#ff5722; min-height:140px;">
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $desktopCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">Desktops</p>
                            </div>
                            <i class="fa fa-desktop text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('store', ['product_search' => 2]) }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Printers -->
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="rounded-3 text-white shadow d-flex flex-column"
                        style="background-color:#009688; min-height:140px;">
                        <div class="p-3 flex-grow-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold m-0" style="font-size:2.2rem;">
                                    {{ $printerCount }}
                                </h2>
                                <p class="mb-0 fw-semibold" style="font-size:1.1rem;">Printers</p>
                            </div>
                            <i class="fa fa-print text-white" style="font-size:48px; opacity:0.2;"></i>
                        </div>
                        <div class="px-3 py-2 text-end"
                            style="background:rgba(0,0,0,0.1); border-bottom-left-radius:0.7rem; border-bottom-right-radius:0.7rem;">
                            <a href="{{ route('store', ['product_search' => 4]) }}" class="text-white fw-bold text-decoration-none">
                                view all <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /top tiles -->
{{-- 
        <div class="row">
        <div class="col-md-8 col-sm-12 ">
            <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                    <h2>Device Usage</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Settings 1</a>
                                <a class="dropdown-item" href="#">Settings 2</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="" style="width:100%">
                        <tr>
                            <th style="width:37%;">
                                <p>Top 5</p>
                            </th>
                            <th>
                                <div class="col-lg-7 col-md-7 col-sm-7 ">
                                    <p class="">Device</p>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 ">
                                    <p class="">Progress</p>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <canvas class="canvasDoughnut" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                            </td>
                            <td>
                                <table class="tile_info">
                                    <tr>
                                        <td>
                                            <p><i class="fa fa-square blue"></i>Laptop </p>
                                        </td>
                                        <td>95%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><i class="fa fa-square green"></i>Desktop </p>
                                        </td>
                                        <td>98%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><i class="fa fa-square purple"></i>Printer </p>
                                        </td>
                                        <td>100%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><i class="fa fa-square aero"></i>Scanner </p>
                                        </td>
                                        <td>100%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><i class="fa fa-square red"></i>Others </p>
                                        </td>
                                        <td>95%</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 ">

        </div>
    </div> --}}
        <br />


        <!-- BETTEX HK product summary start -->
        <div class="container">
            <div class="row">
                <!-- BETTEX HK LTD. -->
                @if (!empty($product_summary_bt))
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('stock_summary', ['company' => 2]) }}" style="text-decoration: none; cursor: pointer;" class="text-dark company-link">
                                <h2 class="h5" style="margin: 0; transition: color 0.3s;">BETTEX HK LTD.</h2>
                            </a>
                        </div>
                        <div class="card-body scrollable" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>Asset Name</th>
                                        <th>Total Asset</th>
                                        <th>Units</th>
                                        <th>Issue Qty</th>
                                        <th>Waste Product</th>
                                        <th>Stock Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_summary_bt as $product_summary)
                                        <tr>
                                            <td>{{ $product_summary->asset_type->product }}</td>
                                            <td>{{ $product_summary->TotalAssets }}</td>
                                            <td>{{ $product_summary->units->size }}</td>
                                            <td>{{ $product_summary->IssueQty }}</td>
                                            <td>{{ $product_summary->WastProduct }}</td>
                                            <td>{{ $product_summary->StockQty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- BHML INDUSTRIES LTD. -->
                @if (!empty($product_summary_bhml))
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('stock_summary', ['company' => 1]) }}" style="text-decoration: none; cursor: pointer;" class="text-dark company-link">
                                <h2 class="h5" style="margin: 0; transition: color 0.3s;">BHML INDUSTRIES LTD.</h2>
                            </a>
                        </div>
                        <div class="card-body scrollable" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>Asset Name</th>
                                        <th>Total Asset</th>
                                        <th>Units</th>
                                        <th>Issue Qty</th>
                                        <th>Waste Product</th>
                                        <th>Stock Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_summary_bhml as $product_summary_bhml)
                                        <tr>
                                            <td>{{ $product_summary_bhml->asset_type->product }}</td>
                                            <td>{{ $product_summary_bhml->TotalAssets }}</td>
                                            <td>{{ $product_summary_bhml->units->size }}</td>
                                            <td>{{ $product_summary_bhml->IssueQty }}</td>
                                            <td>{{ $product_summary_bhml->WastProduct }}</td>
                                            <td>{{ $product_summary_bhml->StockQty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- BETTEX PREMIUM -->
                @if (!empty($product_summary_bp))
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('stock_summary', ['company' => 3]) }}" style="text-decoration: none; cursor: pointer;" class="text-dark company-link">
                                <h2 class="h5" style="margin: 0; transition: color 0.3s;">BETTEX PREMIUM</h2>
                            </a>
                        </div>
                        <div class="card-body scrollable" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>Asset Name</th>
                                        <th>Total Asset</th>
                                        <th>Units</th>
                                        <th>Issue Qty</th>
                                        <th>Waste Product</th>
                                        <th>Stock Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_summary_bp as $product_summary_bp)
                                        <tr>
                                            <td>{{ $product_summary_bp->asset_type->product }}</td>
                                            <td>{{ $product_summary_bp->TotalAssets }}</td>
                                            <td>{{ $product_summary_bp->units->size }}</td>
                                            <td>{{ $product_summary_bp->IssueQty }}</td>
                                            <td>{{ $product_summary_bp->WastProduct }}</td>
                                            <td>{{ $product_summary_bp->StockQty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!-- BETTEX INDIA -->
                @if (!empty($product_summary_bt_ind))
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('stock_summary', ['company' => 4]) }}" style="text-decoration: none; cursor: pointer;" class="text-dark company-link">
                                <h2 class="h5" style="margin: 0; transition: color 0.3s;">UNIONTEX INDIA</h2>
                            </a>
                        </div>
                        <div class="card-body scrollable" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>Asset Name</th>
                                        <th>Total Asset</th>
                                        <th>Units</th>
                                        <th>Issue Qty</th>
                                        <th>Waste Product</th>
                                        <th>Stock Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_summary_bt_ind as $product_summarys)
                                        <tr>
                                            <td>{{ $product_summarys->asset_type->product }}</td>
                                            <td>{{ $product_summarys->TotalAssets }}</td>
                                            <td>{{ $product_summary_bp->units->size }}</td>
                                            <td>{{ $product_summarys->IssueQty }}</td>
                                            <td>{{ $product_summarys->WastProduct }}</td>
                                            <td>{{ $product_summarys->StockQty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if (!empty($product_summary_global_attire))
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('stock_summary', ['company' => $globalAttireCompanyId]) }}" style="text-decoration: none; cursor: pointer;" class="text-dark company-link">
                                    <h2 class="h5" style="margin: 0; transition: color 0.3s;">GLOBAL ATIRE</h2>
                                </a>
                            </div>
                            <div class="card-body scrollable" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>Asset Name</th>
                                            <th>Total Asset</th>
                                            <th>Units</th>
                                            <th>Issue Qty</th>
                                            <th>Waste Product</th>
                                            <th>Stock Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product_summary_global_attire as $product_summary)
                                            <tr>
                                                <td>{{ $product_summary->asset_type->product }}</td>
                                                <td>{{ $product_summary->TotalAssets }}</td>
                                                <td>{{ $product_summary->units->size }}</td>
                                                <td>{{ $product_summary->IssueQty }}</td>
                                                <td>{{ $product_summary->WastProduct }}</td>
                                                <td>{{ $product_summary->StockQty }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- BETTEX HK product summary end -->

        <!-- BHML product summary start -->

        <!-- BHML HK product summary end -->

    </div>
@endsection
