<?php

namespace Tests\Unit;

use App\Answer;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    // 单元测试-作用是写出简洁无错的代码，代码非常少，而且相对独立的一部分代码来测试，大部分单元测试针对单个方法进行的。
    // 单元测试由功能测试驱动，而且更接近于真正的代码。
    public function a_question_has_many_answers()
    {
        $question = factory(Question::class)->create();

        factory(Answer::class)->create(['question_id' => $question->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $question->answers());
    }
}
