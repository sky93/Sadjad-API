<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    public function end_points()
    {
        return response()->json([
            'meta' =>
                [
                    'code' => 200,
                    'message' => 'OK'
                ],
            'data' =>
                [
                    'end_points' =>
                        [
                            '/v1/student_schedule'
                        ]
                ]
        ], 200);
    }

    public function stu_class(Request $request)
    {
        $errors = [];
        $time_start = $this->microtime_float();
        if (! $request->input('username')){
            $errors[] = 'username is not provided.';
        }
        if (! $request->input('password')){
            $errors[] = 'password is not provided.';
        }
        if (count($errors)) {
            return response()->json([
                'meta' =>
                    [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'error' => $errors
                    ]
            ], 400);
        }
        $auth = http_build_query([
            'StID' => $request->input('username'),
            'UserPassword' => $request->input('password')
        ]);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'http://stu.sadjad.ac.ir/Interim.php');
        curl_setopt($ch,CURLOPT_POST, 2);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$auth);
//        curl_setopt($ch,CURLOPT_POSTFIELDS,'StID=93412147&UserPassword=09372759548');
        //curl_setopt($ch,CURLOPT_POSTFIELDS,'StID=92412147&UserPassword=23982398');
        //curl_setopt($ch,CURLOPT_POSTFIELDS,'StID=92412180&UserPassword=highclass');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '-');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = array();
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $header_size);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_setopt($ch,CURLOPT_URL,'http://stu.sadjad.ac.ir/strcss/ShowStSchedule.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        $dom = new \domDocument;

        @$dom->loadHTML($result);
        if (strpos($dom->textContent, ' درخواستبنا به دلایل امنیتی ادامه استفاده شما از سیستم منوط به ورود مجدد به سیستم استلطفا برای ورود مجدد ب')){
            $time_end = $this->microtime_float();
            $time = $time_end - $time_start;

            return response()->json([
                'meta' =>
                    [
                        'code' => 403,
                        'message' => 'Forbidden',
                        'connect_time' => $time
                    ],
            ], 403);
        }
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('table');
        $rows = $tables->item(1)->getElementsByTagName('tr');
        $raw = [];
        foreach ($rows as $row) {
            $tds = $row->getElementsByTagName('td');
            foreach ($tds as $td) {
                $raw[] = $td->textContent;
            }
        }
        $day = [
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []],
            ['odd' => [], 'even' => []]

        ];
        $day_iterate = -1;
        $i = 0;
        $hour = 0;
        $time = 5;
        while(1){
            if ($raw[$i] == 'جمعه') {
                break;
            }
            if ($raw[$i] == 'شنبه' || $raw[$i] == 'یکشنبه' || $raw[$i] == 'دوشنبه' || $raw[$i] == 'سه شنبه' || $raw[$i] == 'چهارشنبه' || $raw[$i] == 'پنجشنبه'){
                $hour = 0;
                $day_iterate++;
                $time = 6;
            } elseif ($raw[$i] == ' ') {
                $time++;
                $hour++;
            } else {
                $cl = str_replace('(0)','', $raw[$i]);
                $cl = str_replace('*','', $cl);
                $cl = str_replace('.00','', $cl);
                $cl = str_replace(',',' - ', $cl);
                $cl = str_replace('كلاس','', $cl);
                $cl = str_replace('  ',' ', $cl);

                if (strpos($cl, 'زوج')) {
                    array_push($day[$day_iterate]['even'], [
                        'time' => $time,
                        'subject' => $cl
                    ]);
                } elseif (strpos($cl, 'فرد')) {
                    array_push($day[$day_iterate]['odd'], [
                        'time' => $time,
                        'subject' => $cl
                    ]);
                } else {
                    array_push($day[$day_iterate]['even'], [
                        'time' => $time,
                        'subject' => $cl
                    ]);
                    array_push($day[$day_iterate]['odd'], [
                        'time' => $time,
                        'subject' => $cl
                    ]);
                }
                if (strpos($raw[$i], 'پروژه') === false){
                    $time += 2;
                    $hour += 2;
                }else{
                    $time += 1;
                    $hour += 1;
                }



            }
            if ($hour >= 15) {
                $hour = 0;
                $time = 6;
            }

            $i++;
        }

        $final_days = [];
        $final_days[] = [
            'name_of_week' => 'شنبه',
            'day_of_week' => 0,
            'classes' => $day[0]
        ];
        $final_days[] = [
            'name_of_week' => 'یکشنبه',
            'day_of_week' => 1,
            'classes' => $day[1]
        ];
        $final_days[] = [
            'name_of_week' => 'دوشنبه',
            'day_of_week' => 2,
            'classes' => $day[2]
        ];
        $final_days[] = [
            'name_of_week' => 'سه‌شنبه',
            'day_of_week' => 3,
            'classes' => $day[3]
        ];
        $final_days[] = [
            'name_of_week' => 'چهارشنبه',
            'day_of_week' => 4,
            'classes' => $day[4]
        ];
        $final_days[] = [
            'name_of_week' => 'پنج‌شنبه',
            'day_of_week' => 5,
            'classes' => $day[5]
        ];

//        echo json_encode(, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//echo $result;




        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        return response()->json([
            'meta' =>
                [
                    'code' => 200,
                    'message' => 'OK',
                    'connect_time' =>$time
                ],
            'data' => $final_days
        ]);
    }
}
