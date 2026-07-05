<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Assumptions made about your schema (adjust to match your real setup):
 *  - There's a single-row `settings` table (Setting model below) holding store-wide
 *    config: name, currency, support_email, timezone, and a `notifications` JSON column.
 *    If you don't have this table yet, see the migration stub at the bottom of this file.
 *  - The logged-in admin is the standard `Auth::user()` from the `users` table with
 *    `name`, `email`, and `password` columns.
 */
class SettingController extends Controller
{
    /**
     * GET /admin/settings
     */
    public function index()
    {
        $store = Setting::current();

        return view('admin.settings.index', [
            'admin'         => Auth::user(),
            'store'         => [
                'name'          => $store->name,
                'currency'      => $store->currency,
                'support_email' => $store->support_email,
                'timezone'      => $store->timezone,
            ],
            'notifications' => $store->notifications ?? [
                'new_order'      => true,
                'low_stock'      => true,
                'new_customer'   => false,
                'weekly_summary' => true,
            ],
        ]);
    }

    /**
     * PUT /admin/settings/profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
        ]);

        $admin->update($validated);

        return back()->with('success', 'Your profile has been updated.');
    }

    /**
     * PUT /admin/settings/store
     */
    public function updateStore(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'currency'      => ['required', 'string', 'in:USD,EUR,GBP,MAD'],
            'timezone'      => ['required', 'string', 'max:64'],
        ]);

        Setting::current()->update($validated);

        return back()->with('success', 'Store details have been updated.');
    }

    /**
     * PUT /admin/settings/notifications
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notifications'                  => ['array'],
            'notifications.new_order'        => ['boolean'],
            'notifications.low_stock'        => ['boolean'],
            'notifications.new_customer'     => ['boolean'],
            'notifications.weekly_summary'   => ['boolean'],
        ]);

        // Checkboxes that are unchecked aren't submitted at all, so fill in `false`
        // for any key that didn't come through in the request.
        $defaults = [
            'new_order'      => false,
            'low_stock'      => false,
            'new_customer'   => false,
            'weekly_summary' => false,
        ];

        $notifications = array_merge($defaults, $validated['notifications'] ?? []);

        Setting::current()->update(['notifications' => $notifications]);

        return back()->with('success', 'Notification preferences saved.');
    }

    /**
     * PUT /admin/settings/password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'          => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your password has been updated.');
    }
}

/*
|--------------------------------------------------------------------------
| Migration stub — only needed if you don't already have a settings table.
|--------------------------------------------------------------------------
|
| php artisan make:model Setting -m
|
| Schema::create('settings', function (Blueprint $table) {
|     $table->id();
|     $table->string('name')->default(config('app.name'));
|     $table->string('currency')->default('USD');
|     $table->string('support_email')->nullable();
|     $table->string('timezone')->default('UTC');
|     $table->json('notifications')->nullable();
|     $table->timestamps();
| });
|
| // app/Models/Setting.php
| class Setting extends Model
| {
|     protected $casts = ['notifications' => 'array'];
|
|     // Always work with a single settings row (id = 1), creating it on first use.
|     public static function current(): self
|     {
|         return static::firstOrCreate(['id' => 1]);
|     }
| }
|
*/