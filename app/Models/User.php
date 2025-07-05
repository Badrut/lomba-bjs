<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Relasi ---

    /**
     * Seorang User dapat menjadi satu Nasabah.
     */
    public function nasabah()
    {
        return $this->hasOne(Nasabah::class, 'user_id', 'id');
    }

    /**
     * Seorang User memiliki banyak Notifikasi.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Seorang User melakukan banyak Activity Log.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id');
    }

    /**
     * Seorang User dapat memperbarui banyak System Setting.
     */
    public function systemSettings()
    {
        return $this->hasMany(SystemSetting::class, 'updated_by_user_id', 'id');
    }

    /**
     * Seorang User dapat mengunggah banyak Dokumen.
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'diupload_oleh_user_id', 'id');
    }
}
