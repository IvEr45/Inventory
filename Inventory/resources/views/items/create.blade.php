@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Item</h2>
    <form method="POST" action="{{ route('items.store') }}">
        @csrf
        <label>Stock No.</label>
        <input type="text" name="stock_no"><br>

        <label>Description</label>
        <input type="text" name="description"><br>

        <label>Unit</label>
        <input type="text" name="unit"><br>

        <label>Quantity</label>
        <input type="number" name="quantity" min="0" value="0"><br>

        <button type="submit">Add</button>
    </form>
</div>
@endsection