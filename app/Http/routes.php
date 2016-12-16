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


$app->group(['prefix' => 'v1'], function () use ($app) {
    $app->get('student_schedule', ['uses' => 'ApiController@stu_class']);
    $app->post('student_schedule', ['uses' => 'ApiController@stu_class']);

    $app->get('internet_credit', ['uses' => 'ApiController@internet_credit']);
    $app->post('internet_credit', ['uses' => 'ApiController@internet_credit']);

    $app->get('self_service_credits', ['uses' => 'ApiController@self_service_credits']);
    $app->post('self_service_credits', ['uses' => 'ApiController@self_service_credits']);

    $app->get('self_service_menu', ['uses' => 'ApiController@self_service_menu']);
    $app->post('self_service_menu', ['uses' => 'ApiController@self_service_menu']);

    $app->get('exams', ['uses' => 'ApiController@exams']);
    $app->post('exams', ['uses' => 'ApiController@exams']);

    $app->get('library', ['uses' => 'ApiController@library']);
    $app->post('library', ['uses' => 'ApiController@library']);
});

$app->group(['prefix' => 'v2'], function () use ($app) {

    $app->group(['prefix' => 'stu'], function () use ($app) {

        $app->get('schedule', ['uses' => 'ApiController@stu_class']);
        $app->post('schedule', ['uses' => 'ApiController@stu_class']);

        $app->get('profile', ['uses' => 'ApiController@v2_stu_profile']);
        $app->post('profile', ['uses' => 'ApiController@v2_stu_profile']);

    });

});


