<h1>Product List</h1>

<ul>
    @foreach ($products as $product)
        <li>{{ $product->name }}</li>
    @endforeach
</ul>

<h2>Add New Product</h2>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Product Name">
    <input type="text" name="sku" placeholder="SKU">
    <input type="text" name="description" placeholder="Description">
    <input type="text" name="brand" placeholder="Brand">
    <button type="submit">Add Product</button>
</form>

<h2>Remove Product</h2>

@if(count($products) > 0)
<form action="{{ route('products.destroy', ['product' => $products[0]->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <select name="product_id">
        @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>
    <button type="submit">Remove Product</button>
</form>
@else
<p>No products to remove.</p>
@endif
