<?php

namespace Inovector\Mixpost\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Inovector\Mixpost\Http\Requests\CreatePixelfedApp;

class CreatePixelfedAppController extends Controller
{
    public function __invoke(CreatePixelfedApp $createPixelfedApp): Response
    {
        $createPixelfedApp->handle();

        return response()->noContent();
    }
} 