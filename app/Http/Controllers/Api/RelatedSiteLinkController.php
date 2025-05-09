<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelatedSiteLinkResource;
use App\Models\RelatedSiteLink;
use Illuminate\Http\Request;

class RelatedSiteLinkController extends Controller
{
    public function getRelatedSiteLinks()
    {
        $related_site_links = RelatedSiteLink::get() ; 
        if(!$related_site_links){
            return apiResponse(404 , 'Not Found!') ; 
        }
        return apiResponse(200 , 'success' , RelatedSiteLinkResource::collection($related_site_links)) ; 
    }
}
