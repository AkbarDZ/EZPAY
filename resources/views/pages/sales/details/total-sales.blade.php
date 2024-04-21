@extends('welcome')

@section('title')

<div class="content-header">
    <div class="container-fluid">

    </div><!-- /.container-fluid -->
</div>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Total Sales for Customer
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('total_sales') }}">
                        @csrf
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_id" class="form-control AAA" required>
                                <option value="">Select Customer</option>
                                @foreach($cust as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->cust_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Get Total Sales</button>
                    </form>

                    @isset($totalSales)
                        <div class="mt-3">
                            <h4>Total Sales: Rp.{{ $totalSales }}</h4>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

    <script>

    $(document).ready(function () {
            $('.AAA').select2();

        });
        
    </script>
    

@endsection
