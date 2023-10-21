<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'stc_city';
    public $timestamps = false;
    protected $primaryKey = 'stc_city_id';
    protected $fillable = ['stc_city_name', 'stc_city_status'];

    static public function getCity(){
        return City::select('stc_city.*')->orderBy('stc_city_name', 'asc')->get();
    }
}
