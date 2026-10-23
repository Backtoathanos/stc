<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ToolstackelsData extends Model
{
    protected $table = 'toolstackelsdata';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public static function ensureColumns(): void
    {
        $cols = [
            'department_name' => "VARCHAR(150) NOT NULL DEFAULT ''",
            'model_no' => "VARCHAR(150) NOT NULL DEFAULT ''",
            'serial_thm_no' => "VARCHAR(150) NOT NULL DEFAULT ''",
            'make_name' => "VARCHAR(150) NOT NULL DEFAULT ''",
            'range_value' => "VARCHAR(150) NOT NULL DEFAULT ''",
        ];
        foreach ($cols as $name => $def) {
            if (!Schema::hasColumn('toolstackelsdata', $name)) {
                Schema::getConnection()->statement("ALTER TABLE `toolstackelsdata` ADD `".$name."` ".$def);
            }
        }
    }
}
