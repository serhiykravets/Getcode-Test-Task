<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Redis;
use App\Good;
use App\Photo;

class ParseGoods implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => "User-agent: chrome",
                    'ignore_errors' => true,
                    'follow_location' => true
                )
            )
        );

        $html = file_get_contents($this->url, false, $context);
        $dom = new \DomDocument();
        try {
            $dom->loadHTML($html);
        } catch (\Throwable $e) {
            // asuming even there is no request to handle exception, it can be cached and handled here
        }
        $finder = new \DomXPath($dom);
        $nodes = $finder->query("//*[contains(@class, 'br-pcg-product-wrapper')]");
        if (null !== $nodes[0]->getAttribute('data-pid')) {
// parse goods list
            foreach ($nodes as $node) {
                $goodData = [];
                $goodData['sku'] = $node->getAttribute('data-pid');
                $goodData['name'] = $node->getAttribute('data-name');
                $goodData['brand'] = $node->getAttribute('data-vendor');
                $desc_node = $finder->query("descendant::div[contains(@class, 'br-pp-i-grid')]", $node);
                $goodData['description'] = trim($desc_node[0]->nodeValue);
                $url_node = $finder->query("descendant::a[contains(@itemprop, 'url')]", $node);
                $goodData['url'] = 'https://brain.com.ua' . trim($url_node[0]->getAttribute('href'));
                $img_url_node = $finder->query("descendant::img[contains(@itemprop, 'image')]", $node);
                $goodPhotosUrls = [];
                $goodPhotosUrls[] = $img_url_node[0]->getAttribute('data-observe-src');

                $goodId = Good::addGood($goodData);
                Photo::addGoodPhotos($goodPhotosUrls, $goodId);
           }
        } else {
//parse single good
           $goodData = [];
           $nodes = $finder->query("//*[contains(@class, 'btn-add-green')]");
           $goodData['sku'] = $nodes[0]->getAttribute('data-pid');
           $nodes = $finder->query("//*[contains(@class, 'btn-add-green')]");
           $goodData['name'] = $nodes[0]->getAttribute('data-name');
           $nodes = $finder->query("//*[contains(@class, 'btn-add-green')]");
           $goodData['brand'] = $nodes[0]->getAttribute('data-brand');
           $goodData['url'] = $this->url;
           $nodes = $finder->query("//*[contains(@class, 'br-pp-i')]");
           $goodData['description'] = trim($nodes[0]->nodeValue);
           $goodPhotosUrls = [];
           $nodes = $finder->query("//*[contains(@class, 'br-main-img')]");
           foreach ($nodes as $node) {
               $goodPhotosUrls[] = $node->getAttribute('src');
           }

           $goodId = Good::addGood($goodData);
           Photo::addGoodPhotos($goodPhotosUrls, $goodId);
        }
    }
}
