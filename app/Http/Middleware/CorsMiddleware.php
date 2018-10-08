<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CorsMiddleware
{
    const ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';

    const ACCESS_CONTROL_EXPOSE_HEADERS = 'Access-Control-Expose-Headers';

    const ACCESS_CONTROL_MAX_AGE = 'Access-Control-Max-Age';

    const ACCESS_CONTROL_ALLOW_CREDENTIALS = 'Access-Control-Allow-Credentials';

    const ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';

    const ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';

    const ACCESS_CONTROL_REQUEST_HEADERS = "Access-Control-Request-Headers";

    const OPTIONS = 'OPTIONS';

    const ALLOW_METHODS = 'allowMethods';

    const ALLOW_HEADERS = 'allowHeaders';

    const ALLOW_CREDENTIALS = 'allowCredentials';

    const EXPOSE_HEADERS = 'exposeHeaders';

    const MAX_AGE = 'maxAge';

    const ORIGIN = 'origin';

    /**
     * @var array
     */
    private $settings = array(
        self::ORIGIN => '*',
        self::ALLOW_METHODS => 'GET,HEAD,PUT,POST,DELETE,PATCH,OPTIONS',
    );

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod(self::OPTIONS)) {
            $response = new Response('', 200);
        } else {
            $response = $next($request);
        }
        $this->setCorsHeaders($request, $response);
        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    private function setOrigin($request, $response)
    {
        $origin = $this->settings[self::ORIGIN];
        if (is_callable($origin)) {
            $origin = call_user_func($origin,
                $request->header(ucfirst(self::ORIGIN))
            );
        }
        $response->header(self::ACCESS_CONTROL_ALLOW_ORIGIN, $origin);
    }

    /**
     * @param \Illuminate\Http\Response $response
     */
    private function setExposeHeaders($response)
    {
        if (isset($this->settings[self::EXPOSE_HEADERS])) {
            $exposeHeaders = $this->settings[self::EXPOSE_HEADERS];
            if (is_array($exposeHeaders)) {
                $exposeHeaders = implode(', ', $exposeHeaders);
            }

            $response->header(self::ACCESS_CONTROL_EXPOSE_HEADERS, $exposeHeaders);
        }
    }

    /**
     * @param \Illuminate\Http\Response $response
     */
    private function setMaxAge($response)
    {
        if (isset($this->settings[self::MAX_AGE])) {
            $response->header(self::ACCESS_CONTROL_MAX_AGE, $this->settings[self::MAX_AGE]);
        }
    }

    /**
     * @param \Illuminate\Http\Response $response
     */
    private function setAllowCredentials($response)
    {
        if (isset($this->settings[self::ALLOW_CREDENTIALS]) && $this->settings[self::ALLOW_CREDENTIALS] === true) {
            $response->header(self::ACCESS_CONTROL_ALLOW_CREDENTIALS, 'true');
        }
    }

    /**
     * @param \Illuminate\Http\Response $response
     */
    private function setAllowMethods($response)
    {
        if (isset($this->settings[self::ALLOW_METHODS])) {
            $allowMethods = $this->settings[self::ALLOW_METHODS];
            if (is_array($allowMethods)) {
                $allowMethods = implode(", ", $allowMethods);
            }

            $response->header(self::ACCESS_CONTROL_ALLOW_METHODS, $allowMethods);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    private function setAllowHeaders($request, $response)
    {
        if (isset($this->settings[self::ALLOW_HEADERS])) {
            $allowHeaders = $this->settings[self::ALLOW_HEADERS];
            if (is_array($allowHeaders)) {
                $allowHeaders = implode(", ", $allowHeaders);
            }
        } else {
            $allowHeaders = $request->header(self::ACCESS_CONTROL_REQUEST_HEADERS);
        }
        if (isset($allowHeaders)) {
            $response->header(self::ACCESS_CONTROL_ALLOW_HEADERS, $allowHeaders);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    private function setCorsHeaders($request, $response)
    {
        if ($request->isMethod(self::OPTIONS)) {
            $this->setOrigin($request, $response);
            $this->setMaxAge($response);
            $this->setAllowCredentials($response);
            $this->setAllowMethods($response);
            $this->setAllowHeaders($request, $response);
        } else {
            $this->setOrigin($request, $response);
            $this->setExposeHeaders($response);
            $this->setAllowCredentials($response);
        }
    }
}
