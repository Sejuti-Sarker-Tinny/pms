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

<section class="content">
  <div class="container-fluid">
    <div class="row">
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="row">
              <div class="col-md-8 card_header_text">
                <b>Monthly Report</b>
              </div>
              <div class="col-md-4 card_header_for_three_button">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row summary_date_form_section">
              <div class="col-md-9"></div>
              <div class="col-md-3 summary_month_name_section">
                <p><b>Month : <span class="text-danger">{{$fullMonth}}</span></b></p>
              </div>
            </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="reporttableinfo">
              <thead>
                <tr>
                    <th>Date</th>
                    <!-- <th>Invoice Number</th>
                    <th>Bill Number</th>
                    <th>Challan Number</th> -->
                    <th>Product</th>
                    <th>Purchase Amount</th>
                    <th>Sale Amount</th>
                </tr>
              </thead>
              <tbody>
                @foreach($expenses as $data)
                <tr>
                  <td>{{$data->purchase_date}}</td>
                  <td>{{$data->product->product_name}}</td>
                  <td>{{ number_format($data->product_total_price ,2)}} Tk</td>
                  <td>---</td>
                </tr>
                @endforeach
                @foreach($incomes as $data)
                <tr>
                  <td>{{$data->sale_date}}</td>
                  <td>{{$data->product->product_name}}</td>
                  <td>---</td>
                  <td>{{ number_format($data->product_total_price_after_discount ,2)}} Tk</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th>Total</th>
                  <th>{{ number_format($expenseTotal ,2)}} Tk</th>
                  <th>{{ number_format($incomeTotal ,2)}} Tk</th>
                </tr>
                <tr>
                  <th colspan="4" class="text-center">
                    <!-- Total Savings: -->
                    @if($incomeTotal > $expenseTotal)
                    Net profit: <span class="text-success">{{ number_format($incomeTotal-$expenseTotal ,2)}} Tk</span>
                    @else
                    Net loss: <span class="text-danger">{{ number_format($incomeTotal-$expenseTotal ,2)}} Tk</span>
                    @endif
                  </th>
                </tr>
              </tfoot>
            </table>
          </div>
          </div>
          
          <div class="card-footer text-muted">

          </div>
        </div>
      </div>
    </div>
    <!-- Main row -->
    <div class="row">

    <!-- /.content -->
    </div>
  </div>
</section>

<script>

// Data Table

    // $('#alltableinfo').DataTable({
    //     ordering:  true,
    //     searching: true,
    //     paging: true,
    //     select: true,
    //     //pageLength: 10
    // });


</script>

@endsection