<?php
namespace DevBar\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Core\Configure;

/**
 * Bar middleware
 */
class BarMiddleware
{

    /**
     * Invoke method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {

        $response = $next($request, $response);

        $body = $response->getBody();
        if (!$body->isSeekable() || !$body->isWritable()) {
            return $response;
        }
        
        $body->rewind();
        $contents = $body->getContents();

        // Body tag found?
        $pos = strrpos($contents, '</body>');
        if ($pos === false) {
            return $response;
        }

        // DevBar already injected?
        $pos = strrpos($contents, 'id="devbar"');
        if ($pos != false) {
            return $response;
        }

        $body->rewind();

        $message = Configure::read('DevBar.message') ? Configure::read('DevBar.message') : __('Debug is enabled!');

        $development_bar = '<div id="devbar" style="width:100%;padding:2px 10px;background-color: #e63757;position: absolute;top: 0;left: 0;text-align:right;color:white;z-index:9999">' . $message . '</div>';
        
        // Inject DevBar in body content before body end tag
        $contents = substr($contents, 0, $pos) . $development_bar . substr($contents, $pos);
        
        $body->rewind();
        $body->write($contents);
        return $response->withBody($body);
    }
}
