<?php

namespace App\Core;

class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /** Render a view file with data */
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extract data as variables
        extract($data);

        // Capture view content
        ob_start();
        $viewFile = BASE_PATH . '/resources/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        require $viewFile;
        $content = ob_get_clean();

        // Render inside layout
        $layoutFile = BASE_PATH . '/resources/views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            // No layout — just output view
            echo $content;
            return;
        }
        require $layoutFile;
    }

    /** Redirect to a URL */
    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    /** Send JSON response */
    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /** Abort with HTTP status */
    protected function abort(int $code, string $message = ''): never
    {
        http_response_code($code);
        $messages = [
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
        ];
        $title = $messages[$code] ?? 'Error';
        $msg   = $message ?: $title;
        echo "<!DOCTYPE html><html><body><h1>{$code} — {$title}</h1><p>" . htmlspecialchars($msg) . "</p></body></html>";
        exit;
    }

    /** Validate required POST fields, return errors array */
    protected function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $label) {
            $value = $this->request->post($field);
            if ($value === null || $value === '') {
                $errors[$field] = "{$label} is required.";
            }
        }
        return $errors;
    }

    /** Escape output for HTML */
    protected function e(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
