@extends('layouts.admin.dashboard')
@section('contents')
@if(Session::has('error'))
<script>
Swal.fire({
    position: 'center',
    icon: 'error',
    text: '{{Session::get('error')}}',
    toast: 'false',
    showConfirmButton: true,
    timer: '5000',
})

</script>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-12">

        
            <form method="post" action="{{ route('submit_supplier') }}" enctype="multipart/form-data">    
            
            @csrf
            <div class="card">
            
                <div class="card-header">
                
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fas fa-users"></i><b> Add Supplier Information</b></h4>
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
                <input type="hidden" name="role_id" value="3">
                <div class="form-group row mb-3 @error('name') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Name:<span class="text-danger">*</span></b></label>
                    
                    <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                    
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>

                <div class="form-group row mb-3 @error('email') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Email:<span class="text-danger">*</span></b></label>
                    
                    <div class="col-sm-6">
                    
                    <input type="email" class="form-control" name="email" value="{{old('email')}}" required>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>

                <div class="form-group row mb-3 @error('phone') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Phone:<span class="text-danger">*</span></b></label>
                    
                    <div class="col-sm-6">
                    
                    <input type="phone" class="form-control" name="phone" value="{{old('phone')}}" required>
                    @error('phone')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>

                <div class="form-group row mb-3 @error('designation') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Designation:<span class="text-danger"></span></b></label>
                    
                    <div class="col-sm-6">
                    
                    <input type="designation" class="form-control" name="designation" value="{{old('designation')}}">
                    @error('designation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>

                <div class="form-group row mb-3 @error('organization') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Organization:<span class="text-danger"></span></b></label>
                    
                    <div class="col-sm-6">
                    
                    <input type="organization" class="form-control" name="organization" value="{{old('organization')}}">
                    @error('organization')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>
  
                <div class="form-group row mb-3 @error('address') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Address:<span
                                class="text-danger"></span></b></label>
                    <div class="col-sm-6">
                        <textarea type="text" class="form-control" name="address"
                            value="{{old('address')}}" placeholder=""></textarea>
                        @error('address')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>   
                
                <div class="form-group row mb-3 @error('remarks') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Remarks:<span
                                class="text-danger"></span></b></label>
                    <div class="col-sm-6">
                        <textarea type="text" class="form-control" name="remarks"
                            value="{{old('remarks')}}" placeholder=""></textarea>
                        @error('remarks')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>  

                <div class="form-group row mb-3 @error('password') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Password:<span class="text-danger">*</span></b></label>
                    
                    <div class="col-sm-6">
                    
                    <input type="password" class="form-control" name="password" value="" required>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    </div>
                </div>
                         
                <div class="form-group row mb-3 @error('photo') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Photo:</b></label>
                    <div class="col-sm-6">
                    <input type="file" onchange="readSupplierURL(this);" class="form-control" name="photo" value="">
                    @error('photo')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <img id="supplier_photo_review" src="#" alt=""/>
                    </div>
                </div>

                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-md btn-primary">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection