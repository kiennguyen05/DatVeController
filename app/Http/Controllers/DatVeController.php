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
    public function approve($ma_ve)
    {
        try {
            $ticket = DatVe::findOrFail($ma_ve);
            $ticket->update(['status' => 'approved']);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Vé đã được duyệt',
                'data' => $ticket->load([
                    'suat_chieu.phim',
                    'suat_chieu.phongchieu.rapphim',
                    've_dats.ghe_ngoi',
                    'nguoi_dung',
                    'chi_tiet_dvs.dv_an_uong'
                ])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể duyệt vé',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 3. Hủy vé
    public function cancel($ma_ve)
{
    try {
        $ticket = DatVe::findOrFail($ma_ve);
        $ticket->update(['status' => 'cancelled']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Vé đã bị hủy',
            'data' => $ticket->load([
                'suat_chieu.phim',
                'suat_chieu.phongchieu.rapphim',
                've_dats.ghe_ngoi',
                'nguoi_dung',
                'chi_tiet_dvs.dv_an_uong'
            ])
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không thể hủy vé',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // 4. Xóa vé
    public function destroy($ma_ve)
{
    try {
        $ticket = DatVe::findOrFail($ma_ve);
        
        // Xóa các bản ghi liên quan trong ve_dat
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
