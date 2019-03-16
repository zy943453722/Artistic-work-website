<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/16
 * Time: 10:01
 */

namespace App\Middleware;

use Slim\Container;

abstract class baseMiddleware
{
    protected $ci;
    public function __construct(Container $ci)
    {
        $this->ci = $ci;
    }
    public function __get($property)
    {
        if (isset($this->ci, $property)) {
            return $this->ci->{$property};
        }
        return null;
    }
}