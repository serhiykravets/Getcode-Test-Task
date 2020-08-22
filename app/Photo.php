<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as DB;

class Photo extends Model
{
    public static function addGoodPhotos($data, $parentId)
    {
        foreach ($data as $photoUrl) {
            $rowData = [
                'url' => $photoUrl,
                'parent_id' => $parentId,
            ];
            DB::table('photos')->insert($rowData);
        }
    }
}
