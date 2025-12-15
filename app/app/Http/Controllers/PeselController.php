<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Services\PeselService;

class PeselController extends Controller
{
    function index(PeselService $peselService): Response
    {
        return Inertia::render('MainPage', [
            'options' => $peselService->getOptions(),
        ]);
    }

    function generator(Request $request, PeselService $peselService): JsonResponse
    {
        return response()->json([
            'status' => true,
            'pesel' => $peselService->generate($request),
        ]);
    }
}
