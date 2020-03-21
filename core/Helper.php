<?php


namespace core;


class Helper
{
    public static function vardump($object,$die = false,$backgroundColor = 'white', $color = 'black')
    {
        echo '<pre>';
        echo '<div style="height: fit-content; width: 100%; background-color: '.$backgroundColor.';color:'.$color.' ">';
        var_dump($object);
        echo '</div>';
        echo '</pre>';

        if($die) {
            die();
        }
    }
}