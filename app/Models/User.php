<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use App\Models\Settings;
use App\Models\CryptoAccount;
use App\Models\CryptoRecord;
use App\Notifications\EmailVerificationCode;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Send the email verification notification.
     *
     * @return void
     */

    public function sendEmailVerificationNotification()
    {
        $settings = Settings::where('id', 1)->first();

        if ($settings->enable_verification == 'true') {
            // Generate a 6-digit verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store the code and expiration time (15 minutes from now)
            $this->email_verification_code = $code;
            $this->email_code_expires_at = now()->addMinutes(15);
            $this->save();
            
            // Send the code via email using Mail facade directly
            \Illuminate\Support\Facades\Mail::to($this->email)
                ->send(new \App\Mail\VerifyEmailMail($this, $code, $settings));
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'l_name',
        'email', 'phone',
        'country', 'password',
        'ref_by', 'status', 'username',
        'email_verified_at', 'code1', 'code2',
        'code3', 'code1_enabled', 'code2_enabled', 'code3_enabled', 'otp_enabled',
        'usernumber', 'middlename', 'lastname', 'pin', 'accounttype', 'allowtransfer',
        'currency', 'email_verification_code', 'email_code_expires_at',
        'block_reason', 'blocked_at', 'blocked_by_admin_id', 'unblock_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function dp()
    {
        return $this->hasMany(Deposit::class, 'user');
    }

    public function wd()
    {
        return $this->hasMany(Withdrawal::class, 'user');
    }

    public function tuser()
    {
        return $this->belongsTo(Admin::class, 'assign_to');
    }

    public function dplan()
    {
        return $this->belongsTo(Plans::class, 'plan');
    }

    public function plans()
    {
        return $this->hasMany(User_plans::class, 'user', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public static function search($search): \Illuminate\Database\Eloquent\Builder
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('username', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
    }

    /**
     * Get the user's crypto account.
     */
    public function cryptoAccount()
    {
        return $this->hasOne(CryptoAccount::class);
    }

    /**
     * Get the user's crypto swap records.
     */
    public function cryptoRecords()
    {
        return $this->hasMany(CryptoRecord::class);
    }

    /**
     * Get all block history for this user
     */
    public function blockHistory()
    {
        return $this->hasMany(\App\Models\AccountBlock::class);
    }

    /**
     * Get current active block if any
     */
    public function currentBlock()
    {
        return $this->hasOne(\App\Models\AccountBlock::class)->whereNull('unblocked_at')->latest();
    }
}
