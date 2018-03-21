<?php
/**
 * File: TwigRenderer.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Template;


use Twig_Environment;

class TwigRenderer implements Renderer
{
    private $renderer;

    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render($template, $data = []) : string
    {
        return $this->renderer->render("$template.html", $data);
    }
}