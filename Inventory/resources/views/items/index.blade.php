@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stationary supplies</h2>

    <form id="addForm">
        @csrf
        <input type="text" name="stock_no" placeholder="Stock No.">
        <input type="text" name="description" placeholder="Description">
        <input type="text" name="unit" placeholder="Unit">
        <input type="number" name="quantity" placeholder="Quantity" min="0">
        <button type="submit">Add</button>
    </form>

    <table border="1" cellpadding="5" cellspacing="0" id="itemTable">
        <thead>
            <tr>
                <th>Stock No.</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr id="row-{{ $item->id }}">
                <td>{{ $item->stock_no }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->quantity }}</td>
                <td>
                    <button onclick="editItem({{ $item->id }}, '{{ $item->stock_no }}', '{{ $item->description }}', '{{ $item->unit }}', {{ $item->quantity }})">Edit</button>
                    <button onclick="deleteItem({{ $item->id }})">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    // ADD ITEM
    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/items',
            method: 'POST',
            data: $(this).serialize(),
            success: function(item) {
                $('#itemTable tbody').append(`
                    <tr id="row-${item.id}">
                        <td>${item.stock_no}</td>
                        <td>${item.description}</td>
                        <td>${item.unit}</td>
                        <td>${item.quantity}</td>
                        <td>
                            <button onclick="editItem(${item.id}, '${item.stock_no}', '${item.description}', '${item.unit}', ${item.quantity})">Edit</button>
                            <button onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    </tr>
                `);
                $('#addForm')[0].reset();
            }
        });
    });

    // DELETE ITEM
    function deleteItem(id) {
        $.ajax({
            url: `/items/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                $(`#row-${id}`).remove();
            }
        });
    }

    // EDIT ITEM
    function editItem(id, stock_no, description, unit, quantity) {
        const row = $(`#row-${id}`);
        row.html(`
            <td><input type="text" id="edit-stock-${id}" value="${stock_no}"></td>
            <td><input type="text" id="edit-desc-${id}" value="${description}"></td>
            <td><input type="text" id="edit-unit-${id}" value="${unit}"></td>
            <td><input type="number" id="edit-quantity-${id}" value="${quantity}" min="0"></td>
            <td>
                <button onclick="updateItem(${id})">Save</button>
                <button onclick="location.reload()">Cancel</button>
            </td>
        `);
    }

    // UPDATE ITEM
    function updateItem(id) {
        const stock_no = $(`#edit-stock-${id}`).val();
        const description = $(`#edit-desc-${id}`).val();
        const unit = $(`#edit-unit-${id}`).val();
        const quantity = $(`#edit-quantity-${id}`).val();

        $.ajax({
            url: `/items/${id}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                stock_no, description, unit, quantity
            },
            success: function(item) {
                $(`#row-${item.id}`).html(`
                    <td>${item.stock_no}</td>
                    <td>${item.description}</td>
                    <td>${item.unit}</td>
                    <td>${item.quantity}</td>
                    <td>
                        <button onclick="editItem(${item.id}, '${item.stock_no}', '${item.description}', '${item.unit}', ${item.quantity})">Edit</button>
                        <button onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                `);
            }
        });
    }
</script>
@endsection