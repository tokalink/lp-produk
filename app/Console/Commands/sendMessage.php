<?php

namespace App\Console\Commands;

use App\Helpers\Whatsapp;
use App\Models\Message;
use Illuminate\Console\Command;

class sendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ambil data pesan dengan status 0
        $message = Message::where('status', 1)->first();
        if ($message) {
            $kirim = Whatsapp::send($message->phone, $message->message, null, $message->device_id);
            $this->info($kirim);
            $resp = json_decode($kirim, true);
            if($resp['message'] == 'Terkirim'){
                $message->status = 2;
                $message->msgid = $resp['data']['messageid'];
                $message->send_at = date('Y-m-d H:i:s');
            }elseif($resp['message'] == 'Belum Terdaftar'){
                $message->status = 3;
            }else{
                $message->status = 0;
            }            
            $message->save();            
            // $this->info('Berhasil mengirim pesan');            
        }
        sleep(rand(1, 20));
        self::handle();
    }
}
