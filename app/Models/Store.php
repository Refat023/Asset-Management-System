<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Store extends Model
{
    use HasFactory;
    protected $fillable = ['asset_tag', 'asset_type', 'model', 'brand_id','description', 'asset_sl_no', 'qty', 'units_id','warrenty', 'durablity', 'cost', 'currency', 'vendor', 'purchase_date', 'challan_no', 'picture', 'status_id', 'location', 'company_id', 'others', 'checkstatus', 'others2', 'created_by'];

    function rel_to_ProductType(){
        return $this->belongsTo(ProductType::class, 'asset_type');
    }

    public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id', 'id');
}

    function rel_to_brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    function rel_to_SizeMaseurment(){
        return $this->belongsTo(SizeMaseurment::class, 'units_id');
    }

    function rel_to_Supplier(){
        return $this->belongsTo(Supplier::class, 'vendor');
    }

    function rel_to_Status(){
        return $this->belongsTo(Status::class, 'status_id');
    }

    function rel_to_Company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    function rel_to_Department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    function rel_to_Designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    // Check if this asset is currently borrowed
    public function isBorrowed()
    {
        return TransferRequest::where('asset_tag', $this->asset_tag)
            ->where('status', 'approved')
            ->where('item_status', 'borrowed')
            ->exists();
    }

    // Get the borrowing information if the asset is borrowed
    public function getBorrowingInfo()
    {
        return TransferRequest::where('asset_tag', $this->asset_tag)
            ->where('status', 'approved')
            ->where('item_status', 'borrowed')
            ->first();
    }

    // Relationship to issue
    function rel_to_issue(){
        return $this->hasOne(issue::class, 'asset_tag');
    }
}
