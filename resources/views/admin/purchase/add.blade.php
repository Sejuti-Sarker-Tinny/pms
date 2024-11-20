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
    use App\Models\User;
    use App\Models\Product;
    $suppliers = User::Where('role_id', '3')->get();
    $products = Product::all();
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('purchase-info.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title"><i class="fas fa-hamburger"></i><b> Add Purchase Information</b></h4>
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
                        <div class="form-group row mb-3 @error('challan_number') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Challan Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="challan_number"
                                    value="{{old('challan_number')}}" placeholder="Challan Number" required>
                                @error('challan_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('bill_number') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Bill Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="bill_number"
                                    value="{{old('bill_number')}}" placeholder="Bill Number" required>
                                @error('bill_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('purchase_date') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Purchase Date:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="date" class="form-control" name="purchase_date"
                                    value="{{old('purchase_date')}}" placeholder="Purchase Date" required>
                                @error('purchase_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('purchase_type') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Purchase Type:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select name="purchase_type" id="purchase_type" class="form-control" required>
                                    <option value="">Select Purchase Type</option>
                                    <option value="retail">Retail Price</option>
                                    <option value="wholesale">Wholesale Price</option>
                                </select>
                                @error('purchase_type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('supplier_id') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Supplier Name:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier Name</option>
                                    @forelse ($suppliers as $supplier )
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} - {{ $supplier->organization }}
                                    </option>
                                    @empty
                                    No Supplier Name found
                                    @endforelse
                                </select>
                                @error('supplier_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_id') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product Name:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <select name="product_id" id="product_id" class="form-control" required>
                                    <option value="">Select Product Name</option>
                                    @forelse ($products as $product )
                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}
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
                        <div class="form-group row mb-3 @error('carton_number') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Carton Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="carton_number"
                                    value="{{old('carton_number')}}" placeholder="Carton Number" required>
                                @error('carton_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('box_per_carton') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Box Per Carton Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="box_per_carton"
                                    value="{{old('box_per_carton')}}" placeholder="Box Per Carton Number" required>
                                @error('box_per_carton')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('pata_per_box') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Pata Per Box Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="pata_per_box"
                                    value="{{old('pata_per_box')}}" placeholder="Pata Per Box Number" required>
                                @error('pata_per_box')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_unit_per_pata') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product Unit Per Pata Number:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_unit_per_pata"
                                    value="{{old('product_unit_per_pata')}}" placeholder="Product Unit Per Pata Number" required>
                                @error('product_unit_per_pata')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('product_price_per_unit') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Product Price Per Unit:<span
                                        class="text-danger">*</span></b></label>

                            <div class="col-sm-6">

                                <input type="text" class="form-control" name="product_price_per_unit"
                                    value="{{old('product_price_per_unit')}}" placeholder="Product Price Per Unit" required>
                                @error('product_price_per_unit')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('purchase_info_photo') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Photo:</b></label>
                            <div class="col-sm-6">
                            <input type="file" onchange="readCustomerURL(this);" class="form-control" name="purchase_info_photo" value="">
                            @error('purchase_info_photo')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <br>
                            <img id="customer_photo_review" src="#" alt=""/>
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('purchase_remarks') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Purchase Remarks:<span
                                        class="text-danger"></span></b></label>

                            <div class="col-sm-6">
                                <textarea type="text" class="form-control" name="purchase_remarks"
                                    value="{{old('purchase_remarks')}}"
                                    required> </textarea>
                                @error('purchase_remarks')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer card_footer text-center">

                        <button type="submit" class="btn btn-md btn-primary">Add Purchase Information</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
