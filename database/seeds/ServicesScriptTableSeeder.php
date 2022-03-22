<?php

use Illuminate\Database\Seeder;
use App\Answer;
use App\Channel;
use App\Question;

class ServicesScriptTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channel_id =Channel::firstWhere('title','Service')->id;

        //Quiestion 1
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Good {{$day_time}} my name is {{$agent_name}} and I am calling from DT Dobie {{$branch_name}}. Am I speaking to {{$customer_name}}?</p>',
            'priority' => 1
        ]);


        //Answer for Quiz 1
        Answer::Create(array(
            [
                'question_id'=>array($quiz->id),
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>2,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>array($quiz->id),
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>10,
                'has_text_box'=>false,
                'has_date_picker'=>false
        ]));

        //Question 2
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Is this time convenient to speak to you?</p>',
            'priority' => 2
        ]);

        //Answer for Quiz 1
        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>3,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>11,
                'has_text_box'=>false,
                'has_date_picker'=>true //Rerseve call back
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"Not Interested",
                'real_answer'=>"Not Interested",
                'redirect_to'=>11,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]

        ]);

        //Question 3
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Thank you, I am making an after-service call regarding your vehicle KCN016W which we serviced recently</p>
            <p>On a scale of 0-10 (0 - Very Unlikely, 10 - Very likely) - How likely is it that you would recommend DT Dobie to family, friends, colleagues?</p>',
            'priority' => 3
        ]);

        //Answer for Quiz 3

        for ($x = 0; $x <= 10; $x++) {

            Answer::Create([
                [
                    'question_id'=>$quiz->id,
                     'answer' =>$x,
                     'real_answer'=>$x,
                     'redirect_to'=>4,
                     'has_text_box'=>true,
                     'has_date_picker'=>false
                ]
            ]);


        }

        //Question 4
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Please tell us why you gave this score.</p>',
            'priority' => 4
        ]);
        //Comment type
        //Department
        //Comment Summary
        //Action required
        //Question 5
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Did the service advisor offer to inspect your vehicle with you before the works started? </p>',
            'priority' => 5
        ]);

        //Answer for Quiz 5
        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>6,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>6,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]
            

        ]);

        //Question 6
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text' => '<p>Did you receive an explanation of the actual work after the service was completed? </p>',
            'priority' => 6
        ]);

        //Answer for Quiz 6
        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>7,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>7,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]
            

        ]);

        //Question 7
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text'=>'<p>Was your vehicle fixed right?</p>',
            'priority' => 7
        ]);

        //Answer for Quiz 7
        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>8,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>8,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]
            

        ]);

        //Question 8
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text'=>'<p>Were the repairs/Maintenance completed within the advised time?</p>',
            'priority' => 8
        ]);

        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>9,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>9,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]
            

        ]);

        //Question 9
        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text'=>'<p>Do you feel the time taken to service/repair your vehicle was reasonable? </p>',
            'priority' => 9
        ]);

        Answer::Create([
            [
                'question_id'=>$quiz->id,
                 'answer' =>"Yes",
                 'real_answer'=>"Yes",
                 'redirect_to'=>11,
                 'has_text_box'=>false,
                 'has_date_picker'=>false
            ],
            [
                'question_id'=>$quiz->id,
                'answer' =>"No",
                'real_answer'=>"No",
                'redirect_to'=>11,
                'has_text_box'=>false,
                'has_date_picker'=>false
            ]
            

        ]);

        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text'=>'<p>Kindly share with us your experience of the services we provide</p>',
            'priority' => 10
        ]);

        //Comment type
        //Department
        //Comment Summary
        //Action required

        $quiz=Question::Create([
            'channel_id' => $channel_id,
            'question_text'=>'<p>Thank you for choosing DT Dobie, We appreciate your time have a good day. </p>',
            'priority' => 11
        ]);


    }
}
