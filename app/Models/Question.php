<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillabel = ['quizUid', 'question'];
    public function answers(){
        return $this->hasMany(Answer::class, 'answerUid','uid');
    }
    public function quiz(){
        return $this->hasOne(Quiz::class, 'uid','quizUid');
    }
    
}
