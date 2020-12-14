<?php

function response_api($status, $data = null, $message = null, $messageKey = null, $messages = [], $statusCode = 200)
{
    return response()->json([
        'status' => $status,
        'data' => $data,
        'message' => $message,
        'messageKey' => $messageKey,
        'messages' => $messages,
        'statusCode' => $statusCode,
    ]);
}

function response_api_form_error($message, $messages)
{
    return response_api(false, null, $message, 'validator_fail', [$messages], 400);
}

function response_api_error($message, $messageKey)
{
    return response_api(false, null, $message, $messageKey, [], 400);
}

function response_api_error_with_data($message, $messageKey, $data)
{
    return response_api(false, $data, $message, $messageKey, [], 400);
}

function response_api_server_error($message)
{
    return response_api(false, null, 'Terjadi kesalahan pada server Kami, coba lagi nanti!', 'server_error', [$message], 500);
}

function response_api_success($data, $message = "Success", $statusCode = 200, $messageKey = 'ok')
{
    return response_api(true, $data, $message, $messageKey, [], $statusCode);
}
