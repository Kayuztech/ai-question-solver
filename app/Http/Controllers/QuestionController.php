<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $answers = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $path);

            $text = shell_exec("tesseract {$fullPath} stdout");

            $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an academician. Answer the following question clearly and academically.'],
                    ['role' => 'user', 'content' => $text],
                ],
            ]);

            $answers[] = [
                'question' => trim($text),
                'answer' => trim($response['choices'][0]['message']['content'] ?? 'No response from AI'),
            ];
        }

        return view('upload', compact('answers'));
    }
}
