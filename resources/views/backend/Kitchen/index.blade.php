@extends('layouts.master')

@section('content')
    <div class="container p-5">
        <div class="d-flex justify-content-end mb-5">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewKitchen">
                Add Kitchen
            </button>
        </div>
        <table id="kitchens" class="table">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Name</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <!-- Add New Kitchen Modal -->
    <div class="modal fade" id="addNewKitchen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('kitchen.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Kitchen</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" placeholder="Enter Kitchen Name...">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Kitchen Modal -->
    <div class="modal fade" id="editKitchen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="editForm" method="POST" class="modal-content">
                <input type="hidden" name="_method" value="PATCH">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kitchen</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Name</label>
                    <input name="editName" id="editName" type="text" class="form-control" placeholder="Enter Kitchen Name...">
                    @error('editName')
                        <span class="text-danger">{{ $message }}</span>
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
            $("#kitchens").DataTable({
                'severSide': true,
                'processing': true,
                'ajax': {
                    url: `${app_url}/kitchen`,
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
                        'data': 'action'
                    },
                ],
                rowCallback: function(row, data) {
                    let rowId = "kitchen_" + data.id;
                    $(row).addClass(rowId);
                },
            })

            //Get Edit Form
            $(document).on('click', '#edit', function() {
                let kitchen_id = $(this).attr('kitchen_id');
                $.ajax({
                    method: 'GET',
                    url: `${app_url}/kitchen/${kitchen_id}/edit`,
                    success: function(resp) {
                        let route = `${app_url}/kitchen/${resp['id']}`;
                        $("#editForm").attr('action', route);
                        $("#editName").val(resp['name']);
                    },
                    error: function(e) {
                        console.error(e);
                    }
                });
            });

            //Delete Data
            $(document).on('click', '#delete', function() {
                let kitchen_id = $(this).attr('kitchen_id');
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
                            url: `${app_url}/kitchen/${kitchen_id}`,
                            success: function(resp) {
                                $(`.kitchen_${resp}`).addClass('d-none');
                            },
                            error: function(e) {}
                        })
                    }
                });
            })
        })
    </script>
@endsection
