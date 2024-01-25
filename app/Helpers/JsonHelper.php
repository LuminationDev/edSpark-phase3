<?php
namespace App\Helpers;

class JsonHelper
{
    /**
     * Safely decode a JSON-encoded string or return the original string.
     *
     * @param string $input
     * @return mixed|string
     */
    public static function safelyDecodeString($input)
    {
        // Check if the input string is JSON-encoded
        $decodedData = json_decode($input);

        // If decoding was successful, return the decoded data
        if ($decodedData !== null && json_last_error() === JSON_ERROR_NONE) {
            return $decodedData;
        }

        // If not JSON-encoded, return the original string
        return $input;
    }


    /**
     * Safely encode data to JSON or return the original data.
     *
     * @param mixed $data
     * @return string
     */
    public static function safelyEncodeData($data)
    {
        // Check if the input is already a JSON-encoded string
        if (is_string($data) && json_decode($data) !== null && json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        // Encode the data to JSON
        $encodedData = json_encode($data);

        // If encoding was successful, return the encoded data
        if ($encodedData !== false) {
            return $encodedData;
        }

        // If encoding fails, return the original data as a string
        return (string) $data;
    }
}