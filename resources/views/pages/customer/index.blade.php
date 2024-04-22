@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Customer Table</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">General</a></li>
                    <li class="breadcrumb-item active">Customer</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <!-- /.card-header -->
            <a href="{{ route('customer_add') }}" class="btn btn-primary">Add New</a>
            <div class="card-body" style="overflow-x: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Customer Name</th>
                            <th class="text-center">Adress</th>
                            <th class="text-center">Phone</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($cust as $item)
                        <tr>
                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                            <td class="align-middle">{{$item->cust_name}}</td>
                            <td class="align-middle">{{$item->cust_address}}</td>
                            <td class="align-middle">{{$item->cust_phone}}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Actions</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item text-warning" href="{{ route('customer_edit', ['id' => $item->id]) }}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('customer_delete', ['id' => $item->id]) }}" onsubmit="return confirm('Are you sure you want to toggle the deletion status of this product?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="dropdown-item text-danger">Toggle Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

@endsection

@section('script')
<script>
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 3000);
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                } // Disable sorting for the third (index 2) column
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,

        });
    });

</script>
@endsection
