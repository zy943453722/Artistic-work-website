<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:05
 */

namespace ArtWorksWebSite\lib\event;

use ArtWorksWebSite\lib\http\Request;

class GetResponseEvent extends Event
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
}