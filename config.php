<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        echo "<script>";
        echo "console.log(".json_encode($args).")";
        echo "</script>";
    }
}
