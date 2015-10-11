<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | A restful API consists of many data object, also known as resources or
    | models. To allow a single model to be accessible through the API you
    | have to "white list" it with a certain name. The name will be the link to
    | the URL's and the model. All listed models will be registered
    | to the provided resource manager.
    |
    | The example below allows a model "\App\Project" to be accessible through
    | "https://api.myawesomeproject.com/v1/projects".
    |
    | Note, it's recommended to NOT include the "User" model since it's very
    | security sensitive. Use default Laravel handling or write custom
    | controllers instead of using the model directly.
    |
    */

    'resources' => [
        'manager'  => ByCedric\Allay\Resource\Manager::class,
        'resolver' => ByCedric\Allay\Resource\Resolvers\LumenResolver::class,
        'models'   => [
            // 'projects' => App\Project::class
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | An API contains routes that defines the actions a resource can receive.
    | For example; some people wants to `create` projects, others would love to
    | `update` the project name. So basically, all those simple actions are
    | executed by a controller. Here you can define the controller it SHOULD
    | use. As you might have noticed, this is only one controller. Think of it as
    | an `one size fits all` controller. It's quite repetitive to define all
    | actions, for every resource model again. Therefore the models are being
    | injected using the resource manager.
    |
    | And last, but not least, you can specify all settings you would like to
    | pass to the route's group. Like the middleware it SHOULD use, and/or a
    | security it SHOULD add. Customize it, to fit your project.
    |
    */

    'routes' => [
        'controller' => ByCedric\Allay\Http\Controllers\LumenController::class,
        'settings'   => [
            'prefix'     => 'v1',
            'middleware' => [
                ByCedric\Allay\Http\Middleware\TransformResponseToArray::class,
                ByCedric\Allay\Http\Middleware\UpdateStatusCodeByRequestMethod::class,
                ByCedric\Allay\Http\Middleware\CatchExceptionsWithManager::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transformer
    |--------------------------------------------------------------------------
    |
    | Every API response is formatted using a pre-defined format. This can be a
    | json-api format, or hal. Even plain array is a format that you SHOULD be
    | able to use. Therefore you can define a transformer that will transform
    | any returned value, from the controllers, to a plain response array.
    |
    */

    'transformer' => ByCedric\Allay\Transformers\ArrayTransformer::class,

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    |
    | Inside Allay we use exceptions to stop and respond with a certain message.
    | Every exception has to be rendered to a valid response, therefore we make
    | use of a general exception manager. You can add your own handlers to
    | extend the manager, or just totally replace the manager.
    |
    | Note, this does not interact with the default Laravel exception handler
    | since these handlers SHOULD only catch exceptions, for respond purposes
    | For example, this SHOULD only catch the ModelNotFoundException or
    | ValidationException. Those aren't "real" exceptions, like
    | InvalidArgumentException. The "real" exceptions MUST not be caught by
    | this manager, and MUST be handled by Laravel's exception handler.
    |
    */

    'exceptions' => [
        'manager'  => ByCedric\Allay\Exceptions\Manager::class,
        'handlers' => [
            ByCedric\Allay\Exceptions\Handlers\ResourceHandler::class,
            ByCedric\Allay\Exceptions\Handlers\ModelNotFoundHandler::class,
            ByCedric\Allay\Exceptions\Handlers\ValidationHandler::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    |
    | Each request SHOULD respond with a applicable http status code. Here you
    | can define each http request, with their default http status code. This
    | will then be applied when successfully executing a resource action.
    |
    */

    'statuses' => [
        Illuminate\Http\Request::METHOD_GET    => 200,
        Illuminate\Http\Request::METHOD_POST   => 201,
        Illuminate\Http\Request::METHOD_PUT    => 204,
        Illuminate\Http\Request::METHOD_PATCH  => 204,
        Illuminate\Http\Request::METHOD_DELETE => 204,
    ],

];
