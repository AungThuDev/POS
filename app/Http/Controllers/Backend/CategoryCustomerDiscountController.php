<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomerDiscount;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class CategoryCustomerDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categoryDiscount = [];
            foreach (CustomerDiscount::all() as $customer_discount) {
                if ($customer_discount->categories->count() > 0) {
                    array_push($categoryDiscount, $customer_discount);
                }
            }
            return DataTables::of($categoryDiscount)
                ->addColumn('categories', function ($a) {
                    return $a->categories->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($a) {
                    $edit = "<button category_customer_discount_id='" . $a->id . "' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editCategoryCustomerDiscount'>Edit</button> ";
                    $delete = "<button category_customer_discount_id='" . $a->id . "' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = Category::all();
        $customer_discounts = CustomerDiscount::all();
        return view('backend.Discount_Types.CustomerDiscount.index',compact('categories','customer_discounts'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_discount_id' => 'required',
            'categories' => 'required',
        ]);
        $customer_discount = CustomerDiscount::find($validated['customer_discount_id']);
        if ($customer_discount->categories->count() > 0) {
            throw ValidationException::withMessages(['customer_discount_id' => 'There is already a Discount. Delete the existing customer discount or modify it.']);
        }
        foreach ($validated['categories'] as $id) {
            $customer_discount->categories()->attach($id);
        }
        return redirect()->route('category_customer_discount.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerDiscount $customerDiscount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerDiscount $customerDiscount)
    {
        $categories_id = [];
        foreach ($customerDiscount->categories->pluck('id') as $id) {
            array_push($categories_id, $id);
        }
        return response()->json([
            'categories' => $categories_id,
            'customer_discount_id' => $customerDiscount->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerDiscount $customerDiscount)
    {
        $validated = $request->validate([
            'edit_categories' => 'required'
        ]);
        $customerDiscount->categories()->detach();
        foreach ($validated['edit_categories'] as $id) {
            $customerDiscount->categories()->attach($id);
        }
        return redirect()->route('category_customer_discount.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerDiscount $customerDiscount)
    {
        $id = $customerDiscount->id;
        $customerDiscount->categories()->detach();
        return response()->json($id);
    }
}
