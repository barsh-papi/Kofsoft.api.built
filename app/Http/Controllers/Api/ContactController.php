<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;





class ContactController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'message' => 'required|string|max:5000',
            ]);

            Mail::raw(
                "New Contact Message:\n\n" .
                    "Name: {$validated['name']}\n" .
                    "Email: {$validated['email']}\n" .
                    "Phone: {$validated['phone']}\n\n" .
                    "Message:\n{$validated['message']}",
                function ($message) use ($validated) {
                    $message->to('paabart0@kofsoft.com')
                        ->subject('New Contact Message from ' . $validated['name']);
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send your message. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
