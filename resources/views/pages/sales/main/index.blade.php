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
                <form action="{{ route('export.sales') }}" method="GET" class="mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">From:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">To:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-block">Export</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Number</th>
                            <th class="text-center">Sales ID</th>
                            <th>Sales Date</th>
                            <th class="text-center">Total Price</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Cashier</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">{{ $item->id }}</td>
                            <td>{{ $item->sales_date }}</td>
                            <td class="text-center align-middle">{{ $item->total_price }}</td>
                            <td class="text-center align-middle">{{ $item->customer->cust_name }}</td>
                            <td class="text-center align-middle">{{ $item->user->name }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Actions</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <form method="POST" action="{{ route('generate_invoice', ['id' => $item->id]) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Generate Invoice</button>
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
