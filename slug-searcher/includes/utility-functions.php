<?php

if ( ! function_exists('dd')) {
    function dd($var)
    {
        echo '<pre style="border: 1px solid red; padding: 35px; width: 75%; margin: 20px auto; display: block;">';
        var_dump($var);
        echo '</pre>';
    }
}
