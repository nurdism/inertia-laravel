<?php

namespace Inertia\Ssr;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HttpGateway implements Gateway
{
    /**
     * Dispatch the Inertia page to the Server Side Rendering engine.
     *
     * @param  array  $page
     * @return Response|null
     */
    public function dispatch(array $page): ?Response
    {
        if (! Config::get('inertia.ssr.enabled', false)) {
            return null;
        }

        $url = Config::get('inertia.ssr.url', 'http://127.0.0.1:8080/render');

        try {
            $response = Http::post($url, $page)->throw()->json();
        } catch (Exception $e) {
            return null;
        }

        return new Response(
            implode("\n", $response['head']),
            $response['body']
        );
    }
}