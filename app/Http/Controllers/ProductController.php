<?php
    
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use App\Http\Controllers\Controller;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use DB;

class ProductController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
	
	public function index()
    {
        $data = Product::latest()->paginate(10);
        return view('product.index', compact('data'));
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     
    public function index()
    {
        $data = Product::latest()->paginate(5);
        return view('product.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
	{
		$this->validate($request, [
			'image'	=> 'required|image|mimes:png,jpg,jpeg',
			'code'	=> 'required|max:5',
			'name'  => 'required|max:100',
			'stock' => 'required|numeric|min:1|max:100'
		]);

		//upload image
		$image = $request->file('image');
		$image->storeAs('public/products', $image->hashName());

		$product = Product::create([
			'product_image'	=> $image->hashName(),
			'product_code'  => $request->code,
			'product_name' 	=> $request->name,
			'stock' 		=> $request->stock,
		]);

		if($product){
			//redirect dengan pesan sukses
			return redirect()->route('product.index')->with(['success' => 'Data Berhasil Disimpan!']);
		}else{
			//redirect dengan pesan error
			return redirect()->route('product.index')->with(['error' => 'Data Gagal Disimpan!']);
		}
	}
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('product.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('product.edit',compact('product'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
         $this->validate($request, [
			'code'	=> 'required|max:5',
			'name'  => 'required|max:100',
			'stock' => 'required|numeric|min:1|max:100'
		]);

		//get data Product by ID
		$product = Product::findOrFail($product->id);

		if($request->file('image') == "") {

			$product->update([
				'product_code'  => $request->code,
				'product_name' 	=> $request->name,
				'stock' 		=> $request->stock,
			]);

		} else {

			//hapus old image
			Storage::disk('local')->delete('public/products/'.$product->product_image);

			//upload new image
			$image = $request->file('image');
			$image->storeAs('public/products', $image->hashName());

			$product->update([
				'product_image'	=> $image->hashName(),
				'product_code'  => $request->code,
				'product_name' 	=> $request->name,
				'stock' 		=> $request->stock,
			]);

		}

		if($product){
			//redirect dengan pesan sukses
			return redirect()->route('product.index')->with(['success' => 'Data Berhasil Diupdate!']);
		}else{
			//redirect dengan pesan error
			return redirect()->route('product.index')->with(['error' => 'Data Gagal Diupdate!']);
		}
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    
        return redirect()->route('product.index')
                        ->with('success','Product deleted successfully');
    }
}