<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Kitchen;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kitchens = Kitchen::query();
            return DataTables::of($kitchens)
                ->addColumn('action', function ($a) {
                    $edit = "<button kitchen_id='" . $a->id . "' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editKitchen'>Edit</button> ";
                    $delete = "<button kitchen_id='" . $a->id . "' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.Kitchen.index');
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
        $validated = $request->validate(['name' => 'required']);
        try {
            Kitchen::create($validated);
        } catch (Exception $e) {
            //Exception Error
        }
        return redirect()->route('kitchen.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kitchen  $kitchen
     * @return \Illuminate\Http\Response
     */
    public function show(Kitchen $kitchen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kitchen  $kitchen
     * @return \Illuminate\Http\Response
     */
    public function edit(Kitchen $kitchen)
    {
        return response()->json($kitchen);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kitchen  $kitchen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Kitchen $kitchen)
    {
        $validated = $request->validate(['editName' => 'required']);
        $kitchen->update(['name' => $validated['editName']]);
        return redirect()->route('kitchen.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kitchen  $kitchen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kitchen $kitchen)
    {
        $id = $kitchen->id;
        $kitchen->delete();
        return response()->json($id);
    }
}
