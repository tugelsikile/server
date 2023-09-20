<?php

namespace Database\Seeders;

use App\Models\Course\Course;
use App\Models\Course\CourseTopic;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamClient;
use App\Models\Major\Major;
use App\Models\Question\Answer;
use App\Models\Question\Question;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class TestDatabase extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('level','participant')->count() == 0) {
            $this->command->line('participant factory');
            \App\Models\User\User::factory(100)->create();
        }
        if (Exam::all()->count() == 0) {
            $this->command->line('exam factory');
            Exam::factory(10)->create();
        }
        if (ExamClient::all()->count() == 0) {
            $this->command->line('client factory');
            ExamClient::factory(10)->create();
        }
        if (Major::all()->count() == 0) {
            $this->command->line('major factory');
            Major::factory(10)->create();
        }
        if (Course::all()->count() == 0) {
            $this->command->line('course factory');
            Course::factory(20)->create();
        }
        if (CourseTopic::all()->count() == 0) {
            $this->command->line('topic factory');
            CourseTopic::factory(30)->create();
        }
        if (Question::all()->count() < 2000) {
            $this->command->line('question factory');
            Question::factory(100)->create();
        }
        if (Question::all()->count() > 0) {
            $this->command->line('answer factory');
            $this->command->getOutput()->progressStart(Question::all()->count());
            foreach (Question::all() as $question) {
                if (Answer::where('question', $question->id)->count() == 0) {
                    if ($question->type == 'multi_choice') {
                        Answer::factory(5)->create(['question' => $question->id]);
                    } else {
                        Answer::factory(1)->create(['question' => $question->id]);
                    }
                }
                $question->max_score = Answer::where('question', $question->id)->sum('score');
                $question->save();
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
        }
    }
}
