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
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fas fa-hamburger"></i><b>All Product</b></h4>
                        </div>
                        <div class="col-md-4 text-right">
                        </div>
                    </div>
                </div>


                <div class="card-body">

                <div class="table-responsive">
                    <table id="alltableinfo" class="table table-bordered table-striped table-hover dt-responsive">

                        <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Wholesale Unit Price</th>
                            <th>Retail Unit Price</th>
                            <th>Photo</th>
                            <th>Manage</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $c=1;
                        @endphp
                        @foreach($products as $data)
                        <tr>
                            <td>{{$c++}}</td>
                            <td>{{$data->product_name}}</td>
                            <td>{{ number_format($data->product_wholesale_price ,2)}} Tk</td>
                            <td>{{ number_format($data->product_retail_price ,2)}} Tk</td>
                            <td>
                                @if($data->product_photo!='')
                                    <img src="{{asset('uploads/product/'.$data->product_photo)}}" alt="Product photo" class="img-fluid" height="65px" width="65px">
                                @else
                                    <!-- <img src="{{asset('contents/admin/assets')}}/img/avatar.png" alt="User photo" class="img-fluid" height="65px" width="65px"> -->
                                @endif
                            </td>
                            <td class="d-flex">
                                <a class="btn btn-info" href="{{ route('product_edit', ['slug' => $data->product_slug]) }}" title="Edit">Edit</a>

                                <form action="{{ route('product_delete', $data->product_id) }}" method="POST"
                                    class="ml-1">

                                    @method('DELETE')
                                    @csrf
                                    <a href="" data-id="{{ $data->product_id }}"class="dltbtn btn btn-danger mr-1"> Delete</a>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $.ajaxSetup({
				        headers: {
				            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				        }
				    });
				    $('.dltbtn').click(function(e) {
				        var form = $(this).closest('form');
				        var dataId = $(this).data('id');
				        e.preventDefault();
				        swal({
				                title: "Are you sure?",
				                text: "Once deleted, you will not be able to recover this product!",
				                icon: "warning",
				                buttons: true,
				                dangerMode: true,
				            })
				            .then((willDelete) => {
				                if (willDelete) {
				                    form.submit();
				                } else {
				                }
				            });

				    });
</script>
@endsection

