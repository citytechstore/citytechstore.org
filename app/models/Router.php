<?php
class Router
{
    private $uri;
    private $routes;

    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->routes = require_once 'app/routes/routes.php';
    }

    public function route()
    {
        if ($this->isStaticFile($this->uri)) {
            return $this->serveStaticFile($this->uri);
        }

        $uri_parse = parse_url($this->uri);
        $uri_path = $uri_parse['path'];
        $uri_query = [];
        if (array_key_exists('query', $uri_parse)) {
            parse_str($uri_parse['query'], $uri_query);
        }
        if (array_key_exists($uri_path, $this->routes)) {
            require ($this->routes[$uri_path]);
        } else {
            $this->abort_router("404");
        }
    }

    protected function abort_router($code = "404")
    {
        http_response_code($code);
        require 'app/controllers/errorPages/' . $code . '.php';
        die();
    }

    private function isStaticFile($uri)
    {
        $staticExtensions = ['ico', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif'];
        $pathInfo = pathinfo($uri);

        return isset($pathInfo['extension']) && in_array($pathInfo['extension'], $staticExtensions);
    }

    private function serveStaticFile($uri)
    {
        $filePath = '../public' . $uri;

        // Debugging line
        // file_put_contents('debug.txt', "Attempting to serve static file: " . $filePath . PHP_EOL, FILE_APPEND);

        if (file_exists($filePath)) {
            // Use finfo to determine the MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);

            // Debugging line
            // file_put_contents('debug.txt', "Serving file with MIME type: " . $mimeType . PHP_EOL, FILE_APPEND);

            header('Content-Type: ' . $mimeType);
            readfile($filePath);
            exit;
        } else {
            // Debugging line
            // file_put_contents('debug.txt', "File not found: " . $filePath . PHP_EOL, FILE_APPEND);

            header("HTTP/1.0 404 Not Found");
            echo 'File not found';
            exit;
        }
    }
}
