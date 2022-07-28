<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';
    protected $fillable = ['question_ar' , 'question_en' , 'answer_ar' , 'answer_en'];

    protected $appends = ['question', 'answer'];

    public function getQuestionAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->question_en : $this->question_ar;
    }

    public function getAnswerAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->answer_en : $this->answer_ar;
    }
}
