<?php

namespace App\Http\Controllers;

use App\Helpers\Whatsapp;
use App\Models\Chat;
use App\Models\Device;
use App\Models\Kontak;
use App\Models\Message;
use App\Models\TemplateCopywriting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    // wehbook whatsapp masuk
    public function whatsapp(Request $request)
    {
        $data = $request->all();
        Log::info($data);
        $chat = Chat::where('msgid', $data['id'])->first();
        if (!$chat) {
            $chat = new Chat();
        }
        $device = Device::where('apikey', $data['token'])->first();
        if (!$device) {
            $device = Device::first();
        }        
        $chat->user_id = $device->user_id;
        $chat->device_id = $device->id;
        $chat->from_phone = $data['server_phone'];
        $chat->to_phone = $data['phone'];
        $chat->message = $data['message'];
        $chat->status = 0;
        $chat->send_at = Date('Y-m-d H:i:s');
        $chat->msgid = $data['id'];
        $chat->chat_type = 'in';
        $chat->save();
        // save kontak jika belum ada
        $kontak = Kontak::where('phone', $data['phone'])->first();
        if (!$kontak) {
            // cek jumlah chat dari pengirim dengan status 0
            $chat_count = Chat::where('to_phone', $data['phone'])->where('status', 0)->count();
            $kontak = new Kontak();
            $kontak->user_id = $device->user_id;
            $kontak->device_id = $device->id;
            $kontak->phone = $data['phone'];
            $kontak->name = $data['name'];
            $kontak->status = $data['status'] ?? null;
            $kontak->last_seen = $data['last_seen'] ?? null;
            $kontak->profile_pic = $data['profile_pic'] ?? null;
            $kontak->keterangan = $data['keterangan'] ?? null;
            $kontak->new_chat = $chat_count;
            $kontak->save();
        }
        // cek jumlah chat dari pengrim
        $count = Chat::where('to_phone', $data['phone'])->count();
        // jika hanya 1 maka kirimkan pesan selamat datang
        // lewat whatsapp sender
        if ($count == 1) {
            $tempate_cw = TemplateCopywriting::where('type', 'pesan_sambutan')->where('status', 'active')->first();
            if ($tempate_cw) {
                $message = $tempate_cw->message;
                $kirim = Whatsapp::send($data['phone'], $message, null, $device->id);
                $this->info($kirim);
                $resp = json_decode($kirim, true);
                $message = new Chat();
                $message->user_id = $device->user_id;
                $message->device_id = $device->id;
                $message->from_phone = $device->phone;
                $message->to_phone = $data['phone'];
                $message->message = $message;
                $message->status = $resp['message'] == 'Terkirim' ? 1 : 2;
                $message->send_at = date('Y-m-d H:i:s');
                $message->msgid = $resp['data']['messageid'];
                $message->chat_type = 'out';
                $message->save();
            }
        }
    }
}
