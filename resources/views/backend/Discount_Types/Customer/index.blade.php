@extends('layouts.master')

@section('content')
    <div class="container p-5">
        <div class="d-flex justify-content-end mb-5">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewCustomerDiscount">
                Add Customer Discount
            </button>
        </div>
        <table id="categories" class="table table-bordered table-responsive">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Customers</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <!-- Add New Customer Discount Modal -->
    <div class="modal fade" id="addNewCustomerDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('customer.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Discount</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" placeholder="Enter Discount Name...">
                    @error('name')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                    <label class="form-label">Percent (%)</label>
                    <input name="percent" type="text" class="form-control" placeholder="Enter Discount Percent...">
                    @error('percent')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Customer Discount Modal -->
    <div class="modal fade" id="editCustomerDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="editForm" method="POST" class="modal-content">
                @method('PATCH')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Discount</h5>
                </div>
                <div class="modal-body form-group">
                    <label class="form-label">Name</label>
                    <input id="editName" name="editName" type="text" class="form-control"
                        placeholder="Enter Discount Name...">
                    @error('editName')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                    <label class="form-label">Percent (%)</label>
                    <input id="editPercent" name="editPercent" type="text" class="form-control"
                        placeholder="Enter Discount Percent...">
                    @error('editPercent')
                        <span class="text-danger d-block">{{ $message }}</span>
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
                    url: `/customer`,
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
                        'data': 'percent',
                        'className': 'text-center'
                    },
                    {
                        'data': 'action'
                    },
                ],
                rowCallback: function(row, data) {
                    let rowId = "customer_" + data.id;
                    $(row).addClass(rowId);
                },
            })

            //Get Edit Form
            $(document).on('click', '#edit', function() {
                let customer_id = $(this).attr('customer_id');
                $.ajax({
                    method: 'GET',
                    url: `${app_url}/customer/${customer_id}/edit`,
                    success: function(resp) {
                        let route = `${app_url}/customer/${resp['id']}`;
                        $('#editForm').attr('action', route);
                        $("#editName").val(resp['name'])
                        $("#editPercent").val(resp['percent'])
                    },
                    error: function(e) {
                        console.error(e);
                    }
                });
            });

            //Delete Data
            $(document).on('click', '#delete', function() {
                let customer_id = $(this).attr('customer_id');
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
                            url: `${app_url}/customer/${customer_id}`,
                            success: function(resp) {
                                $(`.customer_${resp}`).addClass('d-none');
                            },
                            error: function(e) {}
                        })
                    }
                });
            })
        })
    </script>
@endsection
