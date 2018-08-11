<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:07
 */

namespace ArtWorksWebSite\lib\event;

use ArtWorksWebSite\lib\http\Request;

class GetControllerEvent extends Event
{
    private $controller;

    public function __construct(Request $request,Controller $controller)
    {
        parent::__construct($request);
        $this->controller = $controller;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }
}
