<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;

class AppController extends Controller
{
    public function index()
    {
        return view('selectForm');
    }
    public function handler(Request $request)
    {
        $formId = $request->input('formId');
        if ($formId == null) {
            return redirect()->action('AppController@index');
        }
        // $formId = 91826823855973; // :3 address
        // var_dump($formId); exit();
        // https://api.jotform.com/form/91893585128975/questions?apikey=17fd20c3752ed60a494845250b
        $base_uri0 = 'https://api.jotform.com/form/' . $formId . '/questions?apikey=cdcf7d48e2e6d0dfebae5f5393d51ef6';
        $client = new \GuzzleHttp\Client(['base_uri' => $base_uri0]);
        // Send a request to https://foo.com/api/test
        $response = $client->request('GET', '')->getBody();
        $response = json_decode($response);
        $fields = array();
        for ($i = 0; $i < 1000; $i++) { //assuming max number of 1000 questions
            if (isset($response->content->$i)) {
                $isRequired = 0;
                if (isset($response->content->$i->required)) {
                    if ($response->content->$i->required == "Yes") {
                        $isRequired = 1;
                    } else {
                        $isRequired = 0;
                    }
                } else {
                    $isRequired = 0;
                }
                //fill in the extras
                $extra = array();
                switch ($response->content->$i->type) {
                    case 'control_fullname':
                        $p = $response->content->$i->prefix == 'Yes' ? true : false;
                        $m = $response->content->$i->middle == 'Yes' ? true : false;
                        $s = $response->content->$i->suffix == 'Yes' ? true : false;
                        $extra = array('p' => $p,'m' => $m,'s' => $s);
                        break;
                    case 'control_email':
                        break;
                    case 'control_address':
                        break;
                    case 'control_phone':
                        break;
                    case 'control_datetime':
                        # code... //TODO: LOOK AGAIN
                        break;
                    case 'control_time':
                        break;
                    case 'control_textbox':
                        break;
                    case 'control_textarea':
                        break;
                    case 'control_dropdown':
                        $extra = explode('|', $response->content->$i->options);
                        break;
                    case 'control_radio':
                        $extra = explode('|', $response->content->$i->options);
                        break;
                    case 'control_checkbox':
                        $extra = explode('|', $response->content->$i->options);
                        break;
                    case 'control_number':
                        $min = explode('|', $response->content->$i->minValue);                        
                        $max = explode('|', $response->content->$i->maxValue);                                                
                        $extra = array('min' => $min,'max' => $max);
                        break;
                    case 'control_spinner':
                        $min = explode('|', $response->content->$i->minValue);                        
                        $max = explode('|', $response->content->$i->maxValue);        
                        break;
                }
                array_push($fields, array(
                    'qid' => $response->content->$i->qid,
                    'type' => $response->content->$i->type, 'isSupported' => self::isSupported($response->content->$i->type),
                    'isRequired' => $isRequired, 'name' => $response->content->$i->name, 'extra' => $extra
                ));
            }
        }

        // $form_fields = json_encode($fields);

        // return response()->json([
        //     'form_fields' => $form_fields,
        // ], 200);
        return view('status', compact('fields', 'formId'));
    }

    public function isSupported($needle)
    {
        $skipList = ['control_head', 'control_button'];
        $supportedList = [
            'control_fullname', 'control_email', 'control_address', 'control_phone',
            'control_textbox', 'control_textarea',
            'control_number', 'control_spinner'
        ];
        if (array_search($needle, $skipList) !== false) {
            return -1;
        } else if (array_search($needle, $supportedList) !== false) {
            return array_search($needle, $supportedList); //returns index
        } else {
            return -2;
        }
    }
}
