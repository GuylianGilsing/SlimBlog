<?php
namespace App\Core\Views;

use App\Core\Views\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigView extends View
{
    private ?Environment $view = null;

    /**
     * Creates a new twig view.
     * 
     * @param string $filename The name of the view file without the .twig
     * extension, e.g. : helloworld OR folder/helloworld
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename.'.twig';
    }

    /**
     * Loads the view and prepares it.
     */
    public function load()
    {
        parent::load();

        // Load the filesystem and instantiate a new twig environment
        $loader = new FilesystemLoader(VIEW_DIR.'/twig/');
        $this->view = new Environment($loader);
    }

    /**
     * Renders the view.
     * 
     * @return string Returns a string with the rendered view content.
     */
    public function render()
    {
        $content = "[ERROR] View is not initialized.";

        if($this->view !== null)
        {
            $content = $this->view->render($this->filename, $this->variables);
        }

        return $content;
    }
}