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
        $message = Message::where('status', 0)->first();
        if ($message) {
            $kirim = Whatsapp::send($message->phone, $message->message, null, $message->device_id);
            $this->info($kirim);
            $resp = json_decode($kirim, true);
            $message->status = ($resp['message'] == 'Terkirim') ? 1 : 2;
            $message->send_at = date('Y-m-d H:i:s');
            $message->msgid = ($resp['message'] == 'Terkirim') ? $resp['data']['messageid'] : null;
            $message->save();            
            // $this->info('Berhasil mengirim pesan');            
        }
        sleep(rand(1, 20));
        self::handle();
    }
}
