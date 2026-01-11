<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VersionCollection;
use App\Http\Resources\VersionResource;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index(): VersionCollection
    {
        $versions = Version::query()
            ->orderBy('release_date', 'desc')
            ->get();

        return VersionCollection::make($versions);
    }

    public function show(int $versionId): VersionResource
    {
        $version = Version::query()
            ->findOrFail($versionId);

        return VersionResource::make($version);
    }
}
