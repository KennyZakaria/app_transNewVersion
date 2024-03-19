<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends BaseController
{
        // public function createMessage(Request $request)
        // {
        //     $validator = Validator::make($request->all(), [
        //         'message' => 'required|string',
        //         'devis.id' => 'required|integer',
        //         'receiver.id' => 'required|integer'
        //     ]);
        //     if ($validator->fails()) {
        //         return $this->sendError('Validation Error', $validator->errors(), 422);
        //     }
        //     $message = Message::create([
        //         'date' => now(),
        //         'message' => $request->input('message'),
        //         'sender_id' => auth()->id(),
        //         'receiver_id' => $request->input('receiver.id'),
        //         'devi_id' => $request->input('devis.id')
        //     ]);
        //     return response()->json($message, 201);
        // }
        
        public function createMessage(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'message' => 'required|string',
                'devis.id' => 'required|integer',
                'receiver.id' => 'required|integer',
                'photos.*.size' => 'nullable|string',
                'photos.*.format' => 'nullable|string',
                'photos.*.nom' => 'nullable|string',
                'photos.*.url' => 'nullable|string', // Add this line for photo validation
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors(), 422);
            }

            $photoPath = null;

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos'); // Adjust the storage path as needed
            }

            $message = Message::create([
                'date' => now(),
                'message' => $request->input('message'),
                'sender_id' => auth()->id(),
                'receiver_id' => $request->input('receiver.id'),
                'devi_id' => $request->input('devis.id'),
                'photo' => $photoPath,
            ]);

            return response()->json($message, 201);
        }

        public function MessageByDevis(Request $request)
        {

            $perPage = $request->input('per_page', $request->input('size'));
            $devisId = $request->input('devisId');
            $beforeMessageId = $request->input('before');
            $afterMessageId = $request->input('after');
            $sortOrder = $request->input('sort_order', 'desc');
            $query = Message::where('devi_id', $devisId)
                ->with([
                    'sender' => function ($query) {
                        $query->select('id', 'firstName', 'lastName');
                    },
                    'receiver' => function ($query) {
                        $query->select('id', 'firstName', 'lastName');
                    },
                ]);

                if ($beforeMessageId) {
                    $query->where('id', '<', $beforeMessageId);
                    $query->orderBy('created_at','desc');
                } elseif ($afterMessageId) {
                    $query->where('id', '>', $afterMessageId);
                    $query->orderBy('created_at','asc');
                } else {
                    $query->orderBy('created_at', $sortOrder);
                }

            $messages = $query->paginate($perPage);

            return response()->json($messages);


        }
}


