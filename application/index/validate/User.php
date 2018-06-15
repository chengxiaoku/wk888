<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/23
 * Time: 13:15
 */

namespace app\index\validate;
use think\Validate;
class User extends Validate
{
    
    protected $rule =   [
        'name'  => 'require|max:25',
        'age'   => 'number|between:1,120',
        'money' => 'number|between:0,1000000',
    ];

    protected $message  =   [
        'name.require' => '名称不能为空',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'money.number' => '金额必须为数字',
        'money.between' => '金额必须在0~1000000之间',
    ];
}