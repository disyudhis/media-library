<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate;

class CustomAttemptToAuthenticate extends AttemptToAuthenticate
{
     /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        parent::__construct($guard, $limiter);
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (ValidationException $e) {
            $this->throwFailedAuthenticationException($request);
        }
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        $message = 'Email atau password yang dimasukkan tidak valid.';

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                Fortify::username() => [trans('auth.failed')],
            ]);
        }

        // Tambahkan flash message untuk error
        return redirect()
            ->route('login')
            ->with('error', $message)
            ->withErrors([Fortify::username() => trans('auth.failed')])
            ->withInput($request->except('password'));
    }
}