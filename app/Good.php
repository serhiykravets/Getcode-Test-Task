<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as DB;

class Good extends Model
{
    public static function addGood($data)
    {
        $rowData = [
            'sku' => $data['sku'],
            'name' => $data['name'],
            'brand' => $data['brand'],
            'description' => $data['description'],
            'url' => $data['url'],
        ];
        $id = DB::table('goods')->insertGetId($rowData);
        return $id;
    }
}
