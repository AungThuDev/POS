<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Discount::query();
            return DataTables::of($categories)
                ->addColumn('action', function ($a) {
                    $edit = "<button discount_id='" . $a->id . "' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editDiscount'>Edit</button> ";
                    $delete = "<button discount_id='" . $a->id . "' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.Discount_Types.Discount.index');
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
            Discount::create($validated);
        } catch (Exception $e) {
            //Exception Error
        }
        return redirect()->route('discount.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        return response()->json($discount);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate(['editName' => 'required', 'editPercent' => 'required|integer|min:1|max:100']);
        $discount->update(['name' => $validated['editName'], 'percent' => $validated['editPercent']]);
        return redirect()->route('discount.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $id = $discount->id;
        $discount->delete();
        return response()->json($id);
    }
}
