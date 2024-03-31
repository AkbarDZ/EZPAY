@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sales Table</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Sales</a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <a href="" class="btn btn-primary">Add New Sale</a>
            <div class="card-body" style="overflow-x: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Number</th>
                            <th>Sales Date</th>
                            <th class="text-center">Total Price</th>
                            <th class="text-center">Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td>{{ $item->sales_date }}</td>
                            <td class="text-center align-middle">{{ $item->total_price }}</td>
                            <td class="text-center align-middle">{{ $item->customer->cust_name }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Actions</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item text-warning" href="{{ route('transaction_edit', ['id' => $item->id]) }}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('transaction_destroy', ['id' => $item->id]) }}" onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
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
