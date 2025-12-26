<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // pastikan modelnya benar

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $userPendingCount = 0;
            $adminPendingConfirmCount = 0;

            if (Auth::check()) {
                // Notif untuk user: pesanan dia yang masih pending (belum bayar)
                $userPendingCount = Order::query()
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->count();

                // Notif untuk admin: ada pesanan yang statusnya pending confirmation
                // Sesuaikan cara cek admin dengan project kamu (role/is_admin)
                $isAdmin = (Auth::user()->role ?? null) === 'admin';

                if ($isAdmin) {
                    $adminPendingConfirmCount = Order::query()
                        ->where('status', 'pending_confirmation')
                        ->count();
                }
            }

            $view->with([
                'userPendingCount' => $userPendingCount,
                'adminPendingConfirmCount' => $adminPendingConfirmCount,
            ]);
        });
    }
}
