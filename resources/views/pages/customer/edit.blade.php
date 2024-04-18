@extends('welcome')

@section('title')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Customer Edit</h1>
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
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">

            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('customer_update', ['id' => $cust->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input name="cust_name" id="cust_name" autocomplete="off" type="text" class="form-control form-control-border border-width-2"  placeholder="Enter Name"
                        value="{{$cust->cust_name}}">
                    </div>
                    <div class="form-group">
                        <label>Customer Address</label>
                        <input name="cust_address" id="cust_address" autocomplete="off" type="text" class="form-control form-control-border border-width-2"  placeholder="Enter Price"
                        value="{{$cust->cust_address}}">
                    </div>
                    <div class="form-group">
                        <label>Customer Address</label>
                        <input name="cust_phone" id="cust_phone" autocomplete="off" type="text" class="form-control form-control-border border-width-2"  placeholder="Enter Price"
                        value="{{$cust->cust_phone}}">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-primary" href="{{ route('customer_table') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <!--/.col (left) -->
    <!-- right column -->
</div>

@endsection

@section('script')

<script>
    $(function () {
        bsCustomFileInput.init();
    });

</script>

@endsection
