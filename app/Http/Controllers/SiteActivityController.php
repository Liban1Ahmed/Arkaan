<?php

namespace App\Http\Controllers;

use App\Models\SiteActivity;
use Illuminate\Http\Request;

class SiteActivityController extends Controller
{
    public function index()
    {
        return SiteActivity::orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'poster' => 'nullable|string',
            'featured' => 'boolean',
        ]);

        $activity = SiteActivity::create($validated);

        return response()->json($activity, 201);
    }

    public function update(Request $request, SiteActivity $siteActivity)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'schedule' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'poster' => 'nullable|string',
            'featured' => 'boolean',
        ]);

        $siteActivity->update($validated);

        return response()->json($siteActivity);
    }

    public function destroy(SiteActivity $siteActivity)
    {
        $siteActivity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }
}