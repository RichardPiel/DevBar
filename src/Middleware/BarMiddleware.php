<?php
namespace DevBar\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Bar middleware
 */
class BarMiddleware
{

    /**
     * Constructor
     *
     * @param DebugKit\ToolbarService $service The configured service, or null.
     */
    public function __construct()
    {
    }

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

        $pos = strrpos($contents, '</body>');
        if ($pos === false) {
            return $response;
        }

        // DevBar already injected ?
        $pos = strrpos($contents, 'id="devbar"');
        if ($pos != false) {
            return $response;
        }

        $body->rewind();
        $development_bar = '<div id="devbar" style="width:100%;padding:2px 10px;background-color: #e63757;position: absolute;top: 0;left: 0;text-align:right;color:white"><strong>Attention :</strong> Le Debug actif !</div>';
        $contents = substr($contents, 0, $pos) . $development_bar . substr($contents, $pos);
        $body->rewind();

        $body->write($contents);
        return $response->withBody($body);
    }
}
