@extends('master')
@section('content')
    <style>
        /* Modern Store List Styling */
        .pagination-wrapper nav {
            margin-bottom: 0;
        }

        .form-select-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            height: auto;
        }

        .select2-container--bootstrap4 {
            width: 100% !important;
        }

        .custom-file-upload {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .custom-file-upload:hover {
            border-color: #2B7093;
            background-color: rgba(43, 112, 147, 0.05);
        }

        .page-header {
            background: linear-gradient(135deg, #343a40 0%, #495057 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--card-color);
        }

        .summary-card.total {
            --card-color: #007bff;
        }

        .summary-card.instock {
            --card-color: #28a745;
        }

        .summary-card.issued {
            --card-color: #ffc107;
        }

        .summary-card.maintenance {
            --card-color: #dc3545;
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--card-color);
            margin: 0;
        }

        .summary-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .summary-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            color: var(--card-color);
            opacity: 0.3;
        }

        .filter-panel {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-panel .card-header {
            background: transparent;
            border: none;
            padding: 0 0 1rem 0;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: ;
            border: none;
            color: white;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .btn-add {
            background-color: #17a2b8;
        }

        .btn-issue {
            background-color: #28a745;
        }

        .btn-return {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-transfer {
            background-color: #6f42c1;
        }

        .btn-maintenance {
            background-color: #fd7e14;
        }

        .btn-waste {
            background-color: #dc3545;
        }

        .btn-calculator {
            background-color: #6f42c1;
        }

        .btn-print {
            background-color: #28a745;
        }

        .btn-print:disabled {
            background-color: #6c757d;
            opacity: 0.6;
            cursor: not-allowed;
        }

        .smart-table {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .smart-table .table {
            margin-bottom: 0;
        }

        .smart-table .table thead th {
            background-color: #495057;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem 0.75rem;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .smart-table .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .smart-table .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .smart-table .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border: none;
            font-size: 0.85rem;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .status-instock {
            background-color: #d4edda;
            color: #155724;
        }

        .status-issued {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-maintenance {
            background-color: #fff3cd;
            color: #856404;
        }

        .action-buttons-cell {
            min-width: 120px;
            display: flex;
            gap: 0.10rem;
        }

        .table-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem 0.75rem;
            /* Bootstrap medium size padding */
            font-size: 1rem;
            /* Medium font size */
            border: none;
            background: none;
            cursor: pointer;
        }

        .table-action-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }

        .asset-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .asset-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            /* Better min size */
            gap: 1.2rem;
            /* Slightly more spacing between fields */
            margin-bottom: 1rem;
            align-items: end;
            /* Aligns inputs/selects nicely on the bottom */
        }


        .dropdown-menu {
            padding: 0.5rem 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #495057;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #2B7093;
        }

        .dropdown-item i {
            font-size: 1rem;
            width: 1.5rem;
            text-align: center;
        }

        .btn-group .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        .input-group-sm {
            height: 31px;
        }

        .advanced-filters {
            border-top: 1px solid #dee2e6;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .filter-row label {
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
            color: #495057;
        }

        .filter-row input,
        .filter-row select {
            height: 36px;
            /* Forces all inputs/selects to have same height */
            line-height: 36px;
        }

        .filter-actions {
            display: flex;
            align-items: end;
        }

        .filter-actions button {
            width: 100%;
        }

        .form-control-sm,
        .form-select-sm {
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            transition: all 0.3s ease;
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary.btn-sm {
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .form-control-sm:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            transform: translateY(-2px);
        }


        .asset-image {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .export-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Modal Improvements */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: #26B99A;
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-floating>.form-control,
        .form-floating>.form-select {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating>label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity .1s ease-in-out, transform .1s ease-in-out;
        }

        /* Label Preview Styles */
        .label-preview {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .preview-label {
            border: 1px solid #ccc;
            background: white;
            font-family: Arial, sans-serif;
            margin: 0 auto;
            position: relative;
        }

        .small-label {
            width: 160px;
            height: 80px;
            padding: 4px;
            font-size: 8px;
        }

        .medium-label {
            width: 240px;
            height: 160px;
            padding: 8px;
            font-size: 10px;
        }

        .large-label {
            width: 320px;
            height: 240px;
            padding: 12px;
            font-size: 12px;
        }

        .label-header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .label-content {
            flex: 1;
        }

        .asset-tag {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 4px;
        }

        .asset-info {
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .label-codes {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
            border-top: 1px solid #ddd;
            padding-top: 4px;
        }

        .qr-placeholder,
        .barcode-placeholder {
            border: 1px solid #ccc;
            background: #f5f5f5;
            text-align: center;
            font-size: 8px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-placeholder {
            width: 30px;
            height: 30px;
        }

        .barcode-placeholder {
            height: 20px;
            flex: 1;
            margin-left: 5px;
            letter-spacing: 1px;
        }

        /* Selected Assets List */
        .selected-asset-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 5px;
            background-color: #f8f9fa;
        }

        .selected-asset-info {
            flex: 1;
        }

        .selected-asset-tag {
            font-weight: bold;
            color: #495057;
        }

        .selected-asset-details {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .remove-asset-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            padding: 2px 5px;
        }

        .remove-asset-btn:hover {
            color: #c82333;
            background-color: #fff5f5;
            border-radius: 3px;
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .print-labels,
            .print-labels * {
                visibility: visible;
            }

            .print-labels {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-label {
                page-break-inside: avoid;
                border: 1px solid #000;
                margin: 5mm;
                padding: 2mm;
                font-family: Arial, sans-serif;
            }

            .print-label-small {
                width: 50mm;
                height: 25mm;
                font-size: 6px;
            }

            .print-label-medium {
                width: 76mm;
                height: 51mm;
                font-size: 8px;
            }

            .print-label-large {
                width: 101mm;
                height: 76mm;
                font-size: 10px;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                text-align: center;
            }

            .action-buttons {
                justify-content: center;
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }

            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .smart-table {
                font-size: 0.75rem;
                overflow-x: auto;
            }

            .smart-table .table {
                min-width: 1200px;
            }

            .smart-table .table thead th,
            .smart-table .table tbody td {
                padding: 0.4rem 0.2rem;
                white-space: nowrap;
            }


            .table-action-btn {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                margin-bottom: 5px;
            }
        }

        /* Filter Panel Button Styling */
        .btn-group .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            height: 31px;
        }

        .btn-group .btn i {
            font-size: 0.875rem;

        }

        @media (max-width: 576px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .filter-panel {
                padding: 1rem;
            }
        }

        /* Column Selector Styles */
        #columnDropdownMenu {
            min-width: 280px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .column-checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s ease;
            cursor: pointer;
            margin-bottom: 0.25rem;
        }

        .column-checkbox-item:hover {
            background-color: #f8f9fa;
        }

        .column-checkbox-item input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .column-checkbox-item label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 0.875rem;
            flex: 1;
        }

        .column-checkbox-item.essential label {
            font-weight: 600;
            color: #007bff;
        }

        .column-checkbox-item.essential {
            background-color: #f0f8ff;
            border-left: 3px solid #007bff;
        }

        /* Prevent dropdown from closing when clicking inside */
        #columnDropdownMenu {
            pointer-events: auto;
        }

        .dropdown-item {
            padding: 0;
        }

        .dropdown-item:hover {
            background-color: transparent;
        }

        .form-check {
            position: relative;
            display: block;
            padding-left: 1.25rem;
        }

        button,
        input {
            overflow: visible;
        }
    </style>



    <div class="container">
        {{-- Issue Success --}}
        @if (session('issue_success'))
            <div class="alert alert-warning alert-dismissible fade show d-flex justify-content-between align-items-center"
                role="alert">
                <span>{{ session('issue_success') }}</span>
                <button type="button" class="border-0 bg-warning text-white fw-bold px-2 rounded" data-bs-dismiss="alert"
                    aria-label="Close">X</button>
            </div>
        @endif
        <!-- Page Header -->
        <div class="card mb-2" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-header" style="background-color: #f8f9fa; border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 text-dark">
                    <span class="">Asset List</span>
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('add_product') }}" class="action-btn btn-add">
                            <i class="fa fa-plus"></i> Add Asset
                        </a>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#depreciationModal">
                            <i class="fa fa-calculator"></i> Depreciation Calculator
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" id="bulkPrintBtn" data-toggle="modal"
                            data-target="#bulkLabelModal">
                            <i class="fa fa-print"></i> Print Labels (<span id="selectedCount">0</span>)
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('store_export', ['type' => 'xlsx']) }}">
                                        <i class="fa fa-file-excel-o text-success me-2"></i> Excel (XLSX)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('export', ['type' => 'csv']) }}">
                                        <i class="fa fa-file-text-o text-info me-2"></i> CSV File
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('export', ['type' => 'xls']) }}">
                                        <i class="fa fa-file-excel-o text-success me-2"></i> Excel 97-2003
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form action="" method="GET" id="filterForm">
                    <!-- Action Buttons -->
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Search Assets</label>
                            <input type="search" class="form-control form-control-sm" name="search"
                                placeholder="Search by asset tag, model, brand..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Asset Type</label>
                            <select name="product_search" id="product_search" class="form-control form-control-sm select2-filter">
                                <option value="">All Types</option>
                                @foreach ($all_product_types as $Type)
                                    <option value="{{ $Type->id }}"
                                        {{ request('product_search') == $Type->id ? 'selected' : '' }}>
                                        {{ $Type->product }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Company</label>
                            <select name="company_filter" id="company_filter" class="form-control form-control-sm select2-filter">
                                <option value="">All Companies</option>
                                @foreach ($all_company as $allcompany)
                                    <option value="{{ $allcompany->id }}"
                                        {{ request('company_filter') == $allcompany->id ? 'selected' : '' }}>
                                        {{ $allcompany->company }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-search"></i> Apply Filters
                            </button>
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="clearFilters()">
                                <i class="fa fa-refresh"></i> Clear All
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showAllColumns()">
                            <i class="fa fa-eye"></i> Show All
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                id="columnDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-list"></i> Select Columns
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="columnDropdown"
                                id="columnDropdownMenu">
                                <!-- Column checkboxes will be generated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="smart-table">
                    @if (session('delete_employee'))
                        <div class="alert alert-success">{{ session('delete_employee') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th data-column="checkbox" data-essential="true">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th data-column="sl" data-essential="true">SL</th>
                                        <th data-column="status" data-essential="true">STATUS</th>
                                        <th data-column="checkstatus">CHECKSTATUS</th>
                                        <th data-column="asset_tag" data-essential="true">ASSET TAG</th>
                                        <th data-column="asset_type" data-essential="true">ASSET TYPE</th>
                                        <th data-column="model">MODEL</th>
                                        <th data-column="brand_id">BRAND</th>
                                        <th data-column="description">DESCRIPTION</th>
                                        <th data-column="asset_sl_no">ASSET SL No</th>
                                        <th data-column="qty">QTY</th>
                                        <th data-column="units_id">UNITS</th>
                                        <th data-column="warrenty">WARRENTY</th>
                                        <th data-column="durablity">DURABLITY</th>
                                        <th data-column="cost">COST</th>
                                        <th data-column="currency">CURRENCY</th>
                                        <th data-column="vendor">VENDOR</th>
                                        <th data-column="purchase_date">PURCHASE DATE</th>
                                        <th data-column="challan_no">CHALLAN NO</th>
                                        <th data-column="status_name">CONDITION</th>
                                        <th data-column="company_id">COMPANY</th>
                                        <th data-column="others">OTHERS</th>
                                        <th data-column="balance">Balance</th>
                                        <th data-column="picture">PICTURE</th>
                                    </tr>
                                </thead>

                                <tbody style="height: 5px !important; overflow: scroll;">
                                    @foreach ($stores as $key => $store)
                                        <tr>
                                            <td data-column="checkbox">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input asset-checkbox"
                                                        data-asset-id="{{ $store->id }}"
                                                        data-asset-tag="{{ e($store->asset_tag) }}"
                                                        data-asset-type="{{ e($store->rel_to_ProductType->product ?? 'N/A') }}"
                                                        data-model="{{ e($store->model ?? '') }}"
                                                        data-brand="{{ e($store->rel_to_brand->brand_name ?? 'N/A') }}"
                                                        data-company="{{ e($store->others ?? ($store->rel_to_Company->company ?? 'N/A')) }}">
                                                </div>
                                            </td>
                                            <td data-column="sl">{{ $key + 1 }}</td>
                                            <td data-column="status" id="action-btn-{{ $store->id }}">
                                                @if ($store->checkstatus === 'INSTOCK')
                                                    <button class="btn btn-outline-success btn-sm issue-btn"
                                                        data-bs-toggle="modal" data-bs-target="#issueModal"
                                                        data-id="{{ $store->id }}"
                                                        data-asset-tag="{{ $store->asset_tag }}"
                                                        data-asset-type="{{ $store->rel_to_ProductType->product }}"
                                                        data-model="{{ $store->model }}"
                                                        data-company="{{ $store->rel_to_Company->company ?? '' }}">
                                                        INSTOCK
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-primary btn-sm return-btn"
                                                        data-bs-toggle="modal" data-bs-target="#returnModal"
                                                        data-issue-id="{{ $store->rel_to_issue->id ?? '' }}"
                                                        {{-- issue id --}} data-store-id="{{ $store->id }}"
                                                        data-asset-tag="{{ $store->asset_tag }}"
                                                        data-asset-type="{{ $store->rel_to_ProductType->product }}"
                                                        data-model="{{ $store->model }}"
                                                        data-company="{{ $store->rel_to_Company->company ?? '' }}">
                                                        ISSUED
                                                    </button>
                                                @endif
                                            </td>
                                            <td data-column="checkstatus" style="background-color: #feefe6;">
                                                @if ($store->checkstatus == 'INSTOCK')
                                                    <span
                                                        class="badge bg-success text-white">{{ $store->checkstatus }}</span>
                                                @elseif ($store->checkstatus == 'MAINTENANCE')
                                                    <span
                                                        class="badge bg-warning text-white">{{ $store->checkstatus }}</span>
                                                @elseif ($store->checkstatus == 'Wast Products')
                                                    <span
                                                        class="badge bg-danger text-white">{{ $store->checkstatus }}</span>
                                                @elseif ($store->checkstatus == 'DELETE')
                                                    <span
                                                        class="badge bg-danger text-white">{{ $store->checkstatus }}</span>
                                                @elseif ($store->checkstatus == 'ARCHIVE')
                                                    <span
                                                        class="badge bg-secondary text-white">{{ $store->checkstatus }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-primary text-white">{{ $store->checkstatus }}</span>
                                                @endif

                                                @if ($store->isBorrowed())
                                                    @php
                                                        $borrowInfo = $store->getBorrowingInfo();
                                                    @endphp
                                                    <br><span class="badge bg-warning text-dark mt-1"
                                                        title="This asset is currently borrowed by {{ $borrowInfo->to_company }}">
                                                        <i class="fa fa-handshake"></i> BORROWED
                                                    </span>
                                                    <br><small class="text-muted">by {{ $borrowInfo->to_company }}</small>
                                                @endif
                                            </td>
                                            <td data-column="asset_tag">
                                                <a href="{{ route('store_info', $store->id) }}">
                                                    {{ $store->asset_tag }}
                                                </a>
                                            </td>
                                            <td data-column="asset_type">{{ $store->rel_to_ProductType->product ?? '' }}
                                            </td>
                                            <td data-column="model">{{ $store->model }}</td>
                                            <td data-column="brand_id">{{ $store->rel_to_brand->brand_name }}</td>
                                            <td data-column="description">{{ $store->description }}</td>
                                            <td data-column="asset_sl_no">{{ $store->asset_sl_no }}</td>
                                            <td data-column="qty">{{ $store->qty }}</td>
                                            <td data-column="units_id">{{ $store->rel_to_SizeMaseurment->size }}</td>
                                            <td data-column="warrenty">{{ $store->warrenty ?? '' }}</td>
                                            <td data-column="durablity">{{ $store->durablity ?? '' }}</td>
                                            <td data-column="cost">{{ $store->cost }}</td>
                                            <td data-column="currency">{{ $store->currency }}</td>
                                            <td data-column="vendor">{{ $store->rel_to_Supplier->supplier_name ?? '' }}
                                            </td>
                                            <td data-column="purchase_date">{{ $store->purchase_date }}</td>
                                            <td data-column="challan_no">{{ $store->challan_no }}</td>
                                            <td data-column="status_name">{{ $store->rel_to_Status->status_name ?? '' }}
                                            </td>
                                            <td data-column="company_id">{{ $store->rel_to_Company->company }}</td>
                                            <td data-column="others">{{ $store->others }}</td>
                                            <td data-column="balance">{{ $store->balance ?? '' }}</td>
                                            <td data-column="picture">
                                                @if ($store->picture)
                                                    <img width="40" height="15"
                                                        src="{{ asset('uploads/store/' . $store->picture) }}"
                                                        alt="Picture">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>

                            </div>

                        </div>
                        <!--Pagination Start-->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="d-flex align-items-center">
                                @if ($stores instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <span class="text-muted me-3">
                                        Showing {{ $stores->firstItem() }} to {{ $stores->lastItem() }} of
                                        {{ $stores->total() }} assets
                                    </span>
                                @else
                                    <span class="text-muted me-3">Showing all {{ $stores->count() }} assets</span>
                                @endif

                                <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center">
                                    <select name="per_page" class="form-select form-select-sm"
                                        onchange="this.form.submit()" style="width: auto;">
                                        @php $options = [10, 25, 50, 100, 'all']; @endphp
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}"
                                                {{ session('asset_per_page', 10) == $option ? 'selected' : '' }}>
                                                {{ is_numeric($option) ? $option : 'All' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-muted ms-2">per page</span>

                                    <!-- Preserve all filters -->
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="product_search" value="{{ request('product_search') }}">
                                    <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                    <input type="hidden" name="company_filter" value="{{ request('company_filter') }}">
                                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                    <input type="hidden" name="cost_min" value="{{ request('cost_min') }}">
                                    <input type="hidden" name="cost_max" value="{{ request('cost_max') }}">
                                </form>
                            </div>

                            @if ($stores instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="pagination-wrapper">
                                    {{ $stores->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                        <!--Pagination end-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issue Modal -->
    <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="issueModalLabel">
                        Issue Asset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('issue.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="issue_id" name="store_id" value="">

                        <div class="row g-3 ">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Asset Tag</label>
                                <input type="text" id="asset_tag" class="form-control" name="asset_tag" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Asset Type</label>
                                <input type="text" id="asset_type" class="form-control" name="asset_type" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model</label>
                                <input type="text" id="model" class="form-control" name="model" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" id="company" class="form-control" name="others" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Select Employee *</label>
                                <select id="empl_id" name="emp_id" class="form-control select2" required>
                                    <option value="" selected disabled>-- Select Employee --</option>
                                    @foreach ($employee as $emp)
                                        <option value="{{ $emp->emp_id }}" data-emp_id="{{ $emp->id }}">
                                            {{ $emp->emp_id }} - {{ $emp->emp_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Employee Name</label>
                                <input type="text" id="emp_name" class="form-control" name="emp_name" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" id="designation_id" class="form-control" name="designation_id"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" id="department_id" class="form-control" name="department_id"
                                    readonly>
                            </div>
                            <div class="col-md-6 ">
                                <label class="form-label">Issue Date *</label>
                                <input type="date" id="issue_date" class="form-control" name="issue_date" required>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check me-2"></i>Issue Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Return Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('return_update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="return_id" name="store_id" value="">

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Asset Tag</label>
                                <input type="text" id="return_asset_tag" class="form-control" name="asset_tag"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Asset Type</label>
                                <input type="text" id="return_asset_type" class="form-control" name="asset_type"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model</label>
                                <input type="text" id="return_model" class="form-control" name="model" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company</label>
                                <input type="text" id="return_company" class="form-control" name="others" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="return_date" required>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-check me-2"></i> Return Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Clone Modal removed - now using direct link to edit page -->

    <!-- Depreciation Calculator Modal -->
    <div class="modal fade" id="depreciationModal" tabindex="-1" aria-labelledby="depreciationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #5a359a 100%);">
                    <h5 class="modal-title text-white" id="depreciationModalLabel">
                        <i class="fa fa-calculator me-2"></i>Asset Depreciation Calculator
                    </h5>
                    <button type="button" class="close btn border-0 bg-transparent text-white" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Depreciation Calculator:</strong> Calculate asset depreciation using different methods.
                        Select an asset from the table or enter custom values.
                    </div>

                    <div class="row">
                        <!-- Asset Selection -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-cubes me-2"></i>Asset Selection</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Select Asset</label>
                                        <select id="depreciation_asset" class="form-control">
                                            <option value="">Select an Asset</option>
                                            @foreach ($stores as $asset)
                                                <option value="{{ $asset->id }}"
                                                    data-asset_tag="{{ $asset->asset_tag }}"
                                                    data-cost="{{ $asset->cost ?? 0 }}"
                                                    data-purchase_date="{{ $asset->purchase_date }}"
                                                    data-asset_type="{{ $asset->rel_to_ProductType->product ?? '' }}"
                                                    data-model="{{ $asset->model }}"
                                                    data-company="{{ $asset->company }}">
                                                    {{ $asset->asset_tag }} -
                                                    {{ $asset->rel_to_ProductType->product ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Asset Tag</label>
                                        <input type="text" id="dep_asset_tag" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Asset Type</label>
                                        <input type="text" id="dep_asset_type" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <input type="text" id="dep_model" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Company</label>
                                        <input type="text" id="dep_company" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Parameters -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-cogs me-2"></i>Calculation Parameters</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Original Cost <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" id="dep_original_cost" class="form-control"
                                                step="0.01" min="0" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Salvage Value</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" id="dep_salvage_value" class="form-control"
                                                step="0.01" min="0" value="0">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Useful Life <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" id="dep_useful_life" class="form-control"
                                                min="1" required>
                                            <span class="input-group-text">years</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Purchase Date</label>
                                        <input type="date" id="dep_purchase_date" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Depreciation Method</label>
                                        <select id="dep_method" class="form-control">
                                            <option value="straight_line">Straight Line</option>
                                            <option value="declining_balance">Declining Balance</option>
                                            <option value="double_declining">Double Declining Balance</option>
                                            <option value="sum_of_years">Sum of Years' Digits</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="dep_rate_container" style="display: none;">
                                        <label class="form-label">Depreciation Rate (%)</label>
                                        <input type="number" id="dep_rate" class="form-control" step="0.01"
                                            min="0" max="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Results -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-chart-line me-2"></i>Depreciation Results</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Current Age</label>
                                        <div class="input-group">
                                            <input type="text" id="dep_current_age" class="form-control" readonly>
                                            <span class="input-group-text">years</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Total Depreciation</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" id="dep_total_depreciation" class="form-control"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Current Book Value</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" id="dep_book_value" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Annual Depreciation</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" id="dep_annual_depreciation" class="form-control"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Depreciation Rate</label>
                                        <div class="input-group">
                                            <input type="text" id="dep_percentage" class="form-control" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary w-100"
                                        onclick="calculateDepreciation()">
                                        <i class="fa fa-calculator me-2"></i>Calculate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Depreciation Schedule Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-table me-2"></i>Depreciation Schedule</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped" id="depreciation_schedule">
                                            <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Beginning Value</th>
                                                    <th>Depreciation</th>
                                                    <th>Accumulated Depreciation</th>
                                                    <th>Ending Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <i class="fa fa-info-circle"></i> Enter parameters and click
                                                        Calculate to see depreciation schedule
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times me-2"></i>Close
                    </button>
                    <button type="button" class="btn btn-success" onclick="exportDepreciationSchedule()">
                        <i class="fa fa-download me-2"></i>Export Schedule
                    </button>
                    <button type="button" class="btn btn-info" onclick="resetCalculator()">
                        <i class="fa fa-refresh me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bulk Label Print Modal -->
    <div class="modal fade" id="bulkLabelModal" tabindex="-1" aria-labelledby="bulkLabelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title text-white" id="bulkLabelModalLabel">
                        <i class="fa fa-print me-2"></i>Bulk Label Print
                    </h5>
                    <button type="button" class="close btn border-0 bg-transparent text-white" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Selected Assets:</strong> You have selected <span id="modalSelectedCount">0</span> assets
                        for label printing.
                    </div>

                    <!-- Label Configuration -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-cog me-2"></i>Print Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Label Size</label>
                                        <select class="form-control" id="labelSize">
                                            <option value="simple" selected>Simple QR (2" x 2") - Just QR + Asset Tag
                                            </option>
                                            <option value="small">Small (2" x 1")</option>
                                            <option value="medium">Medium (3" x 2")</option>
                                            <option value="large">Large (4" x 3")</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Copies per Asset</label>
                                        <input type="number" class="form-control" id="copiesCount" value="1"
                                            min="1" max="10">
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="includeQR" checked>
                                            <label class="form-check-label" for="includeQR">Include QR Code</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="includeBarcode" checked>
                                            <label class="form-check-label" for="includeBarcode">Include Barcode</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Additional Text</label>
                                        <textarea class="form-control" id="additionalText" rows="2" placeholder="Optional additional text for labels"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-eye me-2"></i>Label Preview</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="label-preview" id="labelPreview">
                                        <div class="preview-label simple-label">
                                            <div class="simple-qr-placeholder"
                                                style="width: 120px; height: 120px; border: 2px dashed #ccc; margin: 10px auto; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #999;">
                                                QR</div>
                                            <div class="simple-asset-tag"
                                                style="text-align: center; font-weight: bold; font-size: 16px; margin-top: 10px;"
                                                id="previewAssetTag">BETTEX-0001</div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-2">
                                        <small>This is how your labels will appear</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Assets List -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fa fa-list me-2"></i>Selected Assets</h6>
                                </div>
                                <div class="card-body">
                                    <div class="selected-assets-list" id="selectedAssetsList">
                                        <div class="text-muted text-center">
                                            <i class="fa fa-info-circle"></i> No assets selected
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-info" onclick="previewLabels()">
                        <i class="fa fa-eye me-2"></i>Preview All
                    </button>
                    <button type="button" class="btn btn-success" onclick="printLabels()">
                        <i class="fa fa-print me-2"></i>Print Labels
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- JsBarcode Library for Barcodes -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Custom matcher function for select2 search
        function matchCustom(params, data) {
            // If there are no search terms, return all data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Match the text
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }

            // Return null if the term does not match
            return null;
        }

        $(document).ready(function() {
            // Initialize Select2 for filter dropdowns with search
            $('#product_search').select2({
                placeholder: "Search Asset Type",
                allowClear: true,
                width: '100%',
                matcher: matchCustom
            });

            $('#company_filter').select2({
                placeholder: "Search Company",
                allowClear: true,
                width: '100%',
                matcher: matchCustom
            });

            // Initialize all select2-filter elements
            $('.select2-filter').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%',
                matcher: matchCustom
            });

            $('#empl_id').select2({
                placeholder: "Select an Employee",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#issueModal')
            });
            // Issue modal data population
            $('.issue-btn').on('click', function() {
                const ds = this.dataset;
                $('#issue_id').val(ds.id);
                $('#asset_tag').val(ds.assetTag); // from stores.asset_tag
                $('#asset_type').val(ds.assetType); // from stores.asset_type
                $('#model').val(ds.model); // from stores.model
                $('#company').val(ds.company); // use "others" field instead of company_id
            });

            // Return modal data population
            $('.return-btn').on('click', function() {
                const ds = this.dataset;
                $('#return_issue_id').val(ds.issueId);
                $('#return_store_id').val(ds.storeId);
                $('#return_asset_tag').val(ds.assetTag);
                $('#return_asset_type').val(ds.assetType);
                $('#return_model').val(ds.model);
                $('#return_company').val(ds.company);
            });

            // Employee selection handler
            $('#empl_id').on('change', function() {
                let empl_id = $('#empl_id option:selected').data("emp_id");
                if (empl_id) {
                    $.ajax({
                        url: "{{ route('search.empl', '') }}/" + empl_id,
                        success: function(result) {
                            $('#emp_name').val(result.data.emp_name);
                            $('#designation_id').val(result.data.designation_id);
                            $('#department_id').val(result.data.department_id);
                            $('#phone_number').val(result.data.phone_number);
                            $('#email').val(result.data.email);
                        }
                    });
                }
            });
        });
        // Clone modal data population using event delegation
        $(document).on('click', '.clone-btn', function(e) {
            e.preventDefault();
            console.log('Clone button clicked'); // Debug log

            const btn = this;

            // Log all data attributes to debug
            console.log('All data attributes:', btn.dataset);

            // Get data attributes using getAttribute
            const originalId = btn.getAttribute('data-id') || '';
            const originalCode = btn.getAttribute('data-asset-code') || ''; // updated
            const productType = btn.getAttribute('data-product-type') || ''; // updated
            const modelName = btn.getAttribute('data-model-name') || ''; // updated
            const brandId = btn.getAttribute('data-brand-id') || '';
            const qty = btn.getAttribute('data-qty') || '1';
            const unitsId = btn.getAttribute('data-units-id') || '';
            const warrenty = btn.getAttribute('data-warrenty') || '';
            const durablity = btn.getAttribute('data-durablity') || '';
            const cost = btn.getAttribute('data-cost') || '';
            const currency = btn.getAttribute('data-currency') || 'USD';
            const vendorId = btn.getAttribute('data-vendor-id') || '';
            const statusId = btn.getAttribute('data-status-id') || '';
            const companyId = btn.getAttribute('data-company-id') || '';
            const description = btn.getAttribute('data-description') || '';
            const others = btn.getAttribute('data-others') || '';

            console.log('Extracted data:');
            console.log('- Original Code:', originalCode);
            console.log('- Product Type:', productType);
            console.log('- Model Name:', modelName);
            console.log('- Brand ID:', brandId);
            console.log('- Company ID:', companyId);

            // Generate new asset code based on original
            const timestamp = Date.now().toString().slice(-4);
            const newCode = originalCode ? originalCode + '_COPY_' + timestamp : 'CLONE_' + timestamp;

            // Populate form fields
            console.log('Populating form fields...');

            // Set original asset ID for cloning
            $('#clone_original_id').val(originalId);

            // Basic text inputs
            $('#clone_asset_code').val(newCode); // updated
            $('#clone_model_name').val(modelName); // updated
            $('#clone_serial').val(''); // Clear for uniqueness
            $('#clone_qty').val(qty);
            $('#clone_warrenty').val(warrenty);
            $('#clone_durablity').val(durablity);
            $('#clone_cost').val(cost);
            $('#clone_currency').val(currency);
            $('#clone_purchase_date').val(''); // Clear for new entry
            $('#clone_challan').val(''); // Clear for uniqueness
            $('#clone_description').val(description);
            $('#clone_others').val(others);

            // Select dropdowns
            $('#clone_product_type').val(productType).trigger('change'); // updated
            $('#clone_brand').val(brandId).trigger('change');
            $('#clone_units').val(unitsId).trigger('change');
            $('#clone_vendor').val(vendorId).trigger('change');
            $('#clone_status').val(statusId).trigger('change');
            $('#clone_company').val(companyId).trigger('change');

            console.log('Form fields populated');
            console.log('Asset code field value:', $('#clone_asset_code').val());
            console.log('Model name field value:', $('#clone_model_name').val());
            console.log('Product type field value:', $('#clone_product_type').val());

            // Add clone notification
            const notification = `<div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <strong>Cloning:</strong> Asset Code and Serial Number have been cleared/modified for uniqueness.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;

            // Remove any existing notifications and add new one
            $('#cloneModal .alert-warning').remove();
            $('#cloneModal .alert-info').after(notification);

            console.log('Data populated successfully'); // Debug log
        });


        // Initialize Select2 for clone modal
        try {
            $('#clone_product_type, #clone_brand, #clone_units, #clone_vendor, #clone_status, #clone_company').select2({
                width: '100%',
                placeholder: 'Select an option'
            });
        } catch (e) {
            console.log('Select2 initialization skipped:', e);
        }

        // Auto-generate asset code suggestion
        $('#clone_asset_code').on('blur', function() { // updated field
            const value = $(this).val();
            if (value && !value.includes('_COPY_')) {
                $(this).val(value + '_COPY');
            }
        });

        // Form validation before submit
        $('#cloneForm').on('submit', function(e) {
            const assetCode = $('#clone_asset_code').val().trim(); // updated
            const productType = $('#clone_product_type').val(); // updated
            const company = $('#clone_company').val(); // updated

            if (!assetCode) {
                e.preventDefault();
                alert('Asset Code is required!');
                $('#clone_asset_code').focus();
                return false;
            }

            if (!productType) {
                e.preventDefault();
                alert('Asset Type is required!');
                $('#clone_product_type').focus();
                return false;
            }

            if (!company) {
                e.preventDefault();
                alert('Company is required!');
                $('#clone_company').focus();
                return false;
            }

            // Show loading state
            $(this).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin me-2"></i>Cloning Asset...')
                .prop('disabled', true);
        });

        // Initialize Select2 for depreciation asset dropdown
        try {
            $('#depreciation_asset_code').select2({ // updated field
                placeholder: 'Select an Asset',
                allowClear: true,
                width: '100%'
            });
        } catch (e) {
            console.log('Select2 for depreciation asset skipped:', e);
        }

        // Asset selection handler for depreciation
        $('#depreciation_asset_code').on('change', function() { // updated field
            const selectedOption = $(this).find('option:selected');
            const assetCode = selectedOption.data('code') || ''; // updated
            const cost = selectedOption.data('cost') || 0;
            const purchaseDate = selectedOption.data('purchase-date') || '';
            const assetType = selectedOption.data('type') || '';

            // Populate asset information fields
            $('#dep_asset_code').val(assetCode); // updated
            $('#dep_asset_type').val(assetType);
            $('#dep_original_cost').val(cost);
            $('#dep_purchase_date').val(purchaseDate);

            // Calculate current age if purchase date is available
            if (purchaseDate) {
                const purchase = new Date(purchaseDate);
                const today = new Date();
                const ageInYears = ((today - purchase) / (1000 * 60 * 60 * 24 * 365)).toFixed(1);
                $('#dep_current_age').val(ageInYears);
            }
        });

        // Show/hide depreciation rate field based on method
        $('#dep_method').on('change', function() {
            const method = $(this).val();
            if (method === 'declining_balance') {
                $('#dep_rate_container').show();
                $('#dep_rate').val(25); // Default 25% rate
            } else {
                $('#dep_rate_container').hide();
                $('#dep_rate').val(''); // Clear rate if not needed
            }
        });

        // Auto-calculate current age when purchase date changes
        $('#dep_purchase_dt').on('change', function() { // updated
            const purchaseDate = $(this).val();
            if (purchaseDate) {
                const purchase = new Date(purchaseDate);
                const today = new Date();
                const ageInYears = ((today - purchase) / (1000 * 60 * 60 * 24 * 365)).toFixed(1);
                $('#dep_age_years').val(ageInYears); // updated
            }
        });

        // Select all checkbox functionality
        $('#select_all_assets').on('change', function() { // updated
            const isChecked = $(this).is(':checked');
            $('.asset_item_checkbox').prop('checked', isChecked); // updated
            updateSelectedAssets(); // your existing function
        });

        // Individual asset checkbox functionality
        $('.asset_item_checkbox').on('change', function() { // updated
            updateSelectedAssets();

            // Update select all checkbox state
            const totalCheckboxes = $('.asset_item_checkbox').length; // updated
            const checkedCheckboxes = $('.asset_item_checkbox:checked').length; // updated

            $('#select_all_assets').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes <
                totalCheckboxes); // updated
            $('#select_all_assets').prop('checked', checkedCheckboxes === totalCheckboxes); // updated
        });

        // Label size change handler
        $('#labelSize').on('change', function() {
            updateLabelPreview();
        });

        // QR and Barcode checkbox handlers
        $('#includeQR, #includeBarcode').on('change', function() {
            updateLabelPreview();
        });

        // Other setting change handlers
        $('#include_qr, #include_barcode').on('change', function() { // updated
            updateLabelPreview(); // your existing function
        });


        function clearFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = window.location.pathname;
        }

        function calculateDepreciation() {
            const originalCost = parseFloat($('#dep_cost').val()) || 0;
            const salvageValue = parseFloat($('#dep_salvage').val()) || 0;
            const usefulLife = parseInt($('#dep_life').val()) || 0;
            const method = $('#dep_method_type').val();
            const purchaseDate = $('#dep_purchase_dt').val();
            const customRate = parseFloat($('#dep_custom_rate').val()) || 25;

            if (originalCost <= 0) {
                alert('Please enter a valid original cost');
                $('#dep_cost').focus();
                return;
            }

            if (usefulLife <= 0) {
                alert('Please enter a valid useful life');
                $('#dep_life').focus();
                return;
            }

            if (salvageValue >= originalCost) {
                alert('Salvage value cannot be greater than or equal to original cost');
                $('#dep_salvage').focus();
                return;
            }

            let currentAge = 0;
            if (purchaseDate) {
                const purchase = new Date(purchaseDate);
                const today = new Date();
                currentAge = (today - purchase) / (1000 * 60 * 60 * 24 * 365);
            }

            let schedule = [];
            let totalDepreciation = 0;
            let annualDepreciation = 0;
            let depreciationRate = 0;

            switch (method) {
                case 'straight_line':
                    annualDepreciation = (originalCost - salvageValue) / usefulLife;
                    depreciationRate = (annualDepreciation / originalCost) * 100;
                    schedule = calculateStraightLine(originalCost, salvageValue, usefulLife);
                    break;

                case 'declining_balance':
                    depreciationRate = customRate;
                    annualDepreciation = originalCost * (depreciationRate / 100);
                    schedule = calculateDecliningBalance(originalCost, salvageValue, usefulLife, depreciationRate);
                    break;

                case 'double_declining':
                    depreciationRate = (2 / usefulLife) * 100;
                    annualDepreciation = originalCost * (depreciationRate / 100);
                    schedule = calculateDoubleDeclining(originalCost, salvageValue, usefulLife);
                    break;

                case 'sum_of_years':
                    const sumOfYears = (usefulLife * (usefulLife + 1)) / 2;
                    depreciationRate = (usefulLife / sumOfYears) * 100;
                    annualDepreciation = (originalCost - salvageValue) * (usefulLife / sumOfYears);
                    schedule = calculateSumOfYears(originalCost, salvageValue, usefulLife);
                    break;
            }

            if (currentAge > 0 && currentAge <= usefulLife) {
                totalDepreciation = calculateCurrentDepreciation(schedule, currentAge);
            } else if (currentAge > usefulLife) {
                totalDepreciation = originalCost - salvageValue;
            }

            const currentBookValue = originalCost - totalDepreciation;

            $('#dep_total').val(formatCurrency(totalDepreciation));
            $('#dep_book_val').val(formatCurrency(Math.max(currentBookValue, salvageValue)));
            $('#dep_annual').val(formatCurrency(annualDepreciation));
            $('#dep_percent').val(depreciationRate.toFixed(2));

            updateScheduleTable(schedule);
        }

        function calculateStraightLine(cost, salvage, life) {
            const annualDep = (cost - salvage) / life;
            const schedule = [];
            let accumulatedDep = 0;

            for (let year = 1; year <= life; year++) {
                const beginningValue = year === 1 ? cost : schedule[year - 2].endingValue;
                const depreciation = annualDep;
                accumulatedDep += depreciation;
                const endingValue = Math.max(cost - accumulatedDep, salvage);

                schedule.push({
                    year,
                    beginningValue,
                    depreciation,
                    accumulatedDepreciation: accumulatedDep,
                    endingValue
                });
            }

            return schedule;
        }

        function calculateDecliningBalance(cost, salvage, life, rate) {
            const schedule = [];
            let bookValue = cost;
            let accumulatedDep = 0;

            for (let year = 1; year <= life; year++) {
                const beginningValue = bookValue;
                let depreciation = bookValue * (rate / 100);

                if (bookValue - depreciation < salvage) {
                    depreciation = bookValue - salvage;
                }

                accumulatedDep += depreciation;
                bookValue -= depreciation;

                schedule.push({
                    year,
                    beginningValue,
                    depreciation,
                    accumulatedDepreciation: accumulatedDep,
                    endingValue: bookValue
                });

                if (bookValue <= salvage) break;
            }

            return schedule;
        }

        function calculateDoubleDeclining(cost, salvage, life) {
            const rate = (2 / life) * 100;
            return calculateDecliningBalance(cost, salvage, life, rate);
        }

        function calculateSumOfYears(cost, salvage, life) {
            const schedule = [];
            const sumOfYears = (life * (life + 1)) / 2;
            const base = cost - salvage;
            let accumulatedDep = 0;

            for (let year = 1; year <= life; year++) {
                const beginningValue = year === 1 ? cost : schedule[year - 2].endingValue;
                const remainingLife = life - year + 1;
                const depreciation = base * (remainingLife / sumOfYears);
                accumulatedDep += depreciation;
                const endingValue = cost - accumulatedDep;

                schedule.push({
                    year,
                    beginningValue,
                    depreciation,
                    accumulatedDepreciation: accumulatedDep,
                    endingValue
                });
            }

            return schedule;
        }

        function calculateCurrentDepreciation(schedule, currentAge) {
            if (!schedule || schedule.length === 0) return 0;

            const fullYears = Math.floor(currentAge);
            const partialYear = currentAge - fullYears;
            let totalDep = 0;

            for (let i = 0; i < Math.min(fullYears, schedule.length); i++) {
                totalDep += schedule[i].depreciation;
            }

            if (partialYear > 0 && fullYears < schedule.length) {
                totalDep += schedule[fullYears].depreciation * partialYear;
            }

            return totalDep;
        }

        function updateScheduleTable(schedule) {
            const tbody = $('#depreciation_schedule tbody');
            tbody.empty();

            if (!schedule || schedule.length === 0) {
                tbody.append(
                    `<tr><td colspan="5" class="text-center text-muted"><i class="fa fa-info-circle"></i> No data to display</td></tr>`
                    );
                return;
            }

            schedule.forEach(row => {
                tbody.append(`<tr>
            <td>${row.year}</td>
            <td>$${formatNumber(row.beginningValue)}</td>
            <td>$${formatNumber(row.depreciation)}</td>
            <td>$${formatNumber(row.accumulatedDepreciation)}</td>
            <td>$${formatNumber(row.endingValue)}</td>
        </tr>`);
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        function formatNumber(amount) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(Math.abs(amount) < 0.01 ? 0 : amount);
        }

        function exportDepreciationSchedule() {
            const tbody = $('#depreciation_schedule tbody tr');

            if (tbody.length <= 1 || tbody.first().find('td').attr('colspan')) {
                alert('No depreciation schedule to export. Please calculate first.');
                return;
            }

            const assetTag = $('#dep_tag').val() || 'Unknown';
            const assetType = $('#dep_type').val() || 'Unknown';
            const originalCost = $('#dep_cost').val() || '0';
            const method = $('#dep_method_type option:selected').text();

            let csv = 'Asset Depreciation Schedule\n';
            csv +=
                `Asset Tag:,${assetTag}\nAsset Type:,${assetType}\nOriginal Cost:,$${originalCost}\nMethod:,${method}\nGenerated:,${new Date().toLocaleString()}\n\n`;
            csv += 'Year,Beginning Value,Depreciation,Accumulated Depreciation,Ending Value\n';

            tbody.each(function() {
                const cells = $(this).find('td');
                if (cells.length === 5) {
                    const row = [];
                    cells.each(function() {
                        row.push($(this).text().trim());
                    });
                    csv += row.join(',') + '\n';
                }
            });

            const blob = new Blob([csv], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `depreciation_schedule_${assetTag}_${Date.now()}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function resetCalculator() {
            $('#dep_asset_select').val('').trigger('change');
            $('#dep_tag').val('');
            $('#dep_type').val('');
            $('#dep_cost').val('');
            $('#dep_salvage').val('0');
            $('#dep_life').val('');
            $('#dep_purchase_dt').val('');
            $('#dep_method_type').val('straight_line');
            $('#dep_custom_rate').val('25');
            $('#dep_rate_div').hide();

            $('#dep_age_years').val('');
            $('#dep_total').val('');
            $('#dep_book_val').val('');
            $('#dep_annual').val('');
            $('#dep_percent').val('');

            $('#depreciation_schedule tbody').html(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                <i class="fa fa-info-circle"></i> Enter parameters and click Calculate to see depreciation schedule
            </td>
        </tr>
    `);
        }

        // Bulk Label Print functionality
        let selectedAssets = [];

        function updateSelectedAssets() {
            selectedAssets = [];
            $('.asset-checkbox:checked').each(function() {
                const checkbox = $(this);
                selectedAssets.push({
                    id: checkbox.data('asset-id'),
                    tag: checkbox.data('asset-tag'),
                    type: checkbox.data('asset-type'),
                    model: checkbox.data('model'),
                    brand: checkbox.data('brand'),
                    company: checkbox.data('company')
                });
            });

            const count = selectedAssets.length;
            $('#selectedCount').text(count);
            $('#modalSelectedCount').text(count);
            $('#bulkPrintBtn').prop('disabled', count === 0);

            updateSelectedAssetsList();
        }

        function updateSelectedAssetsList() {
            const container = $('#selectedAssetsList');

            if (selectedAssets.length === 0) {
                container.html(`
            <div class="text-muted text-center">
                <i class="fa fa-info-circle"></i> No assets selected
            </div>
        `);
                return;
            }

            let html = '';
            selectedAssets.forEach((asset, index) => {
                html += `
            <div class="selected-asset-item">
                <div class="selected-asset-info">
                    <div class="selected-asset-tag">${asset.tag}</div>
                    <div class="selected-asset-details">
                        ${asset.type} • ${asset.brand} • ${asset.company}
                    </div>
                </div>
                <button class="remove-asset-btn" onclick="removeSelectedAsset(${index})" title="Remove">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        `;
            });

            container.html(html);
        }

        function removeSelectedAsset(index) {
            const asset = selectedAssets[index];
            $(`.asset-checkbox[data-asset-id="${asset.id}"]`).prop('checked', false);
            updateSelectedAssets();
        }

        function updateLabelPreview() {
            const labelSize = $('#labelSize').val();
            const includeQR = $('#includeQR').is(':checked');
            const includeBarcode = $('#includeBarcode').is(':checked');

            const labelPreview = $('#labelPreview');

            if (labelSize === 'simple') {
                // Show simple label preview (just QR + asset tag)
                labelPreview.html(`
                <div class="preview-label simple-label">
                    <div class="simple-qr-placeholder" style="width: 120px; height: 120px; border: 2px dashed #ccc; margin: 10px auto; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #999;">QR</div>
                    <div class="simple-asset-tag" style="text-align: center; font-weight: bold; font-size: 16px; margin-top: 10px;">BETTEX-0001</div>
                </div>
            `);
            } else {
                // Show detailed label preview
                labelPreview.html(`
                <div class="preview-label ${labelSize}-label">
                    <div class="label-header">
                        <strong>SAMPLE ASSET</strong>
                    </div>
                    <div class="label-content">
                        <div class="asset-tag">BETTEX-0001</div>
                        <div class="asset-info">
                            <small>Type: Laptop</small><br>
                            <small>Brand: Dell</small><br>
                            <small>Model: XPS 13</small><br>
                            <small>Company: BETTEX</small>
                        </div>
                    </div>
                    <div class="label-codes">
                        ${includeQR ? '<div class="qr-placeholder">QR</div>' : ''}
                        ${includeBarcode ? '<div class="barcode-placeholder">|||||||||||</div>' : ''}
                    </div>
                </div>
            `);
            }
        }

        function previewLabels() {
            if (selectedAssets.length === 0) {
                alert('Please select at least one asset to preview.');
                return;
            }

            const labelSize = $('#labelSize').val();
            const includeQR = $('#includeQR').is(':checked');
            const includeBarcode = $('#includeBarcode').is(':checked');
            const additionalText = $('#additionalText').val();

            const previewWindow = window.open('', '_blank', 'width=800,height=600');
            let previewHtml = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Label Preview</title>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .preview-container { display: flex; flex-wrap: wrap; gap: 10px; }
                .preview-label { border: 2px solid #000; padding: 8px; background: white; display: flex; flex-direction: column; justify-content: space-between; }
                /* Simple Label Styles */
                .label-simple {
                    width: 200px;
                    height: 200px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .simple-qr-code {
                    width: 130px;
                    height: 130px;
                    margin-bottom: 10px;
                }
                .simple-qr-code img, .simple-qr-code canvas {
                    width: 100% !important;
                    height: 100% !important;
                }
                .simple-asset-tag {
                    text-align: center;
                    font-weight: bold;
                    font-size: 20px;
                }
                /* Detailed Label Styles */
                .label-small { width: 160px; height: 80px; font-size: 8px; }
                .label-medium { width: 240px; height: 120px; font-size: 10px; }
                .label-large { width: 320px; height: 160px; font-size: 12px; }
                .label-header { text-align: center; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 2px; }
                .label-content { flex: 1; padding: 5px 0; }
                .asset-tag { font-weight: bold; margin: 4px 0; font-size: 1.2em; }
                .label-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #ccc; padding-top: 4px; min-height: 50px; }
                .qr-code { width: 50px; height: 50px; }
                .qr-code img, .qr-code canvas { width: 100% !important; height: 100% !important; }
                .barcode-container { flex: 1; margin-left: 5px; height: 40px; display: flex; align-items: center; }
                .barcode-container svg { max-width: 100%; max-height: 100%; }
            </style>
        </head>
        <body>
            <h2>Label Preview (${selectedAssets.length} labels)</h2>
            <div class="preview-container">
    `;

            selectedAssets.forEach((asset, index) => {
                if (labelSize === 'simple') {
                    // Simple label preview
                    previewHtml += `
                <div class="preview-label label-simple">
                    <div class="simple-qr-code" id="preview-qr-${index}"></div>
                    <div class="simple-asset-tag">${asset.tag}</div>
                </div>
                `;
                } else {
                    // Detailed label preview
                    previewHtml += `
                <div class="preview-label label-${labelSize}">
                    <div class="label-header">${asset.company || 'COMPANY'}</div>
                    <div class="label-content">
                        <div class="asset-tag">${asset.tag}</div>
                        <div><small>${asset.type || ''}</small></div>
                        <div><small>${asset.brand || ''}</small></div>
                        ${additionalText ? `<div><small>${additionalText}</small></div>` : ''}
                    </div>
                    <div class="label-footer">
                        ${includeQR ? `<div class="qr-code" id="preview-qr-${index}"></div>` : ''}
                        ${includeBarcode ? `<div class="barcode-container"><svg id="preview-barcode-${index}"></svg></div>` : ''}
                    </div>
                </div>
                `;
                }
            });

            previewHtml += `
</div>
<br>
<button onclick="window.print()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Labels</button>
<button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Close</button>

<script>
window.onload = function () {
    const assets = ${JSON.stringify(selectedAssets)};
    const labelSize = '${labelSize}';
    const includeQR = ${includeQR};
    const includeBarcode = ${includeBarcode};

    assets.forEach((asset, index) => {

        // ✅ QR URL
        const qrText = '' + asset.id;

        if (labelSize === 'simple') {
            // SIMPLE LABEL QR
            const qrElement = document.getElementById('preview-qr-' + index);
            if (qrElement && typeof QRCode !== 'undefined') {
                        new QRCode(qrElement, {
            text: qrText,
            width: 220,
            height: 220,
            correctLevel: QRCode.CorrectLevel.H
                });
            }

        } else {

            // DETAILED LABEL QR
            if (includeQR) {
                const qrElement = document.getElementById('preview-qr-' + index);
                if (qrElement && typeof QRCode !== 'undefined') {
                    new QRCode(qrElement, {
                        text: qrText,
                        width: 100,
                        height: 100
                    });
                }
            }

            // BARCODE (optional)
            if (includeBarcode) {
                const barcodeElement = document.getElementById('preview-barcode-' + index);
                if (barcodeElement && typeof JsBarcode !== 'undefined') {
                    try {
                        JsBarcode(barcodeElement, asset.tag || asset.id, {
                            format: 'CODE128',
                            width: 2,
                            height: 40,
                            displayValue: false
                        });
                    } catch (e) {
                        console.error('Barcode generation error:', e);
                    }
                }
            }
        }
    });
};
<\/script>
</body>
</html>
`;

            previewWindow.document.write(previewHtml);
            previewWindow.document.close();
        }


        function printLabels() {
            if (selectedAssets.length === 0) {
                alert('Please select at least one asset to print.');
                return;
            }

            const labelSize = $('#labelSize').val();
            const copies = parseInt($('#copiesCount').val()) || 1;
            const includeQR = $('#includeQR').is(':checked');
            const includeBarcode = $('#includeBarcode').is(':checked');
            const additionalText = $('#additionalText').val();

            const printWindow = window.open('', '_blank', 'width=800,height=600');
            let printHtml = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Asset Labels</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
        <style>
            @media print {
                body { margin: 0; }
                .print-label { page-break-inside: avoid; }
                .no-print { display: none; }
                
            }
            body { font-family: Arial, sans-serif; padding: 10px; }
            .print-container { display: flex; flex-wrap: wrap; }
            .print-label {
                padding: 4px;
                margin: 2mm;
                background: white;
                display: flex;
                flex-direction: column;
                justify-content: left;
                align-items: left;
            }
            /* Simple Label Styles */
            .label-simple {
                width: 50mm;
                height: 50mm;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 5mm;
            }
            .simple-qr-code {
                width: 35mm;
                height: 35mm;
                margin-right: 23mm;
            }
            .simple-qr-code img, .simple-qr-code canvas {
                width: 100% !important;
                height: 100% !important;
            }
            .simple-asset-tag {
                text-align: center;
                font-size: 12pt;
                margin-right: 22mm;
                margin-bottom: 2mm;
            }
            /* Detailed Label Styles */
            .label-small { width: 50mm; height: 25mm; font-size: 6px; }
            .label-medium { width: 76mm; height: 38mm; font-size: 8px; }
            .label-large { width: 100mm; height: 50mm; font-size: 10px; }
            .label-header { text-align: center; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 1px; margin-bottom: 2px; }
            .label-content { flex: 1; }
            .asset-tag { font-weight: bold; margin: 1px 0; font-size: 1.2em; }
            .label-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #000; padding-top: 2px; margin-top: 2px; min-height: 30px; }
            .qr-code { width: 25mm; height: 25mm; }
            .qr-code img, .qr-code canvas { width: 100% !important; height: 100% !important; }
            .barcode-container { flex: 1; margin-left: 2px; height: 20mm; display: flex; align-items: center; }
            .barcode-container svg { max-width: 100%; max-height: 100%; }
            .no-print { margin: 20px; text-align: center; }
        </style>
    </head>
    <body>
        <div class="no-print">
            <h3>Generating labels...</h3>
            <button onclick="window.print()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">Print Labels</button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">Close</button>
        </div>
        <div class="print-container">
`;
            let labelIndex = 0;
            selectedAssets.forEach((asset, assetIndex) => {
                for (let copy = 0; copy < copies; copy++) {
                    const currentIndex = labelIndex++;

                    if (labelSize === 'simple') {
                        // Simple label: Just QR code + asset tag
                        printHtml += `<div class="print-label label-simple">
                        <div class="simple-qr-code" id="qr-${currentIndex}"></div>
                        <div class="simple-asset-tag">${asset.tag}</div>
                    </div>`;
                    } else {
                        // Detailed label with all information
                        printHtml += `<div class="print-label label-${labelSize}">
                        <div class="label-header">${(asset.company || 'COMPANY').substring(0, 20)}</div>
                        <div class="label-content">
                            <div class="asset-tag">${asset.tag}</div>
                            <div><small>${asset.type || ''}</small></div>
                            <div><small>${asset.brand || ''}</small></div>
                            ${additionalText ? '<div><small>' + additionalText.substring(0, 30) + '</small></div>' : ''}
                        </div>
                        <div class="label-footer">
                            ${includeQR ? `<div class="qr-code" id="qr-${currentIndex}"></div>` : ''}
                            ${includeBarcode ? `<div class="barcode-container"><svg id="barcode-Hi"></svg></div>` : ''}
                        </div>
                    </div>`;
                    }
                }
            });

            printHtml += `
        </div>
        <script>
            window.onload = function() {
                const assets = ${JSON.stringify(selectedAssets)};
                const copies = ${copies};
                const labelSize = '${labelSize}';
                const includeQR = ${includeQR};
                const includeBarcode = ${includeBarcode};

                let labelIndex = 0;
                assets.forEach((asset, assetIndex) => {
                    for (let copy = 0; copy < copies; copy++) {
                        const currentIndex = labelIndex++;

                         // 🔗 Build QR URL
            const qrUrl = 'https://asset.bettex.com/public/store/qr_code_view/' + asset.id;

                        if (labelSize === 'simple') {
                            // Generate QR Code for simple label (always included)
                            const qrElement = document.getElementById('qr-' + currentIndex);
                            if (qrElement && typeof QRCode !== 'undefined') {
                                new QRCode(qrElement, {
                                    text: qrUrl,
                                    width: 260,
                                    height: 260, 
                                    margin: 0,
                                });
                            }
                        } else {
                            // Generate QR Code for detailed label
                            if (includeQR) {
                                const qrElement = document.getElementById('qr-' + currentIndex);
                                if (qrElement && typeof QRCode !== 'undefined') {
                                    new QRCode(qrElement, {
                                        text: qrUrl,
                                        width: 100,
                                        height: 100,
                                    margin: 0,

                                    });
                                }
                            }

                            // Generate Barcode
                            if (includeBarcode) {
                                const barcodeElement = document.getElementById('barcode-' + currentIndex);
                                if (barcodeElement && typeof JsBarcode !== 'undefined') {
                                    try {
                                        JsBarcode(barcodeElement, asset.tag || '', {
                                            format: 'CODE128',
                                            width: 1,
                                            height: 30,
                                            displayValue: false
                                        });
                                    } catch (e) {
                                        console.error('Barcode generation error:', e);
                                    }
                                }
                            }
                        }
                    }
                });

                // Update message
                document.querySelector('.no-print h3').textContent = 'Labels ready! Click Print Labels button or use Ctrl+P';
            };
        <\/script>
    </body>
    </html>
    `;

            printWindow.document.write(printHtml);
            printWindow.document.close();

            $('#bulkLabelModal').modal('hide');
        }

        // Column Selector Functionality
        $(document).ready(function() {
            initializeColumnSelector();
            loadColumnPreferences();
            showAllColumns();

            // Initialize print button state
            updateSelectedAssets();

            // Asset checkbox event listeners for print labels
            $('.asset-checkbox').on('change', function() {
                updateSelectedAssets();

                // Update select all checkbox state
                const totalCheckboxes = $('.asset-checkbox').length;
                const checkedCheckboxes = $('.asset-checkbox:checked').length;

                $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes <
                    totalCheckboxes);
                $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
            });

            // Select all checkbox functionality
            $('#selectAll').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.asset-checkbox').prop('checked', isChecked);
                updateSelectedAssets();
            });
        });

        function initializeColumnSelector() {
            const table = $('.smart-table table');
            const headers = table.find('thead th[data-column]');
            const dropdownMenu = $('#columnDropdownMenu');
            dropdownMenu.empty();

            headers.each(function() {
                const columnName = $(this).data('column');
                const columnText = $(this).text().trim() || columnName.toUpperCase();
                const isEssential = $(this).data('essential') === true;

                if (columnName === 'checkbox') return;

                const checkboxItem = $(`
        <div class="dropdown-item">
            <div class="column-checkbox-item ${isEssential ? 'essential' : ''}" data-column="${columnName}" ${isEssential ? 'data-essential="true"' : ''}>
                <input type="checkbox" id="col-${columnName}" class="form-check-input column-checkbox" data-column="${columnName}" ${isEssential ? 'disabled' : ''}>
                <label for="col-${columnName}" class="form-check-label">
                    ${columnText} ${isEssential ? ' <small class="text-muted">(Essential)</small>' : ''}
                </label>
            </div>
        </div>
        `);

                dropdownMenu.append(checkboxItem);
            });

            $('.column-checkbox-item').on('click', function(e) {
                if ($(this).hasClass('essential') || $(e.target).is('input')) return;
                const checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
                toggleColumn($(this).data('column'));
            });

            $('.column-checkbox').on('change', function() {
                toggleColumn($(this).data('column'));
            });

            $('#columnDropdownMenu').on('click', function(e) {
                e.stopPropagation();
            });
        }

        function toggleColumn(columnName) {
            const table = $('.smart-table table');
            const columnElements = table.find(`[data-column="${columnName}"]`);
            const checkbox = $(`.column-checkbox[data-column="${columnName}"]`);
            const isChecked = checkbox.prop('checked');

            if (isChecked) columnElements.show();
            else columnElements.hide();

            saveColumnPreferences();
        }

        function showAllColumns() {
            const table = $('.smart-table table');
            table.find('[data-column]').show();
            $('.column-checkbox').prop('checked', true);
            saveColumnPreferences();
        }

        function hideAllColumns() {
            const table = $('.smart-table table');
            table.find('[data-column]:not([data-essential="true"])').hide();
            $('.column-checkbox:not(:disabled)').prop('checked', false);
            saveColumnPreferences();
        }

        function resetToDefault() {
            const defaultVisibleColumns = ['sl', 'status', 'action', 'asset_tag', 'asset_type', 'model', 'brand', 'cost',
                'purchase_date', 'company'
            ];
            const table = $('.smart-table table');
            table.find('[data-column]').hide();
            $('.column-checkbox').prop('checked', false);

            defaultVisibleColumns.forEach(columnName => {
                table.find(`[data-column="${columnName}"]`).show();
                $(`.column-checkbox[data-column="${columnName}"]`).prop('checked', true);
            });

            table.find('[data-essential="true"]').show();
            saveColumnPreferences();
        }

        function saveColumnPreferences() {
            const visibleColumns = [];
            $('.column-checkbox').each(function() {
                if ($(this).prop('checked')) visibleColumns.push($(this).data('column'));
            });
            localStorage.setItem('storeListVisibleColumns', JSON.stringify(visibleColumns));
        }

        function loadColumnPreferences() {
            const saved = localStorage.getItem('storeListVisibleColumns');
            if (!saved) return resetToDefault();

            try {
                const visibleColumns = JSON.parse(saved);
                const table = $('.smart-table table');
                table.find('[data-column]:not([data-essential="true"])').hide();
                $('.column-checkbox:not(:disabled)').prop('checked', false);

                visibleColumns.forEach(columnName => {
                    table.find(`[data-column="${columnName}"]`).show();
                    $(`.column-checkbox[data-column="${columnName}"]`).prop('checked', true);
                });

                table.find('[data-essential="true"]').show();
            } catch (e) {
                console.error('Error loading column preferences:', e);
                resetToDefault();
            }
        }
    </script>
@endpush
