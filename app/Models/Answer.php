<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillabel = ['questionUid', 'answer', 'isRight'];
    protected $hidden = [
        'isRight'
    ];
    public function question(){
        return $this->hasOne(Question::class, 'answerUid','uid');
    }    
}
