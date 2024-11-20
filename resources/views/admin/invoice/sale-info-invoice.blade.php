<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>HTML-to-PDF Example</title>
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1"
		/>
		<!-- html2pdf CDN link -->
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
			integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		></script>
	</head>
	<body>
		<button id="download-button" class=" btn btn-danger">Download Invoice as PDF</button>
		
<div id="invoice">
        <center>
			<h4>Pharmacy Management System <br>
           </h4>
            <hr>

<div class="container">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="row">
              <div class="col-md-10 card_header_text">
                <b>Sales Invoice</b>
              </div>
              <div class="col-md-2 card_header_for_one_button">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <table class="table table-striped table-bordered view_table">
                <tr>
                  <td>Invoice number</td>
                  <td>:</td>
                  <td>
                  {{$data->invoice_number}}
                  </td>
                </tr>
                <tr>
                  <td>Product</td>
                  <td>:</td>
                  <td>
                  {{$data->product->product_name ?? 'N\A'}}
                  </td>
                </tr>
                <tr>
                  <td>Sale Type</td>
                  <td>:</td>
                  <td>
                  {{$data->sale_type}}
                  </td>
                </tr>
                <tr>
                  <td>Sale Date</td>
                  <td>:</td>
                  <td>
                  {{$data->sale_date}}
                  </td>
                </tr>
                <tr>
                  <td>Quantity</td>
                  <td>:</td>
                  <td>
                  {{$data->product_quantity}}
                  </td>
                </tr>
                <tr>
                  <td>Price per Unit</td>
                  <td>:</td>
                  <td>
                  {{ number_format($data->product_price_per_unit ,2)}} Tk
                  </td>
                </tr>
                <tr>
                  <td>Total Price</td>
                  <td>:</td>
                  <td>
                  {{ number_format($data->product_total_price ,2)}} Tk
                  </td>
                </tr>
                <tr>
                  <td>Discount in percentage</td>
                  <td>:</td>
                  <td>
                  {{$data->product_discount_in_percentage}}%
                  </td>
                </tr>
                <tr>
                  <td>Total Price after Discount</td>
                  <td>:</td>
                  <td>
                  {{ number_format($data->product_total_price_after_discount ,2)}} Tk
                  </td>
                </tr>
                <tr>
                  <td>Payment Status</td>
                  <td>:</td>
                  <td>
                  {{$data->payment_status}}
                  </td>
                </tr>
                <tr>
                  <td>Remarks</td>
                  <td>:</td>
                  <td>
                  {{$data->sale_remarks}}
                  </td>
                </tr>
              </table>
            </div>
            <div class="col-md-2"></div>
            </div>
          </div>
          <div class="card-footer text-muted">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</center>
</div>

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
	</body>
</html>