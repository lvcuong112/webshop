<?php

namespace App\Http\Controllers;

use App\ImageDetail;
use App\Product;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImageDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $id = $_GET['product_id'];
        $product = Product::findorFail($id);
//        dd($product);
//        $product = Product::findorFail

        return view('admin.product.detail_Images_create', [
            'product' => $product,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000'
        ]);
        $is_active = 0;
        if($request->has('is_active')) {
            $is_active = $request->input('is_active');
        }

        $img = new ImageDetail();

        $img->product_id = $request->input('product_id');
        $file = $request->file("image");
        $filename = time().'_'.$file->getClientOriginalName();
        $path_upload = 'uploads/product/';
        $request->file("image")->move($path_upload,$filename);
        $img->image = $path_upload.$filename;
        $img->is_active = $is_active;
        $img->user_id = Auth::user()->id;
        $img->position = $request->input('position');
//                dd($img);
        $img->save();

        return redirect()->route('admin.imagedetail.show', ['id' => $request->input('product_id') ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::findorFail($id);
        $images = ImageDetail::where([ 'product_id' => $id , 'is_active' => 1 ])->get();
        return view('admin.product.detail_Images', [
            'data' => $images,
            'product' => $product,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $image = ImageDetail::findorFail($id);

        $product_id = $image->product_id;

//        dd($image);
        return view('admin.product.detail_Images_edit', [
            'image' => $image,
            'product_id' => $product_id,

        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([

            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000'
        ]);

        $img = ImageDetail::findorFail($id);; // kh???i t???o model
        // Thay ?????i ???nh
        if ($request->hasFile('new_image')) {
            // x??a file c??
            @unlink(public_path($img->image));
            // get file m???i
            $file = $request->file('new_image');
            // get t??n
            $filename = time().'_'.$file->getClientOriginalName();
            // duong dan upload
            $path_upload = 'uploads/product/';
            // upload file
            $request->file('new_image')->move($path_upload,$filename);

            $img->image = $path_upload.$filename;
        }

        $img->position = $request->input('position');
        $is_active = 1;
        // Tr???ng th??i
        if ($request->has('is_active')){//kiem tra is_active co ton tai khong?
            $is_active = $request->input('is_active');
        }
        $img->is_active = $is_active;
        $img->save();

        // chuy???n h?????ng ?????n trang
        return redirect()->route('admin.imagedetail.show', [ 'id' => $img->product_id ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //
        // g???i t???i h??m destroy c???a laravel ????? x??a 1 object
        ImageDetail::destroy($id);

        // Tr??? v??? d??? li???u json v?? tr???ng th??i k??m theo th??nh c??ng l?? 200
        return response()->json([
            'status' => true
        ], 200);
    }
}
