<?php
/**
 * User: karan.tuteja26@gmail.com
 */

namespace Ticket\Utilities;


class ShowUtils
{
    public static function getShowPrice($genre) {

        switch ($genre) {
            case "MUSICAL": return 70;
            case "COMEDY": return 50;
            case "DRAMA": return 40;
            default: return 10;
        }

    }
}