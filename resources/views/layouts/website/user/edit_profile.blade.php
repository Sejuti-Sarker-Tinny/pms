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
            <form method="post" action="{{ route('update_user_info') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-7">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="slug" value="{{$data->user_slug}}">
                        <div class="form-group row mb-3 @error('name') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Name:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" value="{{$data->name}}"
                                     required>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('email') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Email:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="email" value="{{$data->email}}"
                                     required readonly>
                                @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('phone') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Phone:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" value="{{$data->phone}}"
                                     required>
                                @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3 @error('address') is-invalid @enderror">
                            <label class="col-sm-3 col-form-label"><b>Address:<span
                                        class="text-danger">*</span></b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="address" value="{{$data->address}}"
                                     required >
                                @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="card-footer card_footer text-center">
                        <button type="submit" class="btn btn-md btn-primary">Update Information</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection

