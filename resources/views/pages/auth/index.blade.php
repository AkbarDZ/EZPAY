@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Management</h1>
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
            <!-- /.card-header -->
            <a href="{{ route('account_create') }}" class="btn btn-primary">Add New</a>
            <div class="card-body" style="overflow-x: auto;">
                <table id="userTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Number</th>
                            <th>Name</th>
                            <th class="text-center">Picture</th>
                            <th>Email</th>
                            <th class="text-center">Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user as $user)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="text-center align-middle">
                                @if ($user->avatar)

                                <img src="data:image/jpeg;base64,{{ $user->avatar }}" style="max-height: 5rem"
                                    class="rounded mx-auto d-block" alt="...">

                                @else

                                No Image Available

                                @endif
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="text-center" style="text-transform: uppercase" class="align-middle">{{ $user->role }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Actions</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item text-warning" href="{{ route('account_edit', ['id' => $user->id]) }}">Edit</a>
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
        $("#userTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        });
    });

</script>
@endsection
