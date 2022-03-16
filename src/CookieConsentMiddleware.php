<?php

namespace Statikbe\CookieConsent;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CookieConsentMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $response instanceof Response) {
            return $response;
        }

        if (! $this->containsBodyTag($response)) {
            return $response;
        }

        if ($this->isIgnoredPath($request)){
            return $response;
        }
        
        return $this->addCookieConsentScriptToResponse($response);
    }

    protected function containsBodyTag(Response $response): bool
    {
        return $this->getLastClosingBodyTagPosition($response->getContent()) !== false;
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return \Illuminate\Http\Response
     */
    protected function addCookieConsentScriptToResponse(Response $response)
    {
        $content = $response->getContent();

        $closingBodyTagPosition = $this->getLastClosingBodyTagPosition($content);

        $content = ''
            .substr($content, 0, $closingBodyTagPosition)
            .view('cookie-consent::index')->render()
            .substr($content, $closingBodyTagPosition);

        return $response->setContent($content);
    }

    protected function getLastClosingBodyTagPosition(string $content = '')
    {
        return strripos($content, '</body>');
    }

    private function isIgnoredPath($request)
    {
        foreach (config('cookie-consent.ignored_paths', []) as $path){
            if (Str::is($path, $request->getPathInfo())){
                return true;
            }
        }
        return false;
    }
}
