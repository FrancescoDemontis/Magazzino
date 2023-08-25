<?php

use Illuminate\Http\Request;
use App\Http\Middleware\UserIsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/users', [UserController::class, 'index']);
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');
// Route::get('/article', [ArticleController::class, 'index'])->name('article');
Route::get('/article', [ArticleController::class, 'index'])->name('article');
Route::post('/article/create', [ArticleController::class, 'store']);
Route::put('/articleupdate/{id}', [ArticleController::class, 'update']);
Route::delete('/articledelete/{id}', [ArticleController::class, 'destroy']);
Route::get('/article/detail/{article_id}', [ArticleController::class, 'show'])->name('article.detail');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/product/request', [ProductController::class, 'submitRequest']);
    Route::get('/orders', [ProductController::class, 'index']);
   
});

Route::get('/download-orders', [ProductController::class, 'downloadExcel']);

Route::middleware('auth:sanctum')->group(function () {


    Route::get('/user', function (Request $request) {
         return response()->json(['user' => $request->user()]);
            });
            
    Route::get('/admin', function (Request $request) {
        return response()->json(['admin' => $request->user()]);
            });
     Route::get('/user-role', function (Request $request) {
        
        return response()->json(['role' => $request->user()->role]);
            });
     Route::post('/article/request', [ArticleController::class, 'richiesta']);
     Route::get('/admin/request', [ArticleController::class, 'viewRequests']);
     Route::post('/accept/article/request/{requestId}', [ArticleController::class, 'acceptRequest']);
     Route::post('/reject/article/request/{requestId}', [ArticleController::class, 'rejectRequest']);
     Route::post('product/request/{id}/accept', [ProductController::class, 'acceptRequest']);
     Route::post('product/request/{id}/reject', [ProductController::class, 'rejectRequest']);
  

});


Route::middleware('auth:sanctum')->group(function () {
    // Rotte per il controller delle categorie
    Route::get('/all/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});


Route::post('/login',[UserController::class,'login']);
Route::post('/registrazione',[UserController::class,'registrazione']);
// Route::post('/logout', [UserController::class, 'logout']);



Route::post('/filterCategory', [ProductController::class, 'filterByCategory']);
Route::post('/filterByName', [ProductController::class, 'filterByName']);
Route::post('/sortBy', [ProductController::class, 'sortBy']);
Route::post('/filter', [ProductController::class, 'filter']);


Route::post('/filterDataRequest', [ArticleController::class, 'filterDataRequest']);
Route::post('/sortBy', [ArticleController::class, 'sortBy']);
Route::post('/filterUserRequest', [ArticleController::class, 'filterUserRequest']);
Route::get('/stats', [ArticleController::class, 'getMonthlyRequestStats']);