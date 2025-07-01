<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{

    private $category;

    public function __construct(Category $category) {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_categories = $this->category->paginate(6)->onEachSide(2);

        return view('admin.categories.show')
            ->with('all_categories', $all_categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|min:1|max:1000|unique:categories,name',
        ]);

        $this->category->name = $request->category_name;
        $this->category->save();

        $all_categories = $this->category->paginate(6)->onEachSide(2);

        return redirect()->route('admin.categories.show')
            ->with('all_categories', $all_categories);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category_id)
    {
        $category = $this->category->findOrFail($category_id);
        $category->delete();

        $all_categories = $this->category->paginate(7)->onEachSide(2);

        return redirect()->route('admin.categories.show')
            ->with('all_categories', $all_categories);
    }
}
