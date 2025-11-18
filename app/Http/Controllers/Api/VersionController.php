<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index(): JsonResponse
    {
        $versions = Version::query()
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'data' => $versions,
            'message' => 'Versions retrieved successfully'
        ]);
    }

    public function show(Version $version): JsonResponse
    {
        return response()->json([
            'data' => $version,
            'message' => 'Version retrieved successfully'
        ]);
    }
}
