<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class,'index'])->name('login');
Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('do-register', [AuthController::class, 'doRegister'])->name('do-register');;
Route::post('do-login',[AuthController::class,'checkLogin'])->name('do-login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/details/{id}', [EventController::class, 'details'])->name('events.detail');
    Route::get('/export-events', [EventController::class, 'export'])->name('events.export');
    Route::middleware('role:organizer')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/attendees/{id}', [EventController::class, 'attendees'])->name('events.attendees');
        Route::delete('events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::put('events/{id}', [EventController::class, 'update'])->name('events.update');
    });
    
    Route::middleware('role:attendee')->group(function () {
        Route::post('/create-payment-request', [PaymentController::class, 'createPaymentRequest'])->name('createpaymentrequest');
        Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    });

    
});
Route::post('/webhook', [PaymentController::class, 'handleWebhook']);        

// Route::get('/{short_url}', [UrlController::class, 'redirect'])->name('url.redirect');
