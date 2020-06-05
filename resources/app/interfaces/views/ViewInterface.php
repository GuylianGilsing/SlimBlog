<?php
namespace App\Interfaces\Views;

interface ViewInterface
{
    /**
     * Registers variables that can be used within the view.
     * 
     * @param array $variables an array of variables that will be made
     * availlable within the view.
     */
    public function withVariables(array $variables);

    /**
     * Loads the view.
     */
    public function load();

    /**
     * Renders the view.
     * 
     * @return string Returns a string with the rendered view content.
     */
    public function render();
}