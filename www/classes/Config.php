<?php

class Config
{
    public static function get($path = null)
    {
        if ($path)
        {
            $config = $GLOBALS['config'];
            $path = explode('.', $path);

            foreach ($path as $key)
            {
                if (isset($config[$key]))
                {
                    $config = $config[$key];
                }
            }
            return $config;
        }
        return false;
    }
}