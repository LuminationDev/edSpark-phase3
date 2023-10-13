<?php

namespace App\Http\Controllers;

use App\Models\AutoSave;
use Illuminate\Http\Request;
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

    private function getActiveAutoSave(Request $request)
    {
        $userId = $request->input('user_id');
        $postType = $request->input('post_type');

        if (!$userId || !$postType) {
            return response()->json(['message' => 'User ID and Post Type are required.'], 400);
        }

        $activeAutoSaves = AutoSave::where('user_id', $userId)
            ->where('post_type', $postType)
            ->where('is_active', true)
            ->get();

        $validAutoSave = null;
        foreach ($activeAutoSaves as $autoSave) {
            if (new \DateTime($autoSave->exp_date) >= new \DateTime()) {
                $validAutoSave = $autoSave;
                break;
            }

            $autoSave->is_active = false;
            $autoSave->save();
        }

        if (!$validAutoSave) {
            return response()->json(['message' => 'No active auto-save found.'], 404);
        }

        return response()->json(['message' => 'Auto-save found!', 'data' => $validAutoSave], 200);
    }
}
