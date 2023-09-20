<?php

namespace App\Http\Controllers\api;

use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class messageBoardController extends APIController
{
    public function createMessage(Request $request)
    {
        try{
            $rules = [
                'name' => 'required',
                'email' => 'required|email|valid_email_domain',
                'title' => 'required',
                'message' => 'required',
            ];
            $customMessages = [
                'name.required'     => 'Form wajib diisi!',
                'email.required'    => 'Form wajib diisi!',
                'title.required'    => 'Form wajib diisi!',
                'message.required'  => 'Form wajib diisi!',

                'email.email'  => 'Format email yang anda masukkan salah!',
                // 'email.unique'  => 'email ini sudah pernah mengirimkan pesan!',
                'email.valid_email_domain'  => 'Alamat email harus berakhiran dengan domain .id, .net, atau .com',
            ];
            if(array_keys($request->all()) == array_keys($rules)){
                $validator = Validator::make($request->all(), $rules, $customMessages);

                if ($validator->fails()) {
                    return (new baseController)->responseBadRequest($validator->errors());
                }

            } else{
                return (new baseController)->responseBadRequest();
            }

            $dataUser = User::where('email' , '=' , $request->email)->first();

            if ($dataUser != null) {
                $storeMessage = new Message();
                $storeMessage->user_id = $dataUser->user_id;
                $storeMessage->title   = $request->title;
                $storeMessage->message = $request->message;
                $storeMessage->save();
            } else {
                $storeUser = new User();
                $storeUser->name = $request->name;
                $storeUser->email = $request->email;
                $storeUser->save();

                $dataUser = User::where('email' , '=' , $request->email)->first();

                $storeMessage = new Message();
                $storeMessage->user_id = $dataUser->user_id;
                $storeMessage->title   = $request->title;
                $storeMessage->message = $request->message;
                $storeMessage->save();
            }
            return (new baseController)->responseStatus();
        }catch(Exception $e){
            return (new baseController)->responseServerError();
        }
    }

    public function getAllMessage()
    {
        try {
            $response = [];
            $dataMessage = Message::orderBy('updated_at','ASC')->get();
            try{
                if(count($dataMessage) < 1) {
                    return (new baseController)->responseNotFound('Data Tidak Ditemukan');
                } else {
                    foreach ($dataMessage as $item) {
                        $dataUser = User::where('user_id', '=', $item->user_id)->get(['user_id', 'name', 'email'])->first();
                        array_push($response, [
                            'message_id' => $item->message_id,
                            'user'  => $dataUser,
                            'title' => $item->title,
                            'message' => $item->message,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ]);
                    }
                    return (new baseController)->responseListObject($response, totalPage:ceil(count($response)/4));
                }
            } catch (Exception $e){
                return (new baseController)->responseBadRequest();
            }
        } catch (Exception $e) {
            return (new baseController)->responseServerError();
        }
    }

    public function getMessageById($id){
        try {
            $response = [];
            if (is_numeric($id)) {
                $dataMessage = Message::where('message_id' , '=' , $id)->first();
                if ($dataMessage == null) {
                    return (new baseController)->responseNotFound();
                }

                $dataUser = User::where('user_id' , '=' , $dataMessage->user_id)->first();
                $response = [
                    'message_id' => $dataMessage->message_id,
                    'user'  => $dataUser,
                    'title' => $dataMessage->title,
                    'message' => $dataMessage->message,
                    'created_at' => $dataMessage->created_at,
                    'updated_at' => $dataMessage->updated_at,
                ];
                return (new baseController)->responseObject($response);
            } else {
                return (new baseController)->responseBadRequest();
            }
        } catch (Exception $e) {
            return (new baseController)->responseServerError();
        }
    }

    public function searchMessage(Request $request){
        try {
            $keywords = $request->query('q');
            if ($keywords == null) {
                return (new baseController)->responseBadRequest();
            }
            $response = [];
            $dataUser = User::where('email', 'LIKE' , '%' . $keywords . '%')
                            ->orWhere('name', 'LIKE' , '%' . $keywords . '%')->get();
            foreach ($dataUser as $user) {
                $dataMessage = Message::where('user_id' , '=' , $user->user_id)->get();
                foreach ($dataMessage as $message) {
                    array_push($response, [
                        'message_id' => $message->message_id,
                        'user'  => $user,
                        'title' => $message->title,
                        'message' => $message->message,
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                    ]);
                }
            }

            $dataMessage = Message::where('title', 'LIKE' , '%' . $keywords . '%')
                        ->orWhere('message', 'LIKE' , '%' . $keywords . '%')->get();
            foreach ($dataMessage as $item) {
                $dataUser = User::where('user_id' , '=' , $item->user_id)->first();
                if(array_search($dataUser, $response) == null){
                    array_push($response, [
                        'message_id' => $item->message_id,
                        'user'  => $dataUser,
                        'title' => $item->title,
                        'message' => $item->message,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ]);
                }
            }
            if ($response != []) {
                return (new baseController)->responseListObject($response);
            } else {
                return (new baseController)->responseNotFound();
            }

        } catch (Exception $e) {
            return (new baseController)->responseServerError();
        }
    }

    public function updateMessage($id, Request $request){
        try {
            if (is_numeric($id)) {
                $tempMessageData = Message::where('message_id', '=', $id)->first();
                if ($tempMessageData == null) {
                    return (new baseController)->responseNotFound();
                }
                Message::where('message_id', '=', $id)->update([
                    'title' => ($request->title != null) ? $request->title : $tempMessageData->title,
                    'message' => ($request->message != null) ? $request->message : $tempMessageData->message,
                ]);
                return (new baseController)->responseStatus();
            } else {
                return (new baseController)->responseBadRequest();
            }
        } catch (Exception $e) {
            return (new baseController)->responseServerError();
        }
    }

    public function deleteMessage($id){
        try {
            if (is_numeric($id)) {
                $tempMessageData = Message::where('message_id', '=', $id)->first();
                if ($tempMessageData == null) {
                    return (new baseController)->responseNotFound();
                }
                Message::where('message_id', '=', $id)->delete();
                return (new baseController)->responseStatus();
            } else {
                return (new baseController)->responseBadRequest();
            }
        } catch (Exception $e) {
            return (new baseController)->responseServerError();
        }
    }
}
