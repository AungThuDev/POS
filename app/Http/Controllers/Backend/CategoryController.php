<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query();
            return DataTables::of($categories)
                ->addColumn('action', function ($a) {
                    $edit = "<button category_id='".$a->id."' id='edit' type='button' class='btn btn-warning mx-3' data-toggle='modal' data-target='#editCategory'>Edit</button> ";
                    $delete = "<button category_id='".$a->id."' id='delete' class='btn btn-danger mx-3'>Delete</button>";
                    return "<div class='d-flex justify-content-center'>".$edit.$delete."</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.Category.index');
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
            Category::create($validated);
        } catch (Exception $e) {
            //Exception Error
        }
        return redirect()->route('category.index');
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(['editName'=>'required']);
        $category->update(['name' => $validated['editName']]);
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $id = $category->id;
        $category->delete();
        return response()->json($id);
    }
}
