<?php

namespace App\Http\Controllers;

use Image;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Multipic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    //
    public function index(){
    	$brands = Brand::latest()->paginate(5);
    	return view('admin.brand.index', compact('brands'));
    }

    public function store(Request $request){

    	$validateData = $request->validate([

    		'brand_name' => 'required|unique:brands|min:4',
    		'brand_image' => 'required|mimes:jpg,jpeg,png'

    	],

    	[
    		'brand_name.required' => 'Please Input Brand Name',
            'brand_image.min' => 'Brand Longer then 4 Characters', 

    	]);

    	$brand_image =  $request->file('brand_image');

        // $name_gen = hexdec(uniqid());
        // $img_ext = strtolower($brand_image->getClientOriginalExtension());
        // $img_name = $name_gen.'.'.$img_ext;
        // $up_location = 'image/brand/';
        // $last_img = $up_location.$img_name;
        // $brand_image->move($up_location,$img_name);

        $name_gen = hexdec(uniqid()).  '.' . $brand_image->getClientOriginalExtension();

        Image::make($brand_image)->resize(300,200)->save('image/brand/'.$name_gen);

        $last_img = 'image/brand/'.$name_gen;

        Brand::insert([

        	'brand_name' => $request->brand_name,
        	'brand_image' => $last_img,
        	'created_at' => Carbon::now()

        ]);

        return redirect()->back()->with('success', 'Brand Inserted SuccessFully');

    }

    public function edit($id){
    	$brands = Brand::find($id);
    	return view('admin.brand.edit',compact('brands'));
    }

    public function update(Request $request,$id){


        

        $old_image = $request->old_image;

        $brand_image =  $request->file('brand_image');

        if($brand_image){

            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($brand_image->getClientOriginalExtension());
            $img_name = $name_gen.'.'.$img_ext;
            $up_location = 'image/brand/';
            $last_img = $up_location.$img_name;
            $brand_image->move($up_location,$img_name);

            unlink($old_image);

            Brand::find($id)->update([

                'brand_name' => $request->brand_name,
                'brand_image' => $last_img,
                'created_at' => Carbon::now()

            ]);

            return redirect()->back()->with('success', 'Brand Updated SuccessFully');

        } else {

            Brand::find($id)->update([

                'brand_name' => $request->brand_name,
                'created_at' => Carbon::now()

            ]);

            return redirect()->back()->with('success', 'Brand Updated SuccessFully');

        }
        

    }

    public function delete($id){

        $image = Brand::find($id);
        $old_image = $image->brand_image;
        unlink($old_image);

        Brand::find($id)->delete();
        return redirect()->back()->with('success', 'Brand Updated SuccessFully');

    }

    public function multipic(){
        $image = Multipic::all();
        return view('admin.multipic.index',compact('image'));
    }

    public function storeimage(Request $request){

        // $validateData = $request->validate([

            
        //     'image' => 'required|mimes:jpg,jpeg,png'

        // ]);

        $image =  $request->file('image');

        foreach($image as $multipic){

             $name_gen = hexdec(uniqid()).  '.' . $multipic->getClientOriginalExtension();

            Image::make($multipic)->resize(300,200)->save('image/multipic/'.$name_gen);

            $last_img = 'image/multipic/'.$name_gen;

            Multipic::insert([

                
                'image' => $last_img,
                'created_at' => Carbon::now()

            ]);

        }

       

        return redirect()->back()->with('success', 'Brand Inserted SuccessFully');

    }

    public function logout(){

        Auth::logout();
        
        return redirect()->route('login')->with('success', 'Brand Inserted SuccessFully');
    }
}
