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
    $app->get('internet_credit', ['uses' => 'ApiController@internet_credit']);
    $app->post('internet_credit', ['uses' => 'ApiController@internet_credit']);

    $app->get('self_service_credits', ['uses' => 'ApiController@self_service_credits']);
    $app->post('self_service_credits', ['uses' => 'ApiController@self_service_credits']);

    $app->get('self_service_menu', ['uses' => 'ApiController@self_service_menu']);
    $app->post('self_service_menu', ['uses' => 'ApiController@self_service_menu']);

    $app->get('exams', ['uses' => 'StuController@exams']);
    $app->post('exams', ['uses' => 'StuController@exams']);

    $app->get('library', ['uses' => 'ApiController@library']);
    $app->post('library', ['uses' => 'ApiController@library']);
});

$app->group(['prefix' => 'v2'], function () use ($app) {

    $app->group(['prefix' => 'stu'], function () use ($app) {

        $app->get('schedule', ['uses' => 'StuController@stu_class']);
        $app->post('schedule', ['uses' => 'StuController@stu_class']);

        $app->get('profile', ['uses' => 'StuController@v2_stu_profile']);
        $app->post('profile', ['uses' => 'StuController@v2_stu_profile']);

        $app->get('exam_card', ['uses' => 'StuController@v2_stu_exam_card']);
        $app->post('exam_card', ['uses' => 'StuController@v2_stu_exam_card']);

        $app->get('grades', ['uses' => 'StuController@v2_stu_grades']);
        $app->post('grades', ['uses' => 'StuController@v2_stu_grades']);

    });

    $app->group(['prefix' => 'internet'], function () use ($app) {

        $app->get('credits', ['uses' => 'InternetController@credits']);
        $app->post('credits', ['uses' => 'InternetController@credits']);

        $app->get('connection_report', ['uses' => 'InternetController@connection_report']);
        $app->post('connection_report', ['uses' => 'InternetController@connection_report']);

    });

    $app->group(['prefix' => 'self_service'], function () use ($app) {

        $app->get('credits', ['uses' => 'ApiController@self_service_credits']);
        $app->post('credits', ['uses' => 'ApiController@self_service_credits']);

        $app->get('menu', ['uses' => 'ApiController@self_service_menu']);
        $app->post('menu', ['uses' => 'ApiController@self_service_menu']);

        $app->get('reserve', ['uses' => 'ApiController@self_service_reserve']);
        $app->post('reserve', ['uses' => 'ApiController@self_service_reserve']);

    });

});


