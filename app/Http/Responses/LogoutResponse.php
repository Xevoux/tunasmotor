<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Redirect to welcome page after logout from admin panel.
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('welcome');
    }
}
