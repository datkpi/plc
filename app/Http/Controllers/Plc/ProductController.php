<?php
namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index()
   {
       $products = Product::paginate(20);
       return view('plc.products.index', compact('products'));
   }

   public function create()
   {
       return view('plc.products.create');
   }

   public function store(Request $request)
   {
       $validated = $request->validate([
           'code' => 'required|unique:products,code',
           'name' => 'required',
           'gm_spec' => 'required|numeric|min:0',
           'min_productivity' => 'required|numeric|min:0'
       ]);

       Product::create($validated);

       return redirect()
           ->route('plc.products.index')
           ->with('success', 'Thêm sản phẩm thành công');
   }

   public function edit($id)
   {
       $product = Product::findOrFail($id);
       return view('plc.products.edit', compact('product'));
   }

   public function update(Request $request, $id)
   {
       $product = Product::findOrFail($id);

       $validated = $request->validate([
           'code' => 'required|unique:products,code,'.$id,
           'name' => 'required',
           'gm_spec' => 'required|numeric|min:0',
           'min_productivity' => 'required|numeric|min:0'
       ]);

       $product->update($validated);

       return redirect()
           ->route('plc.products.index')
           ->with('success', 'Cập nhật sản phẩm thành công');
   }

   public function destroy($id)
   {
       $product = Product::findOrFail($id);
       $product->delete();

       return redirect()
           ->route('plc.products.index')
           ->with('success', 'Xóa sản phẩm thành công');
   }
}
