@extends('layouts.admin.dashboard')
@section('contents')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="col-6 offset-3">
                        @if (Session()->has('error'))
                        <script>
                            swal("warning", "{{ Session()->get('error') }}", "warning")
                        </script>
                        @endif
                        @if (Session()->has('success'))
                        <script>
                            swal("success", "{{ Session()->get('success') }}", "success")
                        </script>
                        @endif
                        <form class="mt-4" method="post" action="{{ route('change.password', $data->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 mt-4"><input class="form-control" type="password" name="oldpassword"
                                        placeholder="Old Password" value="{{ old('oldpassword') }}" />

                                </div>
                                <div class="col-12 mt-4"><input class="form-control" type="password"
                                        placeholder="New Password" aria-label="New Password"  name="newpassword" value="{{ old('newpassword') }}" />
                                        @error('newpassword')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                </div>


                            </div>
                            <div class="col-12 mt-4"><button class="btn btn-success w-100" type="submit">Change
                                    Password</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
