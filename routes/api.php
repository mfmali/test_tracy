<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// no param -> all quizes | param {uid} -> one quiz  | param {page} -> paginate with 20 per Page | param {page, perPage} -> paginate with perPage
Route::get('/quizzes', [QuizController::class, 'index']);

// accepted data formate ->  {'title', 'description', 'isPublished', 'questions':[{'question', 'answers':[{ 'answer', 'isRight' }] }] }
Route::post('/quizzes', [QuizController::class, 'store']);

// accepted data formate ->  {'uid'-uid of the quiz-, 'questions':[{'uid'-uid of question-, 'answerId'-id of selected answer, 0 if not selected- OR 'answerUid'--uid of selected answer, 0 if not selected-- }] }
Route::post('/quiz/result/submit', [QuizController::class, 'check']);
