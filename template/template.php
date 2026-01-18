<?php
class Template
{
    private string $basePath;
    private array $vars = [];

    public function __construct(string $basePath = '../html')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function set(string $key, string $value): self
    {
        $this->vars[$key] = $value;
        return $this;
    }

    private function load(string $file): string
    {
        $path = $this->basePath . '/' . $file;

        if (!file_exists($path)) {
            throw new Exception("Template not found: {$file}");
        }

        return file_get_contents($path);
    }

    private function replaceVars(string $html): string
    {
        foreach ($this->vars as $key => $value) {
            $html = str_replace('%' . $key . '%', $value, $html);
        }
        return $html;
    }

    public function render(string $file): string
    {
        $html = $this->load($file);
        return $this->replaceVars($html);
    }
}
?>