<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class aiController extends Controller
{
    
  public function showAi(Request $request){
    return view('ai.image');
  }

 public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('NVIDIA_API_KEY'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('NVIDIA_IMAGE_API_URL'), [
            "prompt" => $request->prompt,
            "width" => 1024,
            "height" => 1024,
            "seed" => 0,
            "steps" => 4
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Image generation failed',
                'details' => $response->body()
            ], 500);
        }

        return response()->json($response->json());
    }

}
