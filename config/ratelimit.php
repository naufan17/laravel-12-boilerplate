<?php

use Illuminate\Support\Str;

return [

  /*
  |--------------------------------------------------------------------------
  | Rate Limiting
  |--------------------------------------------------------------------------
  |
  | Here you may configure the rate limiters for your application. Each limiter
  | has a different "driver" that is used to determine how requests are counted
  | and limited. You may configure multiple limiters to handle different use
  | cases. The "limit" is the maximum number of requests that can be made
  | in the "time" duration specified.
  |
  */

  'limiters' => [
    'default' => [
      'driver' => 'redis',
      'limit' => 60,
      'time' => 60,
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Rate Limit Cache Key Prefix
  |--------------------------------------------------------------------------
  |
  | When unique rate limiting strings are stored in the cache, they'll be prefixed
  | with the application name to prevent cache collisions. This prefix should
  | uniquely identify your application on the cache store. This value should
  | usually not be changed.
  |
  */

  'prefix' => env('RATE_LIMITER_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_rate_limit'),


  /*
  |--------------------------------------------------------------------------
  | Rate Limit Headers
  |--------------------------------------------------------------------------
  |
  | When a request is limited, the rate limiter will add headers to the response
  | indicating the limit, remaining attempts, and when the limit resets. You
  | may customize the names of these headers here.
  |
  */

  'headers' => [
    'limit' => 'X-RateLimit-Limit',
    'remaining' => 'X-RateLimit-Remaining',
    'reset' => 'X-RateLimit-Reset',
    'retry-after' => 'Retry-After',
  ],

  /*
  |--------------------------------------------------------------------------
  | Rate Limit Cache Store
  |--------------------------------------------------------------------------
  |
  | This cache store will be used to store rate limiting information. You may
  | use any of the cache stores defined in the "cache" configuration file.
  |
  */

  'store' => env('RATE_LIMITER_STORE', 'redis'),

];