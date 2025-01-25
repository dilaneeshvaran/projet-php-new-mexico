<?php
namespace App\Core;

class View
{
    private string $view;
    private string $template;
    private array $data = [];

    public function __construct(string $view, string $template = "front.php")
    {
        $this->view = __DIR__ . "/../views/" . $view;
        $this->template = __DIR__ . "/../views/" . $template;
    }

    public function __toString()
    {
        return "Nous sommes sur le template " . $this->template . " dans lequel sera inclus la vue " . $this->view;
    }

    public function addData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function render(): void
    {
        //extract data to local variables
        extract($this->data);

        //start output buffering
        ob_start();

        //render the view if exists
        if (file_exists($this->view)) {
            include $this->view;
        } else {
            echo "View file not found: " . $this->view;
        }

        //capture view content
        $content = ob_get_clean();

        //start output buffering for template
        ob_start();

        //render the template with view content if exist
        if (file_exists($this->template)) {
            include $this->template;
        } else {
            echo "Template file not found: " . $this->template;
        }

        //output the final content
        echo ob_get_clean();
    }
}