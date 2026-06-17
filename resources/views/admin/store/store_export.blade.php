<table>
    <thead>
        <tr>
            <th>SL No</th>
            <th>Asset Tag</th>
            <th>Asset Type</th>
            <th>Model</th>
            <th>Brand</th>
            <th>Description</th>
            <th>Asset Sl No</th>
            <th>Quantity</th>
            <th>Units</th>
            <th>Warrenty</th>
            <th>Durablity</th>
            <th>Cost</th>
            <th>Currency</th>
            <!-- <th>Vendor</th> -->
            <th>Purchase Date</th>
            <th>Challan_no</th>
            <th>Status</th>
            <th>location</th>
            <th>Company</th>
            <th>Others</th>
            <th>Checkstatus</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($store as $key => $store)
            <tr>

                <td>{{ $key + 1 }}</td>
                <td>{{ $store->products_id }}</td>
                <td>{{ $store->rel_to_ProductType->product }}</td>
                <td>{{ $store->model }}</td>
                <td></td>
                <td>{{ $store->description }}</td>
                <td>{{ $store->asset_sl_no }}</td>
                <td>{{ $store->qty }}</td>
                <td>{{ $store->rel_to_SizeMaseurment->size }}</td>
                <td>{{ $store->warrenty }}</td>
                <td>{{ $store->durablity }}</td>
                <td>{{ $store->cost }}</td>
                <td>{{ $store->currency }}</td>
                <td></td>
                <td>{{ $store->purchase_date }}</td>
                <td>{{ $store->challan_no }}</td>
                <td>{{ $store->rel_to_Status->status_name }}</td>
                <td>{{ $store->location }}</td>
                <td>{{ $store->company_id }}</td>
                <td>{{ $store->others }}</td>
                <td>{{ $store->checkstatus }}</td>


            </tr>
        @endforeach
    </tbody>
</table>
