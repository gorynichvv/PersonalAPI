<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{

    public static function isAllowedCredit(): bool
    {
        $first = today()->subDays(5); // TODO move to settings
        $second = today()->addDays(5); // TODO move to settings
        $creditDay = Auth::user()->prepay_day_traf;
        $expirationDate = Carbon::parse(Auth::user()->expiries_at);

        return ($expirationDate->between($first, $second) && $creditDay === 0);
    }

    public static function isAllowedBlock(): bool
    {
        $blockingDaysLastMounth = User::find(Auth::id())
            ->block()
            ->whereBetween('date', [Carbon::parse(Auth::user()->activate_day), now()])
            ->where('type', '0')
            ->sum('len');

        return (Auth::user()->blocking_id === 0 && $blockingDaysLastMounth === 0);
    }

    public static function setUnblock(): void
    {
        $user = User::find(Auth::id());
        $info = "Разблокирован из личного кабинета, IP {$_SERVER['REMOTE_ADDR']}";

        $lastBlocking = User::find(Auth::id())
            ->block()
            ->where('type', '1')
            ->latest('id')
            ->first();

        $blockingDate = Carbon::parse($lastBlocking->date);
        $daysOfBlocking = Carbon::today()->diffInDays($blockingDate);
        $daysToExpiries = $blockingDate->diffInDays($user->exp_date);
        $newExpiriesDate = Carbon::now()->addDays($daysToExpiries);

        DB::transaction(function () use (
            $user,
            $info,
            $daysOfBlocking,
            $newExpiriesDate
        ) {
            $user->blocking_id = 0;
            $user->activate_day = now();
            $user->exp_date = $newExpiriesDate;
            $user->saveQuietly();

            $user->session()->update(['drop_ses' => 1]);

            $user->block()->create([
                'user_id' => $user->id,
                'cause' => '',
                'admin_name' => '',
                'date' => now(),
                'type' => 0,
                'len' => $daysOfBlocking
            ]);

            $user->servicelog()
                ->create([
                    'user_id' => $user->id,
                    'info' => $info,
                    'date' => now(),
                    'sum' => '0'
                ]);
        });
    }

    public static function setBlockStatus(): void
    {
        $user = User::find(Auth::id());
        $info = "Блокирован из личного кабинета, IP {$_SERVER['REMOTE_ADDR']}";

        DB::transaction(function () use (
            $user,
            $info
        ) {
            $user->session()->update(['drop_ses' => 1]);

            $blocking_id = $user->block()->create([
                'user_id' => $user->id,
                'cause' => '',
                'admin_name' => '',
                'date' => now(),
                'auto_unblock' => '2020-04-10', //$auto_unblock_date
                'type' => 1,
                'len' => 0
            ])->id;

            $user->blocking_id = $blocking_id;
            $user->saveQuietly();

            $user->servicelog()
                ->create([
                    'user_id' => $user->id,
                    'info' => $info,
                    'date' => now(),
                    'sum' => '0'
                ]);
        });
    }
    public static function setCredit(): void
    {
        $user = User::find(Auth::id());
        $numberOfCreditDays = 3; // TODO move to settings
        $actiavtionDate = $user->activate_day;
        $expirationDate = Carbon::parse($user->exp_date);
        $dateToExpiration = $expirationDate->addDays($numberOfCreditDays);
        $info = "Активирован кредит на $numberOfCreditDays дня, IP {$_SERVER['REMOTE_ADDR']}";

        if ($expirationDate >= Carbon::today()) {
            $numberOfCreditDays -= 1;
            $actiavtionDate = Carbon::today();
        }

        DB::transaction(function () use (
            $user,
            $numberOfCreditDays,
            $dateToExpiration,
            $actiavtionDate,
            $info
        ) {
            $user->prepay_day_traf = $numberOfCreditDays;
            $user->exp_date = $dateToExpiration;
            $user->activate_day = $actiavtionDate;
            $user->saveQuietly();

            $user->session()->update(['drop_ses' => 1]);

            $user->servicelog()
                ->create([
                    'user_id' => $user->id,
                    'info' => $info,
                    'date' => now(),
                    'sum' => '0'
                ]);

            DB::table('credit_stat')->increment('count');
        });
    }

    public static function bytesToHuman($bytes): mixed
    {
        $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ПБ'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function getDaysLeft(): int
    {
        $today = Carbon::today();
        $userExpiriesAt = Auth::user()->exp_date;

        return (Carbon::parse($userExpiriesAt) >= $today)
            ? ($today->diffInDays($userExpiriesAt) + 1)
            : 0;
    }

    public static function prepareKeys(array $keys): array
    {
        $maps = [
            'credit' => 'prepay_day_traf',
            'device_1' => 'mac',
            'device_2' => 'mac2',
            'device_3' => 'mac3'
        ];

        return collect($keys)->keyBy(
            function ($value, $key) use ($maps) {
                return $maps[$key];
            }
        )->toArray();
    }

    public static function checkCredentials(Request $request): User
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || $request->password !== $user->password) {
            throw ValidationException::withMessages([
                'credentials' => ['Неверное имя пользователя или пароль!'],
            ]);
        }

        return $user;
    }
}
