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
                <b>Custom Report</b>
              </div>
              <div class="col-md-4 card_header_for_three_button">
              </div>
            </div>
          </div>
          <div class="card-body">
            <form action="" method="post">
              <div class="row summary_date_form_section">
                <div class="col-md-3">
                  <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label bg-success text-white"><i class=""><span class="search_text"> From</span></i></label>
                    <div class="col-md-8">
                      <input type="date" class="form-control @error('from') is-invalid @enderror" id="datepickerFrom" name="from" value="{{old('from')}}">
                      @error('from')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label bg-success text-white"><i class=""><span class="search_text"> To</span></i></label>
                    <div class="col-md-8">
                      <input type="date" class="form-control @error('to') is-invalid @enderror" id="datepickerTo" name="to" value="{{old('to')}}">
                      @error('to')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <input type="button" class="btn btn-primary" id="search" value="SEARCH">
                </div>
                <div class="col-md-3 summary_month_name_section">
                  <p></p>
                </div>
              </div>
            </form>
            
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

@endsection