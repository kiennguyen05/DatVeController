<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatVe
 * 
 * @property int $ma_ve
 * @property int $ma_nguoi_dung
 * @property int $ma_suat_chieu
 * @property float $tong_gia_tien
 * @property int $tong_so_ve
 * @property Carbon $ngay_dat
 * 
 * @property SuatChieu $suat_chieu
 * @property NguoiDung $nguoi_dung
 * @property Collection|ChiTietDv[] $chi_tiet_dvs
 * @property Collection|ChiTietVe[] $chi_tiet_ves
 * @property Collection|VeDat[] $ve_dats
 *
 * @package App\Models
 */
class DatVe extends Model
{
	protected $table = 'dat_ve';
	protected $primaryKey = 'ma_ve';
	public $timestamps = false;

	protected $casts = [
		'ma_nguoi_dung' => 'int',
		'ma_suat_chieu' => 'int',
		'tong_gia_tien' => 'float',
		'tong_so_ve' => 'int',
		'ngay_dat' => 'datetime'
	];

	protected $fillable = [
		'ma_nguoi_dung',
		'ma_suat_chieu',
		'tong_gia_tien',
		'tong_so_ve',
		'ngay_dat'
	];

	public function suat_chieu()
	{
		return $this->belongsTo(SuatChieu::class, 'ma_suat_chieu');
	}

	public function nguoi_dung()
	{
		return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung');
	}

	public function chi_tiet_dvs()
	{
		return $this->hasMany(ChiTietDv::class, 'ma_ve');
	}

	public function chi_tiet_ves()
	{
		return $this->hasMany(ChiTietVe::class, 'ma_ve');
	}

	public function ve_dats()
	{
		return $this->hasMany(VeDat::class, 'ma_ve');
	}
}
