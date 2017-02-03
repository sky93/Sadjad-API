<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\lib\jDateTime;
use App\lib\functions;

class InternetController extends Controller
{

    private function IBSng_login($username, $password)
    {
        $status = 'OK';
        $errors = [];
        $response = null;

        if (! $username ){
            $errors[] = 'username is not provided.';
        }
        if (! $password ){
            $errors[] = 'password is not provided.';
        }
        if (count($errors)) {
            return [
                'status' => 'WRONG',
                'response' => response()->json([
                    'meta' =>
                        [
                            'code' => 400,
                            'message' => 'Bad Request',
                            'error' => $errors
                        ]
                ], 400)
            ];
        }

        $auth = http_build_query([
            'normal_username' => functions::number_fix($username),
            'normal_password' => functions::number_fix($password)
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'http://178.236.34.178/IBSng/user/');
        curl_setopt($ch, CURLOPT_POST,2);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '-');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [];
        $headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ( strpos(curl_exec($ch), 'Username or Password is incorrect') ) {
            $status = 'FORBIDDEN';
        }

        return [
            'status' => $status,
            'curl_object' => $ch
        ];

    }

    private function bytes_to_unit($bytes)
    {
        $unit  = $bytes[strlen($bytes) - 1];
        $bytes = substr($bytes, 0, -1);
        switch ($unit) {
            case 'K':
                $bytes *= 1024;
                break;
            case 'M':
                $bytes *= 1024 * 1024;
                break;
            case 'G':
                $bytes *= 1024 * 1024 * 1024;
                break;
            case 'T':
                $bytes *= 1024 * 1024 * 1024 * 1024;
                break;
            case 'P':
                $bytes *= 1024 * 1024 * 1024 * 1024 * 1024;
                break;
        }
        return $bytes;
    }


    public function credits(Request $request)
    {
        $time_start = functions::microtime_float();

        $login = $this->IBSng_login( $request->input('username'), $request->input('password') );

        if ( $login['status'] == 'WRONG' ) {
            return $login['response'];
        }

        if ( $login['status'] == 'FORBIDDEN' ) {
            $time_end = functions::microtime_float();
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

        $ch = $login['curl_object'];
        curl_setopt($ch, CURLOPT_URL,'http://178.236.34.178/IBSng/user/home.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);

        $cr = functions::get_string_between($result, '<td class="Form_Content_Row_Left_2Col_light"> مقداراعتبار فعلی :</td>'," UNITS</td>");
        $cr = str_replace(',','', $cr);
        $cr = str_replace('<td class="Form_Content_Row_Right_2Col_light">','', $cr);
        $v = trim($cr);

        $time_end = functions::microtime_float();
        $time = $time_end - $time_start;
        return response()->json([
            'meta' =>
                [
                    'code' => 200,
                    'message' => 'OK',
                    'connect_time' =>$time
                ],
            'data' => [
                'remaining_credits' => round($v * 1024 * 1024),
                'remaining_credits_formatted' => functions::formatBytes(round($v * 1024 * 1024)),
            ]
        ]);
    }


    public function connection_report(Request $request)
    {
        $time_start = functions::microtime_float();

        $login = $this->IBSng_login( $request->input('username'), $request->input('password') );

        if ( $login['status'] == 'WRONG' ) {
            return $login['response'];
        }

        if ( $login['status'] == 'FORBIDDEN' ) {
            $time_end = functions::microtime_float();
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

        $post_fields = [
            'show_reports' => '1',
            'page' => $request->input('page') ? $request->input('page') : '1',
            'search' => '1',
            'login_time_from' => date("Y/m/d H:i", $request->input('start') ? $request->input('start') : time()),
            'login_time_from_unit' => 'gregorian',
            'login_time_to' => date("Y/m/d H:i", $request->input('end') ? $request->input('end') : time()),
            'login_time_to_unit' => 'gregorian',
            'successful' => 'All',
            'service' => 'Internet',
            'order_by' => 'login_time',
            'desc' => 'on',
            'rpp' => '100',
            'view_options' => '3',
            'Login_Time' => 'show__login_time_formatted',
            'Logout_Time' => 'show__logout_time_formatted',
            'Duration' => 'show__duration_seconds|duration',
            'Credit_Used' => 'show__credit_used|price',
            'Successful' => 'show__successful|formatBoolean',
            'Bytes_OUT' => 'show__bytes_out|byte',
            'Bytes_IN' => 'show__bytes_in|byte',
            'x' => '40',
            'y' => '7',
            'show_total_credit_used' => '1',
            'show_total_duration' => '1',
            'show_total_inouts' => '1',
            'tab1_selected' => 'شرایط'
        ];

        $ch = $login['curl_object'];
        curl_setopt($ch, CURLOPT_URL,'http://178.236.34.178/IBSng/user/connection_log.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);
        if ( strpos($result, '<html>') ) {
            $time_end = functions::microtime_float();
            $time = $time_end - $time_start;
            return response()->json([
                'meta' =>
                    [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'connect_time' =>$time
                    ]
            ], 400);
        }
        $raw_csv = explode("\n", $result);
        unset($raw_csv[0]);
        unset($raw_csv[(count($raw_csv))]);

        $clean_csv_array = [];

        foreach ($raw_csv as $item) {
            $clean_csv_array[] = [
                'time' => [
                    'login' => [
                        'formatted_date_time' => str_getcsv($item)[1],
                        'unix_date_time' => strtotime(str_getcsv($item)[1])
                    ],
                    'logout' => [
                        'formatted_date_time' => str_getcsv($item)[2],
                        'unix_date_time' => strtotime(str_getcsv($item)[2])
                    ]
                ],
                'duration' => [
                    'seconds' => strtotime(str_getcsv($item)[2]) - strtotime(str_getcsv($item)[1]),
                    'formatted' => str_getcsv($item)[3]
                ],
                'successful' => str_getcsv($item)[5] == 't' ? true : false,
                'traffic' => [
                    'in' => [
                        'input_traffic' => round($this->bytes_to_unit(str_getcsv($item)[6])),
                        'input_traffic_formatted' => functions::formatBytes(round($this->bytes_to_unit(str_getcsv($item)[6])))

                    ],
                    'out' => [
                        'output_traffic' => round($this->bytes_to_unit(str_getcsv($item)[7])),
                        'output_traffic_formatted' => functions::formatBytes(round($this->bytes_to_unit(str_getcsv($item)[7])))
                    ]
                ]
            ];
        }

        $time_end = functions::microtime_float();
        $time = $time_end - $time_start;
        return response()->json([
            'meta' =>
                [
                    'code' => 200,
                    'message' => 'OK',
                    'connect_time' =>$time
                ],
            'data' => $clean_csv_array

        ]);
    }

}
