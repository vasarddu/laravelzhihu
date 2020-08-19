<?php

namespace Tests\Feature;

use App\Question;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

//    public function testUserCanViewQuestions()
//    {
//        // 0. 抛出异常
//        $this->withoutExceptionHandling();
//        // 1.假设/questions 路由存在 2. 访问链接 /questions
//        $test = $this->get('/questions');
//        // 3. 正常返回 200
//        $test->assertStatus(200);
//    }

    // 推荐使用这种，具有更佳的可读性
    /** @test **/
    public function user_can_view_questions()
    {
        // 0. 抛出异常
        $this->withoutExceptionHandling();
        // 1.假设/questions 路由存在 2. 访问链接 /questions
        $test = $this->get('/questions');
        // 3. 正常返回 200
        $test->assertStatus(200);
    }

    /** @test **/
    public function user_can_view_a_single_question()
    {
        // 1. 创建一个问题
        $question = factory(Question::class)
            ->create(['published_at' => Carbon::parse('-1 week')]);
        // 2. 访问链接
        $test = $this->get('/questions/' . $question->id);
        // 3. 应该看到内容
        $test->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test **/
    public function user_can_view_a_published_question()
    {
        $question = factory(Question::class)
            ->create(['published_at' => Carbon::parse('-1 week')]);

        $this->get('/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test **/
    public function user_cannot_view_unpublised_question()
    {
        $question = factory(Question::class)->create(['published_at' => null]);

        // 要生成http响应
        $this->withExceptionHandling()
            ->get('/questions/'. $question->id)
            ->assertStatus(404);
    }

    /** @test **/
    public function questions_with_published_at_date_are_published()
    {
        /*
        $publishedQuestion1 = factory(Question::class)->create([
            'published_at' => Carbon::parse("-1 week")
        ]);
        $publishedQuestion2 = factory(Question::class)->create([
            'published_at' => Carbon::parse("-1 week")
        ]);
        $unpublisedQuestion = factory(Question::class)->create();
        */
        $publishedQuestion1 = factory(Question::class)->state('published')->create();
        $publishedQuestion2 = factory(Question::class)->state('published')->create();
        $unpublisedQuestion = factory(Question::class)->state('unpublished')->create();

        $publishedQuestions = Question::published()->get();

        $this->assertTrue($publishedQuestions->contains($publishedQuestion1));
        $this->assertTrue($publishedQuestions->contains($publishedQuestion2));
        $this->assertFalse($publishedQuestions->contains($unpublisedQuestion));
    }
}
