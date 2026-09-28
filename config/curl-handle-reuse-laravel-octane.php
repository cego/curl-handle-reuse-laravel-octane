<?php

return [
    'max_handles'                => (int) env('REUSED_CURL_HANDLE_MAX_HANDLES', 50),
    'max_seconds_per_connection' => (int) env('REUSED_CURL_HANDLE_MAX_SECONDS_PER_CONNECTION', 60),
];
