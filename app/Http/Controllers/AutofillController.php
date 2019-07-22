<?php

namespace App\Http\Controllers;

use Faker\Factory as Faker;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;

class AutofillController extends Controller
{
    public function handler(Request $request)
    {
        $formId = $request->input('formId');
        $spinner = $request->input('spinner');
        $fields = $request->input('fields');
        // print_r($fields);
        $fields = json_decode($fields, true);
        // print_r(count($fields));
        // print_r($fields);
        $client = new \GuzzleHttp\Client();
        $url = 'https://api.jotform.com/form/' . $formId . '/submissions?apikey=cdcf7d48e2e6d0dfebae5f5393d51ef6';
        $upper_array = array();
        for ($i = 0; $i < $spinner; $i++) {
            $arr = array();
            foreach ($fields as $eachField) {
                $qid = $eachField['qid'];
                if ($eachField['type'] == 'control_fullname') {
                    $extra = $eachField['extra'];
                    $nameArr = self::genName($extra['p'], $extra['m'], $extra['s']);
                    if($extra['p'] == true && $extra['m'] == true  && $extra['s'] == true ){array_push($arr, [$qid => ["prefix" => $nameArr[0],"middle" => $nameArr[1],"suffix" => $nameArr[2], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == true && $extra['m'] == true  && $extra['s'] == false ){array_push($arr, [$qid => ["prefix" => $nameArr[0],"middle" => $nameArr[1], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == true && $extra['m'] == false  && $extra['s'] == true ){array_push($arr, [$qid => ["prefix" => $nameArr[0],"suffix" => $nameArr[2], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == false && $extra['m'] == true  && $extra['s'] == true ){array_push($arr, [$qid => ["middle" => $nameArr[1],"suffix" => $nameArr[2], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == true && $extra['m'] == false  && $extra['s'] == false ){array_push($arr, [$qid => ["prefix" => $nameArr[0], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == false && $extra['m'] == true  && $extra['s'] == false ){array_push($arr, [$qid => ["middle" => $nameArr[1], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if($extra['p'] == false && $extra['m'] == false  && $extra['s'] == true ){array_push($arr, [$qid => ["suffix" => $nameArr[2], "first" => $nameArr[3], "last" => $nameArr[4]]]);}
                    else if ($extra['p'] == false && $extra['m'] == false  && $extra['s'] == false) {$arr[$qid] = ["first" => $nameArr[3], "last" => $nameArr[4]];}
                } else if ($eachField['type'] == 'control_email') {
                    $arr[$qid] = ["email" => self::genEmail()];
                } else if ($eachField['type'] == 'control_address') {
                    //$faker->streetAddress, $faker->city, $faker->state, $faker->postCode
                    $addressArr = self::genAddress();
                    $arr[$qid] = ["addr_line1" => $addressArr[0], "city" => $addressArr[1], "state" => $addressArr[2]];
                 } else if ($eachField['type'] == 'control_phone') { 
                    $arr[$qid] = ["phone" => self::genPhone()];
                 } else if ($eachField['type'] == 'control_datetime') { 

                 } else if ($eachField['type'] == 'control_time') { 

                 } else if ($eachField['type'] == 'control_textbox') { 
                    $arr[$qid] = ["text" => self::genText()];
                 } else if ($eachField['type'] == 'control_textarea') { 

                 } else if ($eachField['type'] == 'control_dropdown') { 

                 } else if ($eachField['type'] == 'control_radio') { 

                 } else if ($eachField['type'] == 'control_checkbox') { 

                 } else if ($eachField['type'] == 'control_number') { 

                 } else if ($eachField['type'] == 'control_spinner') { }
                // print_r($eachField["qid"]);
                // print_r($eachField["type"]);
                // print_r($eachField["name"]);
            }
            if (!empty($arr))
                array_push($upper_array, $arr);
            // print_r($upper_array);
        }
        // echo "upper_array before json_encode: <br/>";
        // print_r($upper_array);
        // echo "<br/>upper_array after json_encode<br/>";
        // $upper_array = json_encode($upper_array);
        // print_r($upper_array);
        $r = $client->request('PUT', $url, [
            'json' => $upper_array
        ]);
        $upper_array = json_encode($upper_array);
        $link = 'https://www.jotform.com/submissions/'.$formId;
        return view('result', compact('upper_array','link','spinner'));
    }

    public function genName($prefix, $middle, $suffix)
    {
        $faker = Faker::create();
        $p = "";
        $m = "";
        $s = "";
        if ($prefix)
            $p = $faker->title;
        if ($middle)
            $m = $faker->firstName;
        if ($suffix)
            $s = $faker->suffix;
        $firstname = $faker->firstname;
        $lastname = $faker->lastname;
        return array($p, $m, $s, $firstname, $lastname);
    }

    public function genEmail()
    {
        $faker = Faker::create();
        return $faker->safeEmail;
    }

    public function genAddress()
    {
        $faker = Faker::create();
        return array($faker->streetAddress, $faker->city, $faker->state, $faker->postCode);
    }

    public function genPhone()
    {
        $faker = Faker::create();
        return $faker->tollFreePhoneNumber;
    }

    public function genDate($allowTime)
    {
        $faker = Faker::create();
        if ($allowTime) { } else { }
    }

    public function genTime()
    {
        $faker = Faker::create();
    }

    public function genText()
    {
        $faker = Faker::create();
        return $faker->realText($maxNbChars = 10, $indexSize = 2);
    }

    public function genLongText()
    {
        $faker = Faker::create();
        return $faker->realText($maxNbChars = 200, $indexSize = 2);
    }

    public function genDropdown($options)
    {
        return $options[rand(0, count($options))];
    }

    public function genRadio($list)
    {
        return $list[rand(0, count($list))];
    }

    public function genCheckboxes($list)
    {
        return $list[rand(0, count($list))];
    }

    public function genNumber($min, $max)
    {
        return rand($min, $max);
    }

    public function genSpinner($min, $max)
    {
        return rand($min, $max);
    }

    public function generator()
    {
        $faker = Faker::create();

        // generate data by accessing properties
        echo $faker->name;
        // 'Lucy Cechtelar';
        echo $faker->address;
        // "426 Jordy Lodge
        // Cartwrightshire, SC 88120-6700"
        echo $faker->text;
    }
}
