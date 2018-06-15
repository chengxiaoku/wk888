<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/23
 * Time: 13:15
 */

namespace app\index\validate;
use think\Validate;
class Level extends Validate
{

    protected $rule =   [
       
        'discount' => 'number|between:0,100|require',
    ];

    protected $message  =   [
        
        'discount.number' => '折扣必须为数字',
        'discount.between' => '折扣必须在0~100之间',
        'discount.require' => '折扣不能为空',
    ];
    
}