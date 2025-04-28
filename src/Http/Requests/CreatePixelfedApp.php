<?php

namespace Inovector\Mixpost\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Inovector\Mixpost\Facades\ServiceManager;
use Symfony\Component\HttpFoundation\Response;
use Inovector\Mixpost\Actions\CreatePixelfedApp as CreatePixelfedAppAction;

class CreatePixelfedApp extends FormRequest
{
    public function rules(): array
    {
        return [
            'server' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(): void
    {
        $serviceName = "pixelfed.{$this->input('server')}";

        if (ServiceManager::get($serviceName, 'configuration')) {
            return;
        }

        $result = (new CreatePixelfedAppAction())($this->input('server'));

        if (isset($result['error'])) {
            $errors = ['server' => [$result['error']]];

            throw new HttpResponseException(
                response()->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }
} 