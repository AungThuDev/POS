<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;


class CategoryDiscountController extends Controller
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
            foreach(Discount::all() as $discount) {
                if($discount->categories->count() > 0) {
                    array_push($categoryDiscount, $discount);
                }
            }
            return DataTables::of($categoryDiscount)
                ->addColumn('categories',function($a) {
                    return $a->categories->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($a) {
                    $edit = "<button category_discount_id='" . $a->id . "' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editCategoryDiscount'>Edit</button> ";
                    $delete = "<button category_discount_id='" . $a->id . "' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = Category::all();
        $discounts = Discount::all();
        return view('backend.Discount_Types.CategoryDiscount.index',compact('categories','discounts'));
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
        $validated = $request->validate([
            'discount_id' => 'required',
            'categories' => 'required',
        ]);
        $discount = Discount::find($validated['discount_id']);
        if($discount->categories->count() > 0) {
            throw ValidationException::withMessages(['discount_id' => 'There is already a Discount. Delete the existing discount or modify it.']);
        }
        foreach($validated['categories'] as $id) {
            $discount->categories()->attach($id);
        }
        return redirect()->route('category_discount.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        $categories_id = [];
        foreach($discount->categories->pluck('id') as $id) {
            array_push($categories_id, $id);
        }
        return response()->json([
            'categories' => $categories_id,
            'discount_id' => $discount->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Discount $discount
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'edit_categories' => 'required'
        ]);
        $discount->categories()->detach();
        foreach($validated['edit_categories'] as $id) {
            $discount->categories()->attach($id);
        }
        return redirect()->route('category_discount.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $id = $discount->id;
        $discount->categories()->detach();
        return response()->json($id);
    }
}
