@extends('layouts.admin.dashboard')
@section('contents')
<div class="container emp-profile">
    <form action="{{ route('update_user_photo') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$data->id}}">
        <input type="hidden" name="slug" value="{{$data->user_slug}}">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    @if ($data->photo==null)
                    <img src="{{ asset('contents/admin/assets/img/avatar.png') }}" alt="" />
                    @else
                    <img src="{{asset('uploads/customer/'.$data->photo)}}" alt="" />
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row mb-3 @error('photo') is-invalid @enderror">
                    <label class="col-sm-3 col-form-label"><b>Photo:</b></label>
                    <div class="col-sm-12 col-12">
                        <input type="file" onchange="readCustomerURL(this);" class="form-control" name="photo"
                            value="">
                        @error('photo')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <img id="customer_photo_review" src="#" alt="" />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mt-5 ">
                    <button type="submit" class="btn btn-info">Update Photo</button>
                </div>
            </div>
        </div>
    </form>
</div>
<style>
    .emp-profile {
        padding: 3%;
        margin-top: 3%;
        margin-bottom: 3%;
        border-radius: 0.5rem;
        background: #fff;
    }

    .profile-img {
        text-align: center;
    }

    .profile-img img {
        width: 70%;
        height: 100%;
    }
</style>

@endsection


