<?php
/**
 * File: Renderer.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Template;


interface Renderer
{
    public function render($template, $data = []) : string;
}