<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillabel = ['title', 'description', 'isPublished'];
    public function questions(){
        return $this->hasMany(Question::class, 'quizUid','uid');
    }
}
