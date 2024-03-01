@extends('layouts.master')

@section('content')
    <div class="container p-5">
        <div class="d-flex justify-content-end mb-5">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewCategoryDiscount">
                Add Category Discount
            </button>
        </div>
        <table id="categories" class="table table-bordered table-responsive">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Categories</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <!-- Add New Category Discount Modal -->
    <div class="modal fade" id="addNewCategoryDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('category_discount.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category Discount</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Discount</label>
                    <select name="discount_id" class="form-select">
                        @if (!empty($discounts))
                            @foreach ($discounts as $discount)
                                <option id="discount_{{ $discount->id }}" value="{{ $discount->id }}">{{ $discount->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('discount_id')
                        <span class="d-block text-danger">{{ $message }}</span>
                    @enderror
                    <hr>
                    <label class="form-label d-block">Categories: </label>
                    @if (!empty($categories))
                        <div class="row">
                            @foreach ($categories as $category)
                                <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                    <label for="category_{{ $category->id }}"
                                        class="form-check-label me-1">{{ $category->name }}</label>
                                    <input name="categories[]" id="category_{{ $category->id }}" type="checkbox"
                                        class="form-check-inline" value="{{ $category->id }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('categories')
                        <span class="d-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Discount Modal -->
    <div class="modal fade" id="editCategoryDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="editForm" method="POST" class="modal-content">
                @method('PATCH')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Category Discount</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Discount</label>
                    <select id="edit_discount_id" name="edit_discount_id" class="form-control" disabled>

                    </select>
                    @error('edit_discount_id')
                        <span class="d-block text-danger">{{ $message }}</span>
                    @enderror
                    <hr>
                    <label class="form-label d-block">Categories: </label>
                    @if (!empty($categories))
                        <div class="row">
                            @foreach ($categories as $category)
                                <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                    <label for="edit_category_{{ $category->id }}"
                                        class="form-check-label me-1">{{ $category->name }}</label>
                                    <input name="edit_categories[]" id="edit_category_{{ $category->id }}" type="checkbox"
                                        class="form-check-inline" value="{{ $category->id }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('edit_categories')
                        <span class="d-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //Data Table
            $("#categories").DataTable({
                'severSide': true,
                'processing': true,
                'ajax': {
                    url: `/category_discount`,
                    error: function(e) {
                        console.log(e)
                    }
                },
                'columns': [{
                        'data': 'id',
                        'className': 'text-center'
                    },
                    {
                        'data': 'name',
                        'className': 'text-center'
                    },
                    {
                        'data': 'categories',
                        'className': 'text-center'
                    },
                    {
                        'data': 'action'
                    },
                ],
                rowCallback: function(row, data) {
                    let rowId = "category_discount_" + data.id;
                    $(row).addClass(rowId);
                },
            })

            //Get Edit Form
            $(document).on('click', '#edit', function() {
                let category_discount_id = $(this).attr('category_discount_id');
                $.ajax({
                    method: 'GET',
                    url: `${app_url}/category_discount/${category_discount_id}/edit`,
                    success: function(resp) {
                        let route = `${app_url}/category_discount/${resp['discount_id']}`;
                        $('#editForm').attr('action', route);
                        let html = $("#discount_" + resp['discount_id'])[0].outerHTML;
                        $("#edit_discount_id").html(html);
                        for (const id of resp['categories']) {
                            $("#edit_category_" + id).prop('checked', true);
                        }
                    },
                    error: function(e) {}
                });
            });

            //Delete Data
            $(document).on('click', '#delete', function() {
                let category_discount_id = $(this).attr('category_discount_id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this data!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'DELETE',
                            url: `${app_url}/category_discount/${category_discount_id}`,
                            success: function(resp) {
                                $(`.category_discount_${resp}`).addClass('d-none');
                            },
                            error: function(e) {}
                        })
                    }
                });
            })
        })
    </script>
@endsection
