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

$app->group(['prefix' => 'api/v1'], function () use ($app) {

    $app->get(
        'my/messages',
        [
            'as' => 'getMessagesForUser',
            'uses' => 'MessageController@getMessagesForUser'
        ]
    );

    // This route is only here to be able to generate the url we
    // pass in the response of the sendMessage request.
    $app->get(
        '{username}/messages/{index}',
        [
            'as' => 'getMessage'
        ]
    );

    $app->post(
        '{username}/messages',
        [
            'as' => 'sendMessage',
            'uses' => 'MessageController@sendMessageTo'
        ]
    );
});
