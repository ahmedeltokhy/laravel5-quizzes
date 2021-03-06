<?php

use Illuminate\Database\Seeder;

class QuizzesSeeder extends Seeder
{
	protected function set_name($sentence_count, $module_id, $reference_id)
	{
		$faker = Faker\Factory::create();
		$name = new App\Models\System\Name;
        $name->title = $faker->sentence($sentence_count);
        $name->module_id = $module_id;
        $name->reference_id = $reference_id;
        $name->language_id = 2; // en
        $name->save();
	}

	protected function set_user_choice_answers($choice_id)
	{
		if(mt_rand(0, 1)) {
			$answer = new App\Models\Users\Answer;
			$answer->auth_id = mt_rand(1, App\Models\Users\User::count());
			$answer->choice_id = $choice_id;
			$answer->save();
		}
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(1, 3) as $i) {
            $quiz = new App\Models\Quizzes\Quiz;
            $quiz->category_id = mt_rand(1, 5);
            $quiz->auth_id = 1;
            $quiz->save();
            $this->set_name(3, get_module_id('quizzes'), $quiz->id);

            foreach (range(1, 5) as $j) {
	            $question = new App\Models\Quizzes\Questions\Question;
	            $question->image = mt_rand(0, 1) ? "image$j" : null;
	            $question->quiz_id = $quiz->id;
	            $question->type_id = mt_rand(4, 5);
	            $question->save();
	            $this->set_name(7, get_module_id('quizzes.questions'), $question->id);

	            foreach (range(1, 3) as $k) {
		            $choice = new App\Models\Quizzes\Questions\Choice;
		            $choice->question_id = $question->id;
		            $choice->is_answer = mt_rand(0, 1);
		            $choice->save();
		            $this->set_name(3, get_module_id('quizzes.questions.choices'), $choice->id);
		            $this->set_user_choice_answers($choice->id);
	            }
            }
        }
    }
}
