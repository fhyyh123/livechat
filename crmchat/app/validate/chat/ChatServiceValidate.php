<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\validate\chat;


use think\Validate;

class ChatServiceValidate extends Validate
{

    /**
     * @var string[]
     */
    // 国际手机号: 可选 + 前缀，6-15 位数字，允许空格/连字符/点/括号作为分隔，不允许以分隔符结尾
    protected $regex = ['phone' => '/^\+?(?:\d[\s\-().]?){5,14}\d$/'];

    /**
     * @var string[]
     */
    protected $rule = [
        'phone'    => 'require|regex:phone',
        'avatar'   => 'require',
        'nickname' => 'require',
    ];

    /**
     * @var string[]
     */
    protected $message = [
        'phone.require'    => 'Please enter your phone number',
        'phone.regex'      => 'Phone number format is incorrect',
        'nickname.require' => 'Please enter a nickname',
        'avatar.require'   => 'Please upload an avatar',
    ];
}
