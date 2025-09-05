<?php
namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return redirect(url('/'));
        }

        // kalau ada intended (login guard set sebelumnya), pakai itu dulu
        $intended = session()->pull('url.intended');
        if ($intended) {
            return new RedirectResponse($intended);
        }

        // iterate registered panels, pilih panel pertama yang bisa diakses user
        foreach (filament()->getPanels() as $panel) {
            // canAccessPanel diberikan oleh HasPanelShield (atau manual di User model)
            // if ($user->canAccessPanel($panel)) {
                // getUrl() / getDashboardUrl() / fallback ke loginUrl
            //     if (method_exists($panel, 'getUrl')) {
            //         return redirect()->intended($panel->getUrl())->toResponse($request);
            //     }
            //     return redirect()->intended($panel->getLoginUrl())->toResponse($request);
            // }

            if ($user->canAccessPanel($panel)) {
                $panelUrl = $this->resolvePanelUrl($panel);
                return new RedirectResponse($panelUrl);
            }
        }

        // fallback
        return redirect()->intended(url('/'));
    }

    protected function resolvePanelUrl($panel): string
    {
        try {
            if (method_exists($panel, 'getUrl')) {
                return $panel->getUrl();
            }
            if (method_exists($panel, 'getDashboardUrl')) {
                return $panel->getDashboardUrl();
            }
            if (method_exists($panel, 'getLoginUrl')) {
                return $panel->getLoginUrl();
            }
            if (method_exists($panel, 'getPath')) {
                return url($panel->getPath());
            }
            if (method_exists($panel, 'getId')) {
                return url($panel->getId());
            }
        } catch (\Throwable $e) {
            // ignore, nanti fallback di bawah
        }

        return url('/');
    }
}
