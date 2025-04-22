<?php

namespace App\Http\Controllers;

use App\Models\DatVe;
use Illuminate\Http\Request;

class DatVeController extends Controller
{
    // 1. Lấy danh sách vé
    public function index()
{
    try {
        $tickets = DatVe::with([
            'suat_chieu.phim',
            'suat_chieu.phongchieu',
            'nguoi_dung',
            'chi_tiet_dvs'
        ])->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách vé thành công',
            'data' => $tickets
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không thể lấy danh sách vé',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // 2. Duyệt vé
    public function show($ma_ve)
    {
        $ticket = DatVe::with([
            'nguoi_dung',
            'suat_chieu.phim',
            'suat_chieu.phongChieu',
            'chi_tiet_dvs.dv_an_uong',
            've_dats.loai_ve',
            've_dats.ghe_ngoi'
        ])->findOrFail($ma_ve);

        return response()->json($ticket);
    }
    // 3. Hủy vé
    public function cancel($ma_ve)
{
    $ticket = DatVe::findOrFail($ma_ve);
    if ($ticket->trang_thai === 'Đã hủy') {
        return response()->json(['message' => 'Vé đã được hủy trước đó'], 400);
    }
    if (now()->greaterThan($ticket->suat_chieu->ngay_chieu)) {
        return response()->json(['message' => 'Không thể hủy vé sau giờ chiếu'], 400);
    }
    $ticket->trang_thai = 'Đã hủy';
    $ticket->save();
    return response()->json(['message' => 'Hủy vé thành công']);
}

    // 4. Xóa vé
    public function destroy($ma_ve)
{
    try {
        $ticket = DatVe::findOrFail($ma_ve);
        
        // Xóa các bản ghi liên quan trong ve_dat.012
        $ticket->ve_dats()->delete();
        
        // Xóa các bản ghi liên quan trong chi_tiet_dv (nếu đã áp dụng từ lỗi trước)
        $ticket->chi_tiet_dvs()->delete();
        
        // Sau đó xóa vé
        $ticket->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Vé đã được xóa thành công'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không thể xóa vé',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
