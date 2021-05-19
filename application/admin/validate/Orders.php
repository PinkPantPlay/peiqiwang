<?php


namespace app\admin\validate;

use think\Validate;

class Orders extends Validate
{

    protected $rule = [
        'order_num' => 'require|number|elt:30'
    ];

    protected $message = [
        'order_num.unique' => '',
        'order_num.number' => '',
        'order_num.elt' => '',
    ];

    protected $scene = [
        'save' => ['order_num']
    ];
}