<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers (Listing page only requirement).
     */
    public function index(Request $request): View
    {
        $query = Customer::withCount('leads')->with('leads');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 5);
        $customers = $query->latest()->paginate($perPage)->withQueryString();

        return view('customers.index', compact('customers'));
    }
}
