<?php

namespace app\admin\behavior;

class Test
{
    public function run(&$title)
    {
        echo "this is run";
        return $title;
    }
}
