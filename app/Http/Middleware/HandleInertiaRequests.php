<?php

namespace App\Http\Middleware;

use App\Helper\QuoteHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // dd(QuoteHelper::get());
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()?->load('employee3.applicant'),
            ],
            'flash' => fn() => [
                'success' => $request->session()->get('success'),
                'info' => $request->session()->get('info'),
                'error' => $request->session()->get('error'),
                'style' => $request->session()->get('style'),
            ],
            'quote' => QuoteHelper::get(),

        ];
    }
}
