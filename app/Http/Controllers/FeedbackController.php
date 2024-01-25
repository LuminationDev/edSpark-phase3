<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function createFeedback(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $feedbackUsername = $request->input('user_name');
            $feedbackEmail = $request->input('email');
            $feedbackOrganisation = $request->input('organisation_name');
            $feedbackIssueurl = $request->input('issue_url');
            $feedbackContent = $request->input('content');

            $user = Auth::user();

            // Create a new feedback using the data
            $feedback = Feedback::create(
                [
                    'user_name' => $feedbackUsername,
                    'email' => $feedbackEmail,
                    'organisation_name' => $feedbackOrganisation,
                    'issue_url' => $feedbackIssueurl,
                    'content' => $feedbackContent
                ],
                [

                ]
            );

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