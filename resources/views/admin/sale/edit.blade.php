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
@php
    use App\Models\SaleInfo;
    use App\Models\User;
    use App\Models\Product;
        $suppliers = User::Where('role_id', '3')->get();
        $products = Product::all();
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('sale-info.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sale_info_id" value="{{$data->sale_info_id}}">
                <input type="hidden" name="product_id" value="{{$data->product_id}}">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title"><i class="fas fa-hamburger"></i><b> Edit Sale Information</b></h4>
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
                
                        <div class="form-group row mb-3 @error('product_id') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product Name:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select id="product_id" class="form-control" disabled>
                                    <option value="">Select Product Name</option>
                                    @forelse ($products as $product )
                                    <option value="{{ $product->product_id }}" @if ( $data->product_id ==
                                    $product->product_id) selected @endif>{{ $product->product_name }}
                                    </option>
                                    @empty
                                    No Product Name found
                                    @endforelse
                                </select>
                                @error('product_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_quantity') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product Quantity:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_quantity"
                                    value="{{ $data->product_quantity }}" placeholder="Product Quantity" readonly>
                                @error('product_quantity')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('sale_type') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Sale Type:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select name="sale_type" id="sale_type" class="form-control" required>
                                    <option value="">Select Sale Type</option>
                                    <option value="retail" @if ( $data->sale_type ==
                                    'retail') selected @endif >Retail Price</option>
                                    <option value="wholesale" @if ( $data->sale_type ==
                                    'wholesale') selected @endif>Wholesale Price</option>
                                </select>
                                @error('sale_type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('sale_date') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Sale Date:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="date" class="form-control" name="sale_date"
                                    value="{{ $data->sale_date }}" placeholder="sale Date" required>
                                @error('sale_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_discount_in_percentage') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product discount in percentage:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_discount_in_percentage"
                                    value="{{ $data->product_discount_in_percentage }}" placeholder="Product discount in percentage" required>
                                @error('product_discount_in_percentage')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('payment_status') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Payment status:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select name="payment_status" id="payment_status" class="form-control" required>
                                    <option value="">Select payment status</option>
                                    <option value="paid" @if ( $data->payment_status ==
                                    'paid') selected @endif >Paid</option>
                                    <option value="unpaid" @if ( $data->payment_status ==
                                    'unpaid') selected @endif >Unpaid</option>
                                </select>
                                @error('payment_status')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('returned_product_quantity') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Returned product quantity:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="returned_product_quantity"
                                    value="{{ $data->returned_product_quantity }}" placeholder="Returned product quantity">
                                @error('returned_product_quantity')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('sale_info_photo') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Photo:</b></label>
                            <div class="col-sm-6">
                            <input type="file" onchange="readCustomerURL(this);" class="form-control" name="sale_info_photo" value="">
                            @error('sale_info_photo')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <br>
                            <img id="customer_photo_review" src="#" alt=""/>
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('sale_remarks') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Sale Remarks:<span
                                        class="text-danger"></span></b></label>

                            <div class="col-sm-6">
                                <textarea type="text" class="form-control" name="sale_remarks" required>{{ $data->sale_remarks }} </textarea>
                                @error('sale_remarks')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    </div>
                    

                    <div class="card-footer card_footer text-center">

                        <button type="submit" class="btn btn-md btn-primary">Update Sale Information</button>
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