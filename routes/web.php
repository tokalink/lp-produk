<?php

use App\Helpers\Whatsapp;
use App\Http\Controllers\AdminChatsController;
use App\Http\Controllers\AdminAdminController;
use App\Http\Controllers\AdminMessagesController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\OrderController;
use App\Models\Device;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', [LandingpageController::class, 'index']);

Route::get('/form-detail', [LandingpageController::class, 'formDetail']);
Route::any('/registrasi', [LandingpageController::class, 'registrasi']);
// Route::get('/{kategori?}', [LandingpageController::class, 'index']);

Route::get('/', [OrderController::class, 'show']);
Route::get('/order', [OrderController::class, 'show']);
Route::get('/order/{slug?}', [OrderController::class, 'show']);

Route::any('/send-chat', [AdminChatsController::class, 'sendReply']);

Route::get('/wa-status/{id}', function ($id) {
    $device = Device::find($id);
    if ($device) {
        return Whatsapp::status($device->id);
    }
});

// Uploads File excel ke messages 
Route::post('/messages/import', [AdminMessagesController::class, 'getImport'])->name('messages.import');
Route::get('/admin/messages/send-draft', [AdminMessagesController::class, 'sendDraft'])->name('messages.send-draft');

Route::get('/admin', [AdminAdminController::class, 'getIndex']);