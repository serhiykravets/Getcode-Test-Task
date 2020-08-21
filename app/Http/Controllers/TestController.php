<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function parse(Request $request)
    {
        $input = $request->all();
        if(isset($input['url'])) {
            $context = stream_context_create(
                array(
                    'http' => array(
                        'header' => "User-agent: chrome",
                        'ignore_errors' => true,
                        'follow_location' => true
                    )
                )
            );

$html = file_get_contents($input['url'], false, $context);
//echo $html;
$dom = new \DomDocument();
            @$dom->loadHTML($html);
            $finder = new \DomXPath($dom);
            $goodData = [];
// sku
            $nodes = $finder->query("//*[contains(@itemprop, 'sku')]");
            $goodData['sku'] = $nodes[0]->nodeValue;
// name
            $nodes = $finder->query("//*[contains(@id, 'br-pr-1')]");
            $goodData['name'] = $nodes[0]->nodeValue;
// brand
            $nodes = $finder->query("//*[contains(@class, 'btn-add-green')]");
            $goodData['brand'] = $nodes[0]->getAttribute('data-brand');
// url
            $goodData['url'] = $input['url'];

            var_dump($goodData);

//            echo 'Rejoice your url was parsed';
        }

        return view('parser');
    }
}
