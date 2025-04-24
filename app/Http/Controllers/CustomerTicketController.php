<?php

namespace App\Http\Controllers;

use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerTicketController extends Controller
{
    public function getUserTickets(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chưa đăng nhập'
                ], 401);
            }

            $tickets = DatVe::with([
                'suat_chieu.phim',
                'suat_chieu.phongchieu',
                'nguoi_dung',
                've_dats.ghe_ngoi',
                've_dats.loai_ve',
                'chi_tiet_dvs.dv_an_uong'
            ])
            ->where('nguoi_dung_id', $user->ma_nguoi_dung)
            ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách vé của khách hàng thành công',
                'data' => $tickets
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy vé của khách hàng: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể lấy danh sách vé',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, $ma_ve)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chưa đăng nhập'
                ], 401);
            }

            $ticket = DatVe::with('suat_chieu')
                ->where('nguoi_dung_id', $user->ma_nguoi_dung)
                ->findOrFail($ma_ve);

            if ($ticket->trang_thai === 'Đã hủy') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vé đã được hủy trước đó'
                ], 400);
            }

            $now = now();
            $showtime = $ticket->suat_chieu->ngay_chieu;
            if ($now->greaterThan($showtime)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể hủy vé sau giờ chiếu'
                ], 400);
            }

            if (!in_array($ticket->trang_thai, ['Đang chờ thanh toán', 'Đã thanh toán'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể hủy vé ở trạng thái hiện tại'
                ], 400);
            }

            $ticket->trang_thai = 'Đã hủy';
            $ticket->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Hủy vé thành công'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi hủy vé: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi hủy vé, vui lòng thử lại',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}