<?php

use App\Events\VideoCompleted;
use App\Events\VideoEncoding;
use App\Events\VideoUploading;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\MessageSent;

//Route::get('/', [HomeController::class, 'index']);

Route::get('/debug-server', function () {
    return response()->json([
        'server_hostname' => gethostname(), // Սա կվերադարձնի կոնտեյների ID-ն
        'ip_address' => $_SERVER['SERVER_ADDR']
    ]);
});


Route::get('/', function () {
    return view('chat');
});

Route::post('/send-message', function (Request $request) {

    broadcast(new MessageSent($request->message));

    return response()->json([
        'success' => true,
    ]);
});

Route::get('/video', function () {
    return view('video');
});

Route::post('/process-video', function () {

    broadcast(new VideoUploading('Uploading video...'));

    sleep(3);

    broadcast(new VideoEncoding('Encoding video...'));

    sleep(3);

    broadcast(new VideoCompleted('Video processing completed ✅'));

    return response()->json([
        'success' => true
    ]);
});
