<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $quiz = Quiz::with('questions', 'questions.answers');
        if($request->input('uid')){
            $quiz = $quiz->where('uid', $request->input('uid'))->first();
        }else if($request->input('page')){
            $perPage=20;
            if($request->input('perPage')){
                $perPage=$request->input('perPage');
            }
            $quiz = $quiz->paginate($perPage);
        }
        else{
            $quiz = $quiz->get();
        }
        return [
            "code" => 20000,
            "success" => true,
            "errors" => null,
            "data" => $quiz
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quiz = new Quiz();
        $quiz->fill($request->input());
        $quiz->uid = (string) Str::uuid();
        $quiz->save();
        foreach($request->input('questions') as $question){
            $question_create = new Question();
            $question_create->fill($question);
            $question_create->uid = (string) Str::uuid();
            $question_create->quizUid = $quiz->uid;
            $question_create->save();
            foreach($question['answers'] as $answer){
                $answer_create = new Answer();
                $answer_create->fill($answer);
                $answer_create->uid = (string) Str::uuid();
                $answer_create->questionUid = $question_create->uid;
                $answer_create->save();
            }
        }
        return [
            "code" => 20000,
            "success" => true,
            "errors" => null,
            "data" => $quiz->uid
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $errors = [];
        $message = "";
        $answers = [];
        $correct = true;
        $rightAnswer=0;
        $wrongAnswer  = 0;

        if($request->uid && $request->questions){ // get quiz uid
            $actualNumberOfQuestions = Question::where('quizUid', $request->uid)->count();
            foreach($request->questions as $question){
                $correct = true;
                if(array_key_exists("uid",$question) && array_key_exists("answerUid", $question)){
                    $right = Answer::where('questionUid', $question['uid'])->where('isRight', true)->where('uid', $question['answerUid'])->first();
                    if($right){
                        $rightAnswer++;
                    }else{
                        $wrongAnswer++;
                        $correct = false;
                    }
                }else{
                    $message = $message . " Question uid missing,";
                    array_push($errors, '002');
                }
                array_push($answers, ["question"=>$question, "correct"=>$correct]);
            }
        }
        else{
            $message = $message . " Quiz uid missing";
            array_push($errors, '001');
        }
        if(count($errors)>0){
            $message = "Error " . $message;            
        }else {
            $message = "success";        
        }

        return [
            "code" => 20000,
            "success" => count($errors)>0?false:true,
            "message"=>$message,
            "errors" => count($errors)>0?$errors:NULL,
            "data" =>[
                "actualNumberOfQuestions" => $actualNumberOfQuestions,
                "rigthQuantity" => $rightAnswer,
                "wrongQuantity" => $wrongAnswer,
                "answers" => $answers
            ]
        ];
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(quiz $quiz)
    {
        //
    }
}
