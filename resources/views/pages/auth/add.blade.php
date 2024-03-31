@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Add</h1>
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
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <!-- form start -->
            <form method="POST" action="{{ route('account_store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" id="name" autocomplete="off" type="text"
                            class="form-control form-control-border border-width-2" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" id="email" autocomplete="off" type="email"
                            class="form-control form-control-border border-width-2" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" id="password" autocomplete="off" type="password"
                            class="form-control form-control-border border-width-2" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="role" class="form-control form-control-border border-width-2">
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avatar</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input" id="avatar">
                                <label class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-primary" href="{{ route('account_table') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <!--/.col (left) -->
</div>
@endsection

@section('script')
<script>
    $(function () {
        bsCustomFileInput.init();
    });

</script>
@endsection
