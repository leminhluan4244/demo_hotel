<?php

namespace App\Http\Controllers\Base;

use Exception;

class Response
{
    const SUCCESS = 200;
    const ERROR = 400;
    const EXCEPTION = 500;

    /**
     * Check and return response.
     *
     * @param array $data
     *
     * @return object
     */
    public static function response($data = [])
    {
        return response()
        ->json(
            array(
                'status' => self::SUCCESS,
                'data' => $data,
            ),
            self::SUCCESS,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Return errors response.
     *
     * @param array $errors
     *
     * @return object
     */
    public static function errors($errors = ['One of the param values is invalid'])
    {
        return response()
        ->json(
            array(
                'status' => self::ERROR,
                'errors' => $errors,
            ),
            self::SUCCESS,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Return exception response.
     *
     * @param array|Exception $exception
     * @param array           $log
     *
     * @return object
     */
    public static function exception($exception = [])
    {
        return response()
        ->json(
            array(
                'status' => self::EXCEPTION,
                'exception' => ['Server not found'],
            ),
            self::EXCEPTION,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Return exception response.
     *
     * @param array $param
     * @param int   $code
     *
     * @return object
     */
    public static function redirectInput($route, $errors, $param = '')
    {
        if ($param == '') {
            return redirect()
            ->route($route)
            ->with('errors', $errors)
            ->withInput();
        } else {
            return redirect()
            ->route($route, $param)
            ->with('errors', $errors)
            ->withInput();
        }
    }

    /**
     * Return exception response.
     *
     * @param array $param
     * @param int   $code
     *
     * @return object
     */
    public static function redirect($route, $errors, $param = '')
    {
        if ($param == '') {
            return redirect()
            ->route($route)
            ->with('errors', $errors);
        } else {
            return redirect()
            ->route($route, $param)
            ->with('errors', $errors);
        }
    }

    /**
     * Return exception response.
     *
     * @param array $param
     * @param int   $code
     *
     * @return object
     */
    public static function custom($param, $code)
    {
        return response()
        ->json(
            $param,
            $code,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}
