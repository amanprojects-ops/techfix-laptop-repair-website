<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $params = [];

    /** Register a GET route */
    public function get(string $pattern, array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    /** Register a POST route */
    public function post(string $pattern, array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    /** Register a PUT route */
    public function put(string $pattern, array $handler): void
    {
        $this->add('PUT', $pattern, $handler);
    }

    /** Register a DELETE route */
    public function delete(string $pattern, array $handler): void
    {
        $this->add('DELETE', $pattern, $handler);
    }

    private function add(string $method, string $pattern, array $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'pattern' => $this->compile($pattern),
            'raw'     => $pattern,
            'handler' => $handler,
        ];
    }

    /** Convert route pattern to regex, e.g. /repair/{id} → /repair/(\d+|[^/]+) */
    private function compile(string $pattern): string
    {
        $pattern = preg_replace('/\{([a-z_]+)\}/', '([^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri    = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // remove full match
                $this->params = $matches;
                $this->call($route['handler'], $matches);
                return;
            }
        }

        // 404 fallback
        http_response_code(404);
        echo "<!DOCTYPE html><html><body style='font-family:sans-serif;padding:2rem'>";
        echo "<h1>404 — Page Not Found</h1>";
        echo "<p>The page you're looking for doesn't exist.</p>";
        echo "<a href='/'>← Go Home</a></body></html>";
    }

    private function call(array $handler, array $params): void
    {
        [$controllerClass, $method] = $handler;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Method {$method} not found on {$controllerClass}");
        }

        call_user_func_array([$controller, $method], $params);
    }
}
