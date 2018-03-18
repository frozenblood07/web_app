<?php
/**
 * File: Routes.php
 * User: karan.tuteja26@gmail.com
 * Description: Return an array containing all the routes in the app
 */

return [
    ['GET', '/', ['Ticket\Controllers\TicketController', 'index']],
];