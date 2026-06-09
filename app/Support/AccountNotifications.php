<?php
namespace App\Support;
use App\Mail\AccountApprovedUser;
use App\Mail\AccountPendingAdmin;
use App\Mail\AccountReceivedUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AccountNotifications {
    public static function pending(User $user): void {
        if (! config('documents.notifications_enabled')) return;
        Mail::to((string) config('documents.admin_email'))->send(new AccountPendingAdmin($user));
        Mail::to((string) $user->email)->send(new AccountReceivedUser($user));
    }
    public static function approved(User $user): void {
        if (! config('documents.notifications_enabled')) return;
        Mail::to((string) $user->email)->send(new AccountApprovedUser($user));
    }
}
