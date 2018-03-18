<?php
/**
 * File: Routes.php
 * User: karan.tuteja26@gmail.com
 * Description: Return an array containing all the routes in the app
 */

return [
    ['GET', '/', ['Ticket\Controllers\TicketController', 'index']],
    ['GET', '/populate', ['Ticket\Controllers\TicketController', 'populateDB']],
    ['GET', '/inventory', ['Ticket\Controllers\TicketController', 'inventoryList']],
    ['GET', '/show', ['Ticket\Controllers\TicketController', 'show']],
   // ['GET', '/{slug}', ['Ticket\Controllers\Page', 'show']],
    ['GET', '/book/{showID:[0-9]+}', ['Ticket\Controllers\TicketController', 'book']],
    ['POST', '/checkout/{showID:[0-9]+}', ['Ticket\Controllers\TicketController', 'checkout']],



];