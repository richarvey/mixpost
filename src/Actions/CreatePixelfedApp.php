<?php

namespace Inovector\Mixpost\Actions;

use Illuminate\Support\Facades\Http;
use Exception;

class CreatePixelfedApp
{
    public function __invoke(string $serverName): array
    {
        $serviceName = "pixelfed.$serverName";

        try {
            $configuration = Http::post("https:/$serverName/api/v1/apps", [
                'client_name' => config('app.name'),
                'redirect_uris' => route('mixpost.callbackSocialProvider', ['provider' => 'pixelfed']),
                'scopes' => 'read write'
            ])->json();

            if (isset($configuration['error'])) {
                return [
                    'error' => $configuration['error']
                ];
            }

            (new UpdateOrCreateService())(
                name: $serviceName,
                configuration: $configuration,
                active: true
            );

            return $configuration;
        } catch (Exception $exception) {
            return [
                'error' => 'This Pixelfed server is not responding or does not exist.'
            ];
        }
    }
} 