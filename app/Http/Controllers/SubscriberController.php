<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscriber = Subscriber::subscribe($request->email, $request->name);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih telah berlangganan newsletter kami!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscriber = Subscriber::where('email', $request->email)->first();

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan dalam daftar subscriber.'
                ], 404);
            }

            if (!$subscriber->isSubscribed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah tidak berlangganan.'
                ], 400);
            }

            $subscriber->unsubscribe();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil berhenti berlangganan newsletter.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function checkSubscription(Request $request)
    {
        $subscriber = Subscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return response()->json([
                'subscribed' => false
            ]);
        }

        return response()->json([
            'subscribed' => $subscriber->isSubscribed(),
            'subscriber' => $subscriber
        ]);
    }
}
