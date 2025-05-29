<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::ordered()->paginate(15);
        return view('admin.categories.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $req)
    {
        $req->validate(['name'=>'required|unique:categories|max:255']);
        Category::create($req->only('name','description','icon','sort_order'));
        return redirect()->route('admin.categories.index')->with('success','Đã thêm danh mục.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show',compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    public function update(Request $req,Category $category)
    {
        $req->validate([
            'name'=>['required','max:255', Rule::unique('categories')->ignore($category->id)]
        ]);
        $category->update($req->only('name','description','icon','sort_order','is_active'));
        return redirect()->route('admin.categories.index')->with('success','Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        if($category->posts()->exists()){
            return back()->with('error','Không thể xóa, có bài đăng đang dùng.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success','Đã xóa danh mục.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active'=>!$category->is_active]);
        return back()->with('success','Đã thay đổi trạng thái.');
    }
}
