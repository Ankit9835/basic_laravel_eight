<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    //
    public function index(){
    	$categories = Category::latest()->paginate(5);
    	$trashData = Category::onlyTrashed()->latest()->paginate(3);
    	return view('admin.category.index',compact('categories','categories','trashData'));
    }

    public function store(Request $request){

    	$validate_data = $request->validate([

    			'category_name' => 'required|unique:categories|max:255'

    	],
    	[
    			'category_name.required' => 'Please Input Category Name',
    			'category_name.max' => 'Max. Character Should Be 255 Chars',
    	]);

    	Category::insert([

    		'category_name' => $request->category_name,
    		'user_id' => Auth::User()->id,
    		'created_at' => Carbon::now()

    	]);

    	return redirect()->back()->with('success', 'Category Inserted Successfully');

    }

    public function edit($id){

    	$categories = Category::find($id);
    	return view('admin.category.edit', compact('categories'));

    }

    public function update(Request $request,$id){

    	// $category = Category::find($id)->update([

    	// 		'user_id' => Auth::User()->id,
    	// 		'category_name' => $request->category_name
    	// ]);

    	$data = array();
    	$data['user_id'] = Auth::User()->id;
    	$data['category_name'] = $request->category_name;

    	DB::table('categories')->where('id',$id)->update($data);

    	return redirect()->route('all.category')->with('success', 'Category Updated Successfully');

    }

    public function softDelete($id){

    	Category::find($id)->delete();
    	return redirect()->back()->with('success','Soft Deleted Successfully');

    }

    public function restore($id){

    	Category::withTrashed()->find($id)->restore();
    	return redirect()->back()->with('success','Restore Data Successfully');

    }

    public function perDelete($id){
    	Category::onlyTrashed()->find($id)->forceDelete();
    	return redirect()->back()->with('success','Permanently Data Deleted Successfully');


    }
}
