<?php

function error_out(string $error_message, int $status_id): void
{
    http_response_code($status_id);
    exit(
    json_encode(
        array(
            'success' => false,
            'message' => $error_message
        )
    )
    );
}