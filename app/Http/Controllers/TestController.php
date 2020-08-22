<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ParseGoods;

class TestController extends Controller
{
    public function parse(Request $request)
    {
        $input = $request->all();
        if(isset($input['url'])) {
            ParseGoods::dispatch($input['url']);
//          echo 'Rejoice your url was parsed';
        }

        return view('parser');
    }
}
