@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Account Change Histories</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">General</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
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
                <div class="card-header">

                </div>
                <!-- ./card-header -->
                <div class="card-body">
                    <div class="table-responsive"> 
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>By User</th>
                                <th>Date</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $index => $entry)
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td>{{ $loop ->iteration }}</td>
                                <td>{{ $entry->user->name }}</td>
                                <td>{{ $entry->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $entry->action }}</td>
                                <td class="text-center">Click to toggle collapse</td>
                            </tr>
                            <tr class="expandable-body">
                                <td colspan="5">
                                    <p><strong>Old Data:</strong> {{ $entry->old_data }}</p>
                                    <p><strong>New Data:</strong> {{ $entry->new_data }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection
