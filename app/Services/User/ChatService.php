<?php

namespace App\Services\User;

use App\Models\Conversation;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ChatService{

    public static function ask($request){

        $userInput = $request->input('message');
        $userId = Auth::id();
        if (empty($userInput)) {
            return response()->json(['reply' => 'Invalid input or user not authenticated.'], 400);
        }
        $system = "You are a helpful chatbot for an online clothing store who doesn't hallucinate at all. Follow these rules STRICTLY:
        1. Never list all products at once. Instead, summarize categories (e.g., 'We offer dresses, jeans, and accessories').
        2. Keep responses under 3 sentences unless user requests details.
        3. If user insists on a full list more than 8 products, reply: 'For a better experience, please visit our website or specify filters.'";


        $productList = '';
        if (str_contains(strtolower($userInput), 'product')) {
            $products = Product::all()->where('visible', true)->take(20);
            foreach ($products as $product) {
                $productList .= $product . "\n";
            }
        }

        if ($productList) {
            $system .= "Here are some products available:\n" . $productList;
        }

        $conversationParts = [];
        $conversations = Conversation::where('user_id', $userId)->orderBy('created_at', 'asc')->get();

        foreach ($conversations as $conversation) {
            $conversationParts[] = [
                "role" => "user",
                "parts" => [["text" => $conversation->user_message]]
            ];
            $conversationParts[] = [
                "role" => "model",
                "parts" => [["text" => $conversation->bot_reply]]
            ];
        }

        $conversationParts[] = [
            "role" => "user",
            "parts" => [["text" => $userInput]]
        ];


        // Gemini API call
        $response = Http::withOptions([
            'verify' => false,
            $link = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),

        ])->post($link, [
            "system_instruction" => [
                "role" => "system",
                "parts" => [["text" => $system]]
            ],
            "contents" => $conversationParts
        ]);

        $reply = $response->json('candidates.0.content.parts.0.text') ?? 'No response from the AI.';
        $conversation = new Conversation();
        $conversation->user_id = $userId;
        $conversation->user_message = $userInput;
        $conversation->bot_reply = $reply;
        $conversation->save();

        return $reply;
    }
}
