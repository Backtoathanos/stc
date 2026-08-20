<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'stc_merchant';
    public $timestamps = false;
    protected $primaryKey = 'stc_merchant_id';
    protected $fillable = [
        'stc_merchant_name',
        'stc_merchant_address',
        'stc_merchant_city_id',
        'stc_merchant_state_id',
        'stc_merchant_contact_person',
        'stc_merchant_email',
        'stc_merchant_phone',
        'stc_merchant_pan',
        'stc_merchant_gstin',
        'stc_merchant_specially_known_for',
        'stc_merchant_category',
        'stc_merchant_image',
        'stc_merchant_found_by',
    ];

    public static function categoryOptions()
    {
        return [
            'Manufacturer',
            'Retailer',
            'Wholesaler',
            'Distributor',
            'Dealer',
            'Supplier',
            'Trader',
            'Service Provider',
            'Others',
        ];
    }
}
