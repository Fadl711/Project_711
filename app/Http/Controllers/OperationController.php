<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function index()
    {
        $operationsAll = Operation::with('user')->latest()->get(); // جلب جميع العمليات
        return view('operation.index', compact('operationsAll'));
    }

    public function markSeen(Operation $operation)
    {
        $operation->update(['is_seen' => 1]);
        return response()->json(['success' => true]);
    }

    public function markAllSeen(Request $request)
    {
        Operation::where('is_seen', 0)->update(['is_seen' => 1]);
        return response()->json(['success' => true, 'message' => 'تم تعليم الكل كمقروء.']);
    }
    // OperationController.php
    public function destroy(Operation $operation)
    {
        $operation->delete();
        return response()->json(['success' => true]);
    }
}
