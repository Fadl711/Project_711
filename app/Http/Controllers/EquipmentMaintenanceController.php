<?php

namespace App\Http\Controllers;

use App\Models\EquipmentMaintenance;
use App\Models\ProductionLine;
use App\Models\Employee;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentMaintenance::with(['productionLine', 'technician'])
            ->latest();

        // تطبيق الفلاتر
        if ($request->filled('line_id')) {
            $query->where('line_id', $request->line_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('overdue')) {
            $query->where('status', 'scheduled')
                ->where('scheduled_date', '<', now());
        }

        $maintenances = $query->paginate(15);
        $lines = ProductionLine::active()->get();
        $technicians = SubAccount::where('main_id',3)->get();
        $types = EquipmentMaintenance::getMaintenanceTypes();
        $statuses = EquipmentMaintenance::getStatuses();

        return view('production_system.equipment-maintenance.index', compact(
            'maintenances',
            'lines',
            'technicians',
            'types',
            'statuses'
        ));
    }

    public function create()
    {
        $lines = ProductionLine::with('equipments')->active()->get();
        $technicians = SubAccount::where('type_account',1)->get();
        $types = EquipmentMaintenance::getMaintenanceTypes();

        return view('production_system.equipment-maintenance.create', compact(
            'lines',
            'technicians',
            'types'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'line_id' => 'required|exists:production_lines,id',
            'equipment_code' => 'nullable|string|max:50',
            'maintenance_type'=> ['required', Rule::in(array_keys(EquipmentMaintenance::getMaintenanceTypes()))],
            'maintenance_code'=> 'nullable|string|max:50|unique:equipment_maintenance,maintenance_code',
            'description' => 'required|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'estimated_cost' => 'required|numeric|min:0',
            'technician_id' => 'required|exists:sub_accounts,sub_account_id',
            'parts_replaced' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        // توليد كود الصيانة إذا لم يتم تقديمه
        if (empty($validated['maintenance_code'])) {
            $validated['maintenance_code'] = 'MT-' . strtoupper(uniqid());
        }

        $validated['status'] = 'scheduled';

        EquipmentMaintenance::create($validated);

        return redirect()->route('production_system.equipment-maintenance.index')
            ->with('success', 'تم جدولة عملية الصيانة بنجاح');
    }

    public function show(EquipmentMaintenance $equipmentMaintenance)
    {
        $equipmentMaintenance->load([
            'productionLine',
            'technician',
            'approver',
            'verifier'
        ]);

        return view('equipment-maintenance.show', compact('equipmentMaintenance'));
    }

    public function edit(EquipmentMaintenance $equipmentMaintenance)
    {
        if ($equipmentMaintenance->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل الصيانة في حالتها الحالية');
        }

        $lines = ProductionLine::with('equipments')->active()->get();
        $technicians = SubAccount::where('position', 'technician')->get();
        $types = EquipmentMaintenance::getMaintenanceTypes();

        return view('equipment-maintenance.edit', compact(
            'equipmentMaintenance',
            'lines',
            'technicians',
            'types'
        ));
    }

    public function update(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        if ($equipmentMaintenance->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل الصيانة في حالتها الحالية');
        }

        $validated = $request->validate([
            'line_id' => 'required|exists:production_lines,line_id',
            'equipment_code' => 'required|string|max:50',
            'maintenance_type' => ['required', Rule::in(array_keys(EquipmentMaintenance::getMaintenanceTypes()))],
            'maintenance_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('equipment_maintenance', 'maintenance_code')->ignore($equipmentMaintenance->maintenance_id, 'maintenance_id')
            ],
            'description' => 'required|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'estimated_cost' => 'required|numeric|min:0',
            'technician_id' => 'required|exists:employees,employee_id',
            'parts_replaced' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $equipmentMaintenance->update($validated);

        return redirect()->route('equipment-maintenance.show', $equipmentMaintenance)
            ->with('success', 'تم تحديث بيانات الصيانة بنجاح');
    }

    public function startMaintenance(EquipmentMaintenance $equipmentMaintenance)
    {
        if ($equipmentMaintenance->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'لا يمكن بدء الصيانة في حالتها الحالية');
        }

        $equipmentMaintenance->markAsStarted();

        return redirect()->back()
            ->with('success', 'تم بدء عملية الصيانة بنجاح');
    }

    public function completeMaintenance(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        if ($equipmentMaintenance->status !== 'in_progress') {
            return redirect()->back()
                ->with('error', 'لا يمكن إكمال الصيانة في حالتها الحالية');
        }

        $validated = $request->validate([
            'actual_cost' => 'required|numeric|min:0',
            'parts_replaced' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $partsReplaced = array_filter(
            array_map('trim', explode(',', $validated['parts_replaced'] ?? ''))
        );

        $equipmentMaintenance->markAsCompleted(
            $validated['actual_cost'],
            $partsReplaced,
            $validated['notes'] ?? ''
        );

        return redirect()->back()
            ->with('success', 'تم إكمال عملية الصيانة بنجاح');
    }

    public function cancelMaintenance(EquipmentMaintenance $equipmentMaintenance)
    {
        if (!in_array($equipmentMaintenance->status, ['scheduled', 'in_progress'])) {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء الصيانة في حالتها الحالية');
        }

        $equipmentMaintenance->update([
            'status' => 'canceled',
            'end_time' => now()
        ]);

        return redirect()->back()
            ->with('success', 'تم إلغاء عملية الصيانة بنجاح');
    }
}