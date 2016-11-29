<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', ['uses' => 'ApiController@end_points']);

$app->get('/v1/student_schedule', ['uses' => 'ApiController@stu_class']);
$app->post('/v1/student_schedule', ['uses' => 'ApiController@stu_class']);

$app->get('/v1/internet_credit', ['uses' => 'ApiController@internet_credit']);
$app->post('/v1/internet_credit', ['uses' => 'ApiController@internet_credit']);
