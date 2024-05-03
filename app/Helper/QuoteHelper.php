<?php

namespace App\Helper;

use Exception;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class QuoteHelper
{
    protected static string $cacheName = '__quote';

    public static function get(int $timeout = 3): string|array
    {
        // dd(1);

        $now = now();
        $userID = auth()->id();

        return Cache::remember(self::$cacheName . '_' . $userID, $now->addHour(), function () use ($timeout, $now) {
            try {

                $options = ['verify' => false];
                // $proxy = config('app.proxy');
                $quoteApi = config('app.quote_api');
                // $options = $proxy ? [...$options, 'proxy' => $proxy] : $options;
                $response = Http::timeout($timeout)->get($quoteApi);
                $quote = $response->json()[0] ?? self::getLocalQuote($now);
                 return self::format(self::getLocalQuote($now));
            } catch (Exception) {
                return self::format(self::getLocalQuote($now));
            }
            return self::format($quote);
        });
    }

    private static function getLocalQuote(Carbon $now)
    {
        // dd(2);
        [$content, $author] = explode(' - ', Inspiring::quotes()->random());

        return [
            'q' => $content,
            'a' => $author,
        ];
    }

    private static function format(array $quote): string|array
    {
        // dd($quote);

        return [
            'author' => $quote['a'],
            'quote' => $quote['q']
        ];

    }
}
