<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\GenAI\Client;
use Exception;

class AIController extends Controller
{
    /**
     * Show AI Assistant page
     */
    public function index()
    {
        return view('assistant.index');
    }

    /**
     * Chat with Gemini
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {

            $client = new Client(env('GEMINI_API_KEY'));

            $response = $client->models()->generateContent(
                model: 'gemini-3.5-flash',
                contents: $request->message
            );

            return response()->json([
                'success' => true,
                'reply' => $response->text(),
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}