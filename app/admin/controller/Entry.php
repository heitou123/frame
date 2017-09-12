<?php

    namespace app\admin\controller;

    use houdunwang\view\View;

    class Entry extends Common
    {
        /*  [加载模板]
         *  View::make()
         */
        public function index ()
        {
            return View ::make ();
        }
    }