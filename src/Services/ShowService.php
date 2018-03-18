<?php
/**
 * File: ShowService.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Services;


class ShowService
{

    public function populateDatabase() {
        $file = fopen("contacts.csv","r");
        print_r(fgetcsv($file));
        fclose($file);
    }
}