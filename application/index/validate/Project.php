<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/23
 * Time: 13:15
 */

namespace app\index\validate;
use think\Validate;
class Project extends Validate
{

    protected $rule =   [
        'name'  => 'require|max:25',
        'money' => 'number|between:0,100000|require',
    ];

    protected $message  =   [
        'name.require' => '名称不能为空',
        'name.max'     => '名称最多不能超过25个字符',
        'money.number' => '金额必须为数字',
        'money.between' => '金额必须在0~1000000之间',
        'money.require' => '金额不能为空',
    ];
}