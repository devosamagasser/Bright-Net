<?php
namespace App\Modules\Shared\Support\Helper;


use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * @param $info
     * @param $message
     * @param $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    static function apiFormat($info, $message = null, $code = Response::HTTP_OK)
    {
        $response = [
            'success' => ($code >= 200 && $code < 300),
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($info) {
            $key = key($info);
            $response[$key] = $info[$key];
        }

        return response($response, $code);
    }

    public static function failed($errors, $message, $code)
    {
        $errors = $errors ? ['errors' => $errors] : null;
        return self::apiFormat(
            $errors,
            $message,
            $code
        );
    }

    public static function success($data, $message = null, $code = Response::HTTP_OK)
    {
        return self::apiFormat(
            ['data' => $data],
            __($message),
            $code
        );
    }

    public static function message($message, $code = Response::HTTP_OK)
    {
        return self::apiFormat(
            null,
            __($message),
            $code
        );
    }

    public static function notFound($message = 'apiMessages.not_found')
    {
        return self::apiFormat(
            null,
            __($message),
            Response::HTTP_NOT_FOUND
        );
    }

    public static function serverError($message = 'apiMessages.server_error')
    {
        return self::apiFormat(
            null,
            __($message),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function validationError($errors, $message = 'apiMessages.validation_error')
    {
        return self::failed(
            $errors,
            __($message),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function unauthorized($message = 'apiMessages.unauthorized', $code = Response::HTTP_UNAUTHORIZED)
    {
        return self::message(
            __($message), 
            $code
        );
    }

    public static function forbidden($message = 'apiMessages.forbidden', $code = Response::HTTP_FORBIDDEN)
    {
        return self::message(
            __($message), 
            $code
        );
    }

    public static function created($data = null, $message = 'apiMessages.created')
    {
        return ($data) ? 
            self::success(
                $data,
                __($message),
                Response::HTTP_CREATED
            ) : 
            self::message(
                __($message),
                Response::HTTP_CREATED
            );
    }

    public static function updated($data = null, $message = 'apiMessages.updated')
    {
        return ($data) ? 
            self::success(
                $data,
                __($message)
            ) :
            self::message(
                __($message)
            );
    }

    public static function deleted($message = 'apiMessages.deleted')
    {
        return self::message( 
            __($message), 
            Response::HTTP_NO_CONTENT
        );
    }
}

