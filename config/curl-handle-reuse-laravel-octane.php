<?php

return [
    'max_handles'                => filter_var(env('REUSED_CURL_HANDLE_MAX_HANDLES', 50), FILTER_VALIDATE_INT),
    'max_seconds_per_connection' => filter_var(env('REUSED_CURL_HANDLE_MAX_SECONDS_PER_CONNECTION', 60), FILTER_VALIDATE_INT),
];
