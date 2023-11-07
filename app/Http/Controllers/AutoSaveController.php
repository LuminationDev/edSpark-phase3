<?php

namespace App\Http\Controllers;

use App\Models\AutoSave;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AutoSaveController extends Controller
{
    /**
     * Handle creating or retrieving an auto-save.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleAutoSave(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->storeAutoSave($request);
        }

        return $this->getActiveAutoSave($request);
    }

    private function storeAutoSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'post_id' => 'nullable|integer',
                'post_type' => 'required|string',
                'content' => 'required|string',
                'exp_date' => 'nullable|date',
            ]);

            // Check if a previous version is available
            $previousAutoSave = AutoSave::where('user_id', $validated['user_id'])
                ->where('post_type', $validated['post_type'])
                ->where('is_active', true)
                ->first();

            if ($previousAutoSave) {
                // Set the old auto-save to inactive
                $previousAutoSave->update(['is_active' => false]);
            }

            // Create the new auto-save
            $autoSave = AutoSave::create($validated + ['is_active' => true]);
            return response()->json(['message' => 'Auto-save created successfully!', 'data' => $autoSave], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function getActiveAutoSave(Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::user()->id;
        $postType = $request->input('post_type');

        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        $autoSaveQuery = AutoSave::where('user_id', $userId)
            ->where('is_active', true)
            ->whereDate('exp_date', '>=', now())
            ->orderByDesc('updated_at');

        if ($postType && $postType !== 'all') {
            $autoSaveQuery->where('post_type', $postType);
        }

        $validAutoSaves = $autoSaveQuery->get();

        if ($validAutoSaves->isEmpty()) {
            return response()->json(['message' => 'No active auto-save found.'], 404);
        }

        // Set other older autosave to inactive except the ones just returned
        AutoSave::where('user_id', $userId)
            ->where('is_active', true)
            ->whereDate('exp_date', '<', now())
            ->when($postType && $postType !== 'all', function ($query) use ($postType) {
                return $query->where('post_type', $postType);
            })
            ->when(!$validAutoSaves->isEmpty(), function ($query) use ($validAutoSaves) {
                return $query->whereNotIn('id', $validAutoSaves->pluck('id'));
            })
            ->update(['is_active' => false]);

        $resultArray = $validAutoSaves->map(function ($item) {
            return (object) $item->toArray();
        })->toArray();

//        return response()->json(['message' => 'Auto-save(s) found!', 'data' => $validAutoSaves], 200);
        return ResponseService::success('Auto-save(s) found', $resultArray);

    }


}
