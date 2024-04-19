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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Total Sales for Customer
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('total_sales') }}">
                        @csrf
                        <div class="form-group">
                            <label for="customer_id">Customer ID</label>
                            <input type="number" name="customer_id" id="customer_id" class="form-control" required>
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
