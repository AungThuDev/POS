<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CustomerDiscount;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = CustomerDiscount::query();
            return DataTables::of($customers)
                ->addColumn('action', function ($a) {
                    $edit = "<button customer_id='" . $a->id . "' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editCustomerDiscount'>Edit</button> ";
                    $delete = "<button customer_id='" . $a->id . "' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.Discount_Types.Customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required', 'percent' => 'required|integer|min:1|max:100']);
        try {
            CustomerDiscount::create($validated);
        } catch (Exception $e) {
            // Exception error
        }
        return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerDiscount  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerDiscount $customer)
    {
        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerDiscount $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerDiscount $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerDiscount  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CustomerDiscount $customer)
    {
        $validated = $request->validate(['editName' => 'required', 'editPercent' => 'required|integer|min:1|max:100']);
        $customer->update(['name' => $validated['editName'], 'percent' => $validated['editPercent']]);
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerDiscount  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerDiscount $customer)
    {
        $id = $customer->id;
        $customer->delete();
        return response()->json($id);
    }
}
