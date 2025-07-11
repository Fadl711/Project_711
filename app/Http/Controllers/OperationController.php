<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        if (!$user->canRead('الاشعارات')) {
            abort(403, 'لايمكنك الوصول');
        }
        $operationsAll = Operation::with('user')->latest()->get(); // جلب جميع العمليات
        return view('operation.index', compact('operationsAll'));
    }

    public function markSeen(Operation $operation)
    {
        $user = Auth::user();
        if (!$user->canWrite('الاشعارات') || !$user->canModify('الاشعارات')) {
            abort(403, 'لايمكنك الوصول');
        }
        $operation->update(['is_seen' => 1]);
        return response()->json(['success' => true]);
    }

    public function markAllSeen(Request $request)
    {
        $user = Auth::user();
        if (!$user->canWrite('الاشعارات') || !$user->canModify('الاشعارات')) {
            abort(403, 'لايمكنك الوصول');
        }
        Operation::where('is_seen', 0)->update(['is_seen' => 1]);
        return response()->json(['success' => true, 'message' => 'تم تعليم الكل كمقروء.']);
    }
    // OperationController.php
    public function destroy(Operation $operation)
    {
        $user = Auth::user();
        if (!$this->user->canDelete('الاشعارات')) {
            abort(403, 'لايمكنك الوصول');
        }
        $operation->delete();
        return response()->json(['success' => true]);
    }
}
