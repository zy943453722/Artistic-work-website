<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/26
 * Time: 17:28
 */

namespace App\Controller;

use Slim\Container;

abstract class baseController
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