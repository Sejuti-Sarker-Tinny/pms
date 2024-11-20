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

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
    integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>
@endif


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fas fa-hamburger"></i><b> All Sale Information</b></h4>
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
                            <th>Invoice number</th>
                            <th>Product</th>
                            <th>Sale Type</th>
                            <th>Sale Date</th>
                            <th>Quantity</th>
                            <th>Price per Unit</th>
                            <th>Total Price</th>
                            <th>Discount in percentage</th>
                            <th>Total Price after Discount</th>
                            <th>Payment Status</th>
                            <th>Photo</th>
                            <th>Manage</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $c=1;
                        @endphp
                        @foreach($SaleInfo as $data)
                        <tr>
                            <td>{{$c++}}</td>
                            <td>{{$data->invoice_number}}</td>
                            <td>{{$data->product->product_name ?? 'N\A'}}</td>
                            <td>{{$data->sale_type}}</td>
                            <td>{{$data->sale_date}}</td>
                            <td>{{ $data->product_quantity }}</td>
                            <td>{{ number_format($data->product_price_per_unit ,2)}} Tk</td>
                            <td>{{ number_format($data->product_total_price ,2)}} Tk</td>
                            <td>{{$data->product_discount_in_percentage}}%</td>
                            <td>{{ number_format($data->product_total_price_after_discount ,2)}} Tk</td>
                            <td>{{$data->payment_status}}</td>
                            <td>
                                @if($data->sale_info_photo!='')
                                    <img src="{{asset('uploads/sale-info/'.$data->sale_info_photo)}}" alt="sale Info photo" class="img-fluid" height="65px" width="65px">
                                @else
                                    <!-- <img src="{{asset('contents/admin/assets')}}/img/avatar.png" alt="User photo" class="img-fluid" height="65px" width="65px"> -->
                                @endif
                            </td>
                            <td class="d-flex">
                                <a class="btn btn-info mr-1" href="{{ route('sale-info-edit', ['slug' => $data->sale_info_slug]) }}" title="Edit">Edit</a>
                                <a class="btn btn-info mr-1" href="{{ route('sale-info-view', ['slug' => $data->sale_info_slug]) }}" title="Download Invoice as PDF">View Invoice</a>


                                <form action="{{ route('sale-info-delete', $data->sale_info_id) }}" method="POST"
                                    class="ml-1">

                                    @method('DELETE')
                                    @csrf
                                    <a href="" data-id="{{ $data->id }}"class="dltbtn btn btn-danger mr-1"> Delete</a>
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
<script>
    const button = document.getElementById('download-button');

    function generatePDF() {
        // Choose the element that your content will be rendered to.
        const element = document.getElementById('invoice');
        // Choose the element and save the PDF for your user.
        html2pdf().from(element).save();
    }

    button.addEventListener('click', generatePDF);
</script>

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
				                text: "Once deleted, you will not be able to recover this sale Information!",
				                icon: "warning",
				                buttons: true,
				                dangerMode: true,
				            })
				            .then((willDelete) => {
				                if (willDelete) {
				                    form.submit();
				                    swal("Sale Information Deleted Successfully!", {
				                        icon: "success",
				                    });
				                } else {
				                    swal("Your sale information is safe!");
				                }
				            });

				    });
</script>
@endsection

