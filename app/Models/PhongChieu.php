<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PhongChieu
 * 
 * @property int $ma_phong
 * @property int $ma_rap
 * @property string $ten_phong
 * @property string $loai_phong
 * @property int $so_cot
 * @property int $so_hang
 * @property Carbon $Ngay_tao_phong
 * 
 * @property RapPhim $rap_phim
 * @property Collection|GheNgoi[] $ghe_ngois
 * @property Collection|SuatChieu[] $suat_chieus
 *
 * @package App\Models
 */
class PhongChieu extends Model
{
	protected $table = 'phong_chieu';
	protected $primaryKey = 'ma_phong';
	public $timestamps = false;

	protected $casts = [
		'ma_rap' => 'int',
		'so_cot' => 'int',
		'so_hang' => 'int',
		'Ngay_tao_phong' => 'datetime'
	];

	protected $fillable = [
		'ma_rap',
		'ten_phong',
		'loai_phong',
		'so_cot',
		'so_hang',
		'Ngay_tao_phong'
	];

	public function rapphim()
	{
		return $this->belongsTo(RapPhim::class, 'ma_rap');
	}

	public function ghe_ngois()
	{
		return $this->hasMany(GheNgoi::class, 'ma_phong');
	}

	public function suatchieus()
	{
		return $this->hasMany(SuatChieu::class, 'ma_phong');
	}
}
