<?php
namespace app\my_api\controller;
class Index
{

    public function index()
    {
        return [
            'name' => 'thinkphp',
            'version' => '5.0'
        ];
    }
}
