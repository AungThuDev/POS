@extends('layouts.master')

@section('content')
    <div class="container p-5">
        <div class="d-flex justify-content-end mb-5">
            <a class='btn btn-primary' href="{{ route('recipe.create') }}">
                Add Kitchen
            </a>
        </div>
        <table id="kitchens" class="table">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Name</th>
                <th class="text-center">Price</th>
                <th class="text-center">Category</th>
                <th class="text-center">Kitchen</th>
                <th class="text-center">Image</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //Data Table
            $("#kitchens").DataTable({
                'severSide': true,
                'processing': true,
                'ajax': {
                    url: `${app_url}/recipe`,
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
                        'data': 'price',
                        'className': 'text-center'
                    },
                    {
                        'data': 'category',
                        'className': 'text-center'
                    },
                    {
                        'data': 'kitchen',
                        'className': 'text-center'
                    },
                    {
                        'data': 'image',
                        'className': 'text-center'
                    },
                    {
                        'data': 'action'
                    },
                ],
                rowCallback: function(row, data) {
                    let rowId = "recipe_" + data.id;
                    $(row).addClass(rowId);
                },
            })


            //Delete Data
            $(document).on('click', '#delete', function() {
                let recipe_id = $(this).attr('recipe_id');
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
                            url: `${app_url}/recipe/${recipe_id}`,
                            success: function(resp) {
                                $(`.recipe_${resp}`).addClass('d-none');
                            },
                            error: function(e) {}
                        })
                    }
                });
            })
        })
    </script>
@endsection
