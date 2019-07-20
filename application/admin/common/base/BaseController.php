<?php

namespace app\admin\common\base;

use think\Hook;
use think\Controller;

class BaseController extends Controller
{
    function __construct()
    {
        Hook::exec('app\\admin\\behavior\\Auth');
    }
}
