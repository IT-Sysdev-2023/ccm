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

        $now = now();
        $userID = auth()->id();

        return Cache::remember(self::$cacheName . '_' . $userID, $now->addHour(), function () use ($timeout, $now) {
            try {

                $options = ['verify' => false];

                // $proxy = config('app.proxy');

                $quoteApi = config('app.quote_api');
                // dd($quoteApi);

                // $options = $proxy ? [...$options, 'proxy' => $proxy] : $options;

                $response = Http::timeout($timeout)->get($quoteApi);

                $quote = $response->json() ?? self::getLocalQuote($now);
            } catch (Exception) {
                return self::format(self::getLocalQuote($now));
            }

            return self::format($quote);
        });
    }

    private static function getLocalQuote(Carbon $now)
    {
        [$content, $author] = explode(' - ', Inspiring::quotes()->random());

        return [
            '_id' => $now->timestamp,
            'tags' => [],
            'content' => $content,
            'author' => $author,
            'authorSlug' => Str::slug($author),
            'length' => Str::length($content),
            'dateAdded' => $now->format('Y-m-d'),
            'dateModified' => $now->format('Y-m-d'),
        ];
    }

    private static function format(array $quote): string|array
    {

        return [
            'author' => $quote[0]['a'],
            'quote' => $quote[0]['q']
        ];

    }
}