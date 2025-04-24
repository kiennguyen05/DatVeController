<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';
    public $timestamps = false;

    protected $casts = [
        'ngay_sinh' => 'datetime',
        'ngay_tao_nd' => 'datetime',
        'mat_khau' => 'hashed',
    ];

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'sdt',
        'vai_tro',
        'ngay_sinh',
        'ngay_tao_nd',
    ];

    protected $hidden = [
        'mat_khau',
    ];

    public function dat_ves()
    {
        return $this->hasMany(DatVe::class, 'nguoi_dung_id', 'ma_nguoi_dung');
    }

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }
}