<?php

namespace App\Http\Controllers;

use App\Models\Urlinfo;
use Illuminate\Http\Request;

class UrlextractorController extends Controller
{
    /**
     * Display the home page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allUrls = Urlinfo::all();
        return view('home', ['allUrls' => $allUrls]);
    }

    /**
     * Extract URL properties and return to the view
     *
     * @return \Illuminate\Http\Response
     */
    public function extract_url()
    {
        try{
            $requestArr = fn_get_request_json_arr();

            $url = $requestArr['extractor'];
            if(!empty($url) && $url !='')
            {
                $sites_html = file_get_contents($url);

                $html = new \DOMDocument();
                @$html->loadHTML($sites_html);
                $meta_og_img = null;
                $meta_og_title = null;
                $meta_og_desc = null;
                //Get all meta tags and loop through them.
                foreach($html->getElementsByTagName('meta') as $meta) {
                    //If the property attribute of the meta tag is og:image
                    if($meta->getAttribute('property')=='og:image'){
                        $meta_og_img = $meta->getAttribute('content');
                    }

                    //If the property attribute of the meta tag is og:title
                    if($meta->getAttribute('property')=='og:title'){
                        $meta_og_title = $meta->getAttribute('content');
                    }

                    //If the property attribute of the meta tag is og:image or name is description
                    if($meta->getAttribute('property')=='og:description' || $meta->getAttribute('name')=='description'){
                        $meta_og_desc = $meta->getAttribute('content');
                    }
                }

                /*
                 * If og:title not found then
                 * get the title of the page
                 */
                if($meta_og_title == null){
                    $sites_html = trim(preg_replace('/\s+/', ' ', $sites_html)); // supports line breaks inside <title>
                    preg_match("/\<title\>(.*)\<\/title\>/i",$sites_html,$title); // ignore case
                    $meta_og_title = $title[1];
                }

                /*
                 * If og:image not found then
                 * get the first image on the page
                 */
                if($meta_og_img == null){
                    preg_match('@<img.+src="(.*)".*>@Uims', $sites_html, $matches);
                    $meta_og_img = $matches[1];
                }

                /*
                 * If description not found then read
                 * the first text on page inside of <p> tag
                 */
                if($meta_og_desc == null){
                    //preg_match('@<img.+src="(.*)".*>@Uims', $sites_html, $matches);
                    $start = strpos($sites_html, '<p>');
                    $end = strpos($sites_html, '</p>', $start);
                    $paragraph = substr($sites_html, $start, $end-$start+4);
                    $meta_og_desc = $paragraph;
                }

                return json_encode(['meta_og_img'=>@$meta_og_img, 'meta_og_desc'=>@$meta_og_desc, 'meta_og_title'=>@$meta_og_title]);
            }
        }catch (Exception $e) {
            Log::addError("Exception error" ,
                array(
                    'context' => [
                        $e->getMessage()
                        . ' in file '
                        . $e->getFile()
                        . ' at line '
                        . $e->getLine()
                        . " full stack "
                        . $e->getTraceAsString()
                    ]
                ));
            $responseObj = RestResponseFactory::error((object) array() , $e->getMessage());

            return $responseObj->toJSON();
        }
    }

    /**
     * Store URL information
     */
    public function storeUrlInfo()
    {
        try{
            $requestArr = fn_get_request_json_arr();
            $errorsArr    = array();

            $url = $requestArr['url'];
            if(!empty($url) && $url !='')
            {
                $UrlinfoObj             = new Urlinfo();
                $UrlinfoObj->url        = $requestArr['url'];
                $UrlinfoObj->title      = $requestArr['url_title'];
                $UrlinfoObj->image      = $requestArr['url_image'];
                $UrlinfoObj->desc       = $requestArr['url_desc'];
                //Save URL info into the urlinfos table
                if($UrlinfoObj->save()){
                    return json_encode(['url'=>@$requestArr['url'], 'title'=>@$requestArr['url_title']]);
                }

            }
        }catch (Exception $e) {
            Log::addError("Exception error" ,
                array(
                    'context' => [
                        $e->getMessage()
                        . ' in file '
                        . $e->getFile()
                        . ' at line '
                        . $e->getLine()
                        . " full stack "
                        . $e->getTraceAsString()
                    ]
                ));
            $responseObj = RestResponseFactory::error((object) array() , $e->getMessage());

            return $responseObj->toJSON();
        }
    }
}
