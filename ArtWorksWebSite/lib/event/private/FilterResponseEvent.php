<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:06
 */

namespace ArtWorksWebSite\lib\event;

use ArtWorksWebSite\lib\http\Request;

class FilterResponseEvent extends Event
{
    private $response;

    public function __construct(Request $request,Response $response)
    {
        parent::__construct($request);
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}