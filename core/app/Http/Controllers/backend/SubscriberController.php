<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index()
    {
        return view('backend.modules.subscriber.index');
    }

//subscriber show and filter part//

public function ajaxIndex(Request $request)
{
    $columns    = ["id"];
    $draw       = $request->draw;
    $row        = $request->start;
    $rowperpage = $request->length;

    $columnIndex     = $request->order[0]['column'];
    $columnName      = ! empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
    $columnSortOrder = $request->order[0]['dir'];
    $searchValue     = $request->search['value'];
    $statusFilter    = $request->status;

    // Fetch only users who have at least one subscription
    $query = User::with(['latestSubscription'])->select('users.*');

    // Apply search filter
    if (! empty($searchValue)) {
        $query->where(function ($q) use ($searchValue) {
            $q->where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('email', 'like', '%' . $searchValue . '%');
        });
    }

    // Filtering logic
    if ($statusFilter == 'Active') {
        // Show users who have at least one active order
        $query->whereHas('latestSubscription', function ($q) {
            $q->where('payment_status', 'Paid')
                ->whereDate('end_date', '>=', now());
        });
    } elseif ($statusFilter == 'Inactive') {
        // Show only users who have NO active orders, but display their latest inactive order
        $query->whereDoesntHave('latestSubscription', function ($q) {
            $q->where('payment_status', 'Paid')
                ->whereDate('end_date', '>=', now()); // Exclude users with active subscriptions
        })->whereHas('latestSubscription'); // Ensure they have at least one order
    } else {
        // Initially show only users with active orders
        $query->whereHas('latestSubscription', function ($q) {
            $q->where('payment_status', 'Paid')
                ->whereDate('end_date', '>=', now());
        });
    }

    // Fetch results
    $totalRecords        = $query->count();
    $totalDisplayRecords = $query->count();
    $records             = $query->orderBy($columnName, $columnSortOrder)
        ->skip($row)
        ->take($rowperpage)
        ->get();

    $data = [];
    foreach ($records as $key => $user) {
        $subscription = $user->latestSubscription;
        $currentDate  = now();

        // Determine if user is truly Active or Inactive
        $activeStatus = ($subscription && $subscription->payment_status === 'Paid' && $subscription->end_date >= $currentDate)
            ? 'Active'
            : 'Inactive';

        $row    = [];
        $row[]  = '<label class="checkboxs"><input type="checkbox" class="checked-row" data-value="' . $user->id . '"><span class="checkmarks"></span></label>';
        $row[]  = ++$key;
        $row[]  = $user->name;
        $row[]  = $user->email;
        $row[]  = $user->phone ?? 'N/A';
        $row[]  = '<span class="badge ' . ($activeStatus === 'Active' ? 'bg-success' : 'bg-danger') . '">' . $activeStatus . '</span>';
        $row[]  = $subscription->subscription_start_date ?? 'N/A';
        $row[]  = $subscription->end_date ?? 'N/A';
        $row[]  = '<span class="badge ' . ($subscription && $subscription->payment_status === 'Paid' ? 'bg-success' : ($subscription && $subscription->payment_status === 'Pending' ? 'bg-warning' : 'bg-danger')) . '">' . ucfirst($subscription->payment_status ?? 'No Subscription') . '</span>';
        $data[] = $row;
    }

    $response = [
        "draw"                 => intval($draw),
        "iTotalRecords"        => $totalRecords,
        "iTotalDisplayRecords" => $totalDisplayRecords,
        "aaData"               => $data,
    ];

    return response()->json($response);
}




//All customer show part//
    public function customerIndex()
    {
        return view('backend.modules.customer.index');
    }

    public function allcustomerajaxIndex(Request $request)
    {
        $columns    = ["id"];
        $draw       = $request->draw;
        $row        = $request->start;
        $rowperpage = $request->length;

        $columnIndex     = $request->order[0]['column'];
        $columnName      = ! empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue     = $request->search['value'];

        // Fetch only users where user_type is 'customer'
        $query = User::where('user_type', 'customer')
            ->whereHas('packageshow')
            ->with(['packageshow'])
            ->select('users.*');

        $totalRecords = $query->count();

        // Apply search filter
        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            });
        }

        $totalDisplayRecords = $query->count();
        $records             = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($records as $key => $user) {
            $subscription = $user->packageshow;

            $row    = [];
            $row[]  = '<label class="checkboxs"><input type="checkbox" class="checked-row" data-value="' . $user->id . '"><span class="checkmarks"></span></label>';
            $row[]  = ++$key;
            $row[]  = $user->name;
            $row[]  = $user->email;
            $row[]  = $user->phone ?? 'N/A';
            $row[]  = $subscription->subscription_start_date ?? 'N/A';
            $row[]  = $subscription->end_date ?? 'N/A';
            $row[]  = '<span class="badge ' . ($subscription->payment_status === 'Paid' ? 'bg-success' : ($subscription->payment_status === 'Pending' ? 'bg-warning' : 'bg-danger')) . '">' . ucfirst($subscription->payment_status ?? 'No Subscription') . '</span>';
            $data[] = $row;
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalDisplayRecords,
            "aaData"               => $data,
        ];

        return response()->json($response);
    }

}
