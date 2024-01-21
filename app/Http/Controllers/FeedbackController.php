<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Services\ResponseService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function createFeedback(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'user_name' => 'required|string',
                'email' => 'required|email',
                'content' => 'required|string',
            ]);

            // Create a new feedback using the validated data
            $feedback = Feedback::create($validatedData);

            $feedbackId = $feedback->id;


            return ResponseService::success('Feedback created successfully', $feedbackId);
        } catch (ValidationException $validationException) {
            // Use the ResponseService to send a validation error response
            return ResponseService::error('Validation error', $validationException->errors(), 422);
        } catch (\Exception $exception) {
            // Use the ResponseService to send a generic error response
            return ResponseService::error('Error creating feedback', $exception->getMessage(), 500);
        }
    }
}
