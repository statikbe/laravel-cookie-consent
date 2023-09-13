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

        if (!$response instanceof Response) {
            return $response;
        }

        if (!$this->containsBodyTag($response) || !$this->containsHeadTag($response)) {
            return $response;
        }

        if ($this->isIgnoredPath($request)) {
            return $response;
        }

        return $this->addCookieConsentScriptToResponse($response);
    }

    protected function containsBodyTag(Response $response): bool
    {
        return $this->getLastClosingBodyTagPosition($response->getContent()) !== false;
    }

    protected function containsHeadTag(Response $response): bool
    {
        return $this->getLastClosingBodyTagPosition($response->getContent()) !== false;
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    protected function addCookieConsentScriptToResponse(Response $response): Response
    {
        $content = $response->getContent();

        $closingBodyTagPosition = $this->getLastClosingBodyTagPosition($content);
        $closingHeadTagPosition = $this->getLastClosingHeadTagPosition($content);

        $content = ''
            . substr($content, 0, $closingBodyTagPosition)
            . view('cookie-consent::index')->render()
            . substr($content, $closingBodyTagPosition);

        $content = ''
            . substr($content, 0, $closingHeadTagPosition)
            . view('cookie-consent::head')->render()
            . substr($content, $closingHeadTagPosition);

        return $response->setContent($content);
    }

    protected function getLastClosingBodyTagPosition(string $content = ''): false|int
    {
        return strripos($content, '</body>');
    }

    protected function getLastClosingHeadTagPosition(string $content = ''): false|int
    {
        return strripos($content, '</head>');
    }

    private function isIgnoredPath($request): bool
    {
        foreach (config('cookie-consent.ignored_paths', []) as $path) {
            if (Str::is($path, $request->getPathInfo())) {
                return true;
            }
        }
        return false;
    }
}
