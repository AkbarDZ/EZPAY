@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Product Edit</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">General</a></li>
                    <li class="breadcrumb-item active">Product</li>
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
            <form method="POST" action="{{ route('product_update', ['id' => $prod->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input name="prod_name" id="prod_name" autocomplete="off" type="text" class="form-control form-control-border border-width-2" placeholder="Enter Name" value="{{$prod->prod_name}}">
                    </div>
                    <div class="form-group">
                        <label>Product Price</label>
                        <input name="price" id="price" autocomplete="off" type="number" class="form-control form-control-border border-width-2" placeholder="Enter Price" value="{{$prod->price}}">
                    </div>
                    <div class="form-group">
                        <label>Product Stock</label>
                        <input name="stock" id="stock" autocomplete="off" type="number" class="form-control form-control-border border-width-2" placeholder="Enter Stock" value="{{$prod->stock}}">
                    </div>
                    <div class="form-group">
                        <label>Product SKU</label>
                        <input name="sku" id="sku" autocomplete="off" type="text" class="form-control form-control-border border-width-2" placeholder="Enter SKU" value="{{$prod->sku}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Current Image</label>
                        <div>
                            @if ($prod->prod_img)
                                <img src="data:image/jpeg;base64,{{ $prod->prod_img }}" style="max-height: 5rem" class="rounded mx-auto d-block" alt="Product Image">
                            @else
                                No Image Available
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">New Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="prod_img" class="custom-file-input" id="prod_img" onchange="previewImage(this)">
                                <label class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <img id="new-img-preview" src="#" alt="New Image Preview" style="max-height: 5rem; display: none;">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-primary" href="{{ route('product_table') }}">Cancel</a>
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

    function previewImage(input) {
        var imgPreview = document.getElementById('new-img-preview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            imgPreview.style.display = 'none';
        }
    }
</script>
@endsection
