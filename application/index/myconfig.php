<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/26
 * Time: 15:24
 */
return [
    //myconfig
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    'base_url'=>'', // clf
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    'page' => 10,
    //会话过期时间
    'SESSION_EXPIRE' => 3600*24,

    //MENUNAME 应该与 MENU在一块
    "MENUNAME" => [
        'index' => '控制台',
        'user' => '会员管理',
        'project' => '项目管理',
        'funds' => '交易记录',
        'admin' => '管理员管理',
        'password' => '修改密码',
        'level' => '会员等级',
        'set' => '系统信息',
    ],

    "MENU" => [
        'index' => "<li id=\"index\">
                 <a href=\"#\">
                       <i class=\"icon-dashboard\"></i>
                        <span class=\"menu-text\"> 控制台 </span>
                    </a>
               </li>",
        'user' => "<li id=\"user\">
                    <a href=\"#\">
                        <i class=\"icon-group\"></i>
                        <span class=\"menu-text\"> 会员管理 </span>
                    </a>
                </li>",
        'project' => "<li id=\"project\">
                    <a href=\"#\">
                        <i class=\"icon-bar-chart\"></i>
                        <span class=\"menu-text\"> 项目管理 </span>
                    </a>
                </li>",
        'funds' => "<li id=\"funds\">
                    <a href=\"#\">
                        <i class=\"icon-credit-card\"></i>
                        <span class=\"menu-text\"> 交易记录 </span>
                    </a>
                </li>",
        'admin' => " <li id=\"admin\">
                    <a href=\"#\">
                        <i class=\" icon-lock\"></i>
                        <span class=\"menu-text\"> 管理员管理 </span>
                    </a>
                </li>",
        'password' => " 
                <li id=\"password\">
                    <a href=\"#\">
                        <i class=\"icon-key\"></i>
                        <span class=\"menu-text\"> 修改管理员密码 </span>
                    </a>
                </li>",
        'proset' =>[
            'str1' =>"<li id=\"proset\">
                    <a href=\"#\" class=\"dropdown-toggle\">
                        <i class=\"icon-cogs\"></i>
                        <span class=\"menu-text\"> 设置 </span>

                        <b class=\"arrow icon-angle-down\"></b>
                    </a>

                    <ul class=\"submenu\" >",
            'str2' =>" </ul>
                    </li>",
            'content' =>[
                "level" =>"<li id=\"level\">
                            <a href=\"#\">
                                <i class=\"icon - double - angle - right\"></i>
                                会员等级设置
                            </a>
                        </li>",
                "set" => " <li id=\"set\">
                            <a href=\"#\">
                                <i class=\"icon - double - angle - right\"></i>
                                系统信息设置
                            </a>
                        </li>",
            ],
        ],
    ],
];