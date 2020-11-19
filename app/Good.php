<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as DB;

class Good extends Model
{
    public static function addGood($data)
    {
        $id = DB::table('goods')->insertGetId($data);
        return $id;
    }
}
