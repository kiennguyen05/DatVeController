<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NguoiDung
 * 
 * @property int $ma_nguoi_dung
 * @property string $ho_ten
 * @property string $email
 * @property string $mat_khau
 * @property string $sdt
 * @property string $vai_tro
 * @property Carbon $ngay_sinh
 * @property Carbon $ngay_tao_nd
 * 
 * @property Collection|DatVe[] $dat_ves
 *
 * @package App\Models
 */
class NguoiDung extends Model
{
	protected $table = 'nguoi_dung';
	protected $primaryKey = 'ma_nguoi_dung';
	public $timestamps = false;

	protected $casts = [
		'ngay_sinh' => 'datetime',
		'ngay_tao_nd' => 'datetime'
	];

	protected $fillable = [
		'ho_ten',
		'email',
		'mat_khau',
		'sdt',
		'vai_tro',
		'ngay_sinh',
		'ngay_tao_nd'
	];

	public function dat_ves()
	{
		return $this->hasMany(DatVe::class, 'ma_nguoi_dung');
	}
}
