<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletsReportsController extends Controller
{
    //
    public function wallets()
    {
        return view('backend.modules.reports.wallets');
    }
    //
    public function walletsAjax(Request $request)
    {
        $columns = ['id', 'user.name', 'date', 'transaction_type', 'w_type', 'amount'];
        $draw = $request->input('draw');
        $row = $request->input('start');
        $rowPerPage = $request->input('length');
        $columnIndex = $request->input('order.0.column');
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->input('order.0.dir', 'asc');
        $searchValue = $request->input('search.value');
        $transaction_type = $request->input('type', 'all'); // Get the w_type filter

        $query = Wallet::with('user') // Eager loading 'user' relationship
            ->orderBy('id', 'desc');

        // Apply search filter
        if (!empty($searchValue)) {
            $query->whereHas('user', function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%');
            });
        }

        // Apply year filter
        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('created_at', $request->year);
        }

        // Apply w_type filter
        if ($transaction_type !== 'all') {
            $query->where('transaction_type', $transaction_type);
        }

        $totalFiltered = $query->count();

        $wallets = $query->orderBy($columnName, $columnSortOrder)
            ->offset($row)
            ->limit($rowPerPage)
            ->get();

        $allData = [];
        foreach ($wallets as $key => $wallet) {
            $data = [];
            $data[] = $key + $row + 1;
            $data[] = $wallet->user->name ?? '';
            $data[] = $wallet->created_at->setTimezone('Asia/Dhaka')->format('Y-m-d h:i:s') ?? '';
            // Accessor automatically formats w_type
            $data[] = '<span class="badge badge-linesuccess">' . ($wallet->w_type ?? '') . '</span>';
            // Accessor automatically formats transaction_type
            $badgeClass = $wallet->transaction_type == 'debit' ? 'badge-linesuccess' : 'badge-linedanger';
            $data[] = '<span class="badge ' . $badgeClass . '">' . ($wallet->transaction_type ?? '') . '</span>';

            $data[] = $wallet->amount ?? '';

            $allData[] = $data;
        }


        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalFiltered,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
}
