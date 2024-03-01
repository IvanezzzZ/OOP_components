<?php

class Input
{

    public static function exist($method = 'post')
    {
        switch ($method)
        {
            case 'post':
                return (!empty($_POST)) ? true : false;
            case 'get':
                return (!empty($_GET)) ? true : false;
            default:
                return false;
        }
    }

    public static function get($input)
    {
        if (isset($_POST[$input]))
        {
            return $_POST[$input];
        }
        elseif (isset($_GET[$input]))
        {
            return $_GET[$input];
        }

         return '';
    }
}