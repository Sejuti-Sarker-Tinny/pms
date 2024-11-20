@extends('layouts.admin.dashboard')
@section('contents')
@if(Session::has('success'))
<script>
    Swal.fire({
    position: 'center',
    icon: 'success',
    text: '{{Session::get('success')}}',
    showConfirmButton: true,
    timer: '5000',
})
</script>
@endif
@if(Session::has('error'))
<script>
    Swal.fire({
    position: 'center',
    icon: 'error',
    text: '{{Session::get('error')}}',
    showConfirmButton: true,
    timer: '5000',
})

</script>

@endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{$data->product_id}}">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title"><i class="fas fa-hamburger"></i><b> Edit Product</b></h4>
                            </div>
                            <div class="col-md-4 text-right">
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-7">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="form-group row mb-3 @error('product_name') is-invalid @enderror">
                            <label class="col-sm-4 col-form-label"><b>Product Name:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_name"
                                    value="{{ $data->product_name }}" required>
                                @error('product_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_wholesale_price') is-invalid @enderror">
                            <label class="col-sm-4 col-form-label"><b>Product Wholesale Sale Unit Price:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_wholesale_price" value="{{ $data->product_wholesale_price }}"
                                    required>
                                @error('product_wholesale_price')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_retail_price') is-invalid @enderror">
                            <label class="col-sm-4 col-form-label"><b>Product Retail Sale Unit Price:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_retail_price" value="{{ $data->product_retail_price }}"
                                    required>
                                @error('product_retail_price')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_details') is-invalid @enderror">
                            <label class="col-sm-4 col-form-label"><b>Product Details:<span
                                        class="text-danger"></span></b></label>

                            <div class="col-sm-6">
                                <textarea type="text" class="form-control" name="product_details"
                                    value="{{old('product_details')}}" placeholder="">{{ $data->product_details }}</textarea>
                                @error('product_details')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_photo') is-invalid @enderror">
                            <label class="col-sm-4 col-form-label"><b>Photo:</b></label>
                            <div class="col-sm-6">
                            <input type="file" onchange="readProductURL(this);" class="form-control" name="product_photo" value="{{ $data->product_photo }}">
                            @error('product_photo')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <br>
                            <img id="product_photo_review"  height="80" src="{{ asset($data->product_photo) }}" alt=""/>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer card_footer text-center">

                        <button type="submit" class="btn btn-md btn-primary">Update Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#category').change(function(){
            let id = $(this).val();
            //console.log(id)
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
           });




            $.ajax({
                dataType: 'json',
                url: "/dashboard/getSubcategory/"+id,
                type: "GET",
                success: function (data) {
                  //  console.log(data);
                    $('select[name="sub_category_id"]').empty();
                    $.each(data, function(key,data){
                      $('select[name="sub_category_id"]').append('<option selected value="'+ data.id +'">'+ data.sub_cat_name +'</option>');
                });
                },
               error: function(error) {
                    console.log(error);
               }
            });
        });

    });

</script>
@endsection
