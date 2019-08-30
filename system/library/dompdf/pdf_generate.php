<?php
/* include autoloader */
require_once 'dompdf/autoload.inc.php';

/* reference the Dompdf namespace */
use Dompdf\Dompdf;

/* instantiate and use the dompdf class */
$dompdf = new Dompdf();
?>
<style type="text/css">
tr.total, td.total {
    text-align: right;
}
td, th {
    padding-left: 5px !important;
}
</style>
<?php

echo $html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<div style="margin-top: 50px;width:100%">			
		<table width="80%">
			<tr>
			<td valign="top" width="2%";><b>VENDOR:</b> </td>
			<td width="25%";>
				Premium Incentive Sales, Inc.<br>
				9889 E. Easter Ave. .<br>
				Centennial, CO  80112.<br>
				www.gopremco.com.<br>
				303-771-4224.<br>
			</td>
			<td valign="top" style="text-align: right;">
				<h2>PURCHASE ORDER</h2>
				</td>
			</tr>
		</table>

		<table width="80%" style="margin-top:50px;">
			<tr>
				<td>
					<table>
						<tr>
						<td  valign="top"><b>BILL TO:</b> </td>
						<td>
							(Name) <br>
							(Company Name) <br>
							(Street Address) <br>
							(City, ST  Zip) <br>
							(Phone Number) <br>

						</td>
						</tr>
					</table>
				</td>
				<td>
					<table>
					<tr>
						<td valign="top"><b>P.O. NO.</b></td>
						<td valign="top"></td>
					</tr>
					<tr>
						<td valign="top"><b>DATE</b></td>
						<td valign="top"></td>
					</tr>
					<tr>
						<td valign="top"><b>ACCT. NO.</b></td>
						<td valign="top"></td>

					</tr>
					<tr>
						<td valign="top"><b>SALES REP.</b></td>
						<td valign="top">Ed Bernau</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>	
		<table  style="margin-top:50px;">
			<tr>
				<td valign="top"  ><b>SHIP TO:</b> </td>
				<td >
				(Name) <br>
				(Company Name) <br>
				(Street Address) <br>
				(City, ST  Zip) <br>
				(Phone Number) <br>
				</td>
			
			</tr>
		</table>		
		<div style="margin-top:50px;"><b>Notes:</b></div>
		<table  class="table table-bordered">
			<thead>
				<tr>
					<th>QTY</th>
					<th>ITEM #</th>
					<th>DESCRIPTION</th>
					<th>Size</th>
					<th>Color</th>
					<th>Unit Price</th>
					<th>LINE TOTAL</th>
				</tr>
			</thead>
				<tbody>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td class="total">000</td>
					<td class="total">000</td>
				</tr>
				
				<tr class="total">
					<td colspan="5" rowspan="3" style="text-align: left;">
						<p>
						Freight will be added to Pre-Payment form.<br>
						All revisions must be re-sent as "Revised Purchase Order".<br>
						Based on item availibility, items may ship seperately.</br>
						All billing information must be completed before order will be processed.
					</td>
					<td><b>SUBTOTAL</b></td>
					<td>000</td>
				</tr>
				<tr class="total">
					<td><b>SALES TAX</b></td>
					<td>000</td>
				</tr>
				<tr class="total">
					<td><b>TOTAL</b></td>
					<td>000</td>
				</tr>
				</tbody>
		</table>	
		<div class="col-sm-12" style="margin-top: 50px;    margin-bottom: 30px;">
				<div class="col-sm-3">			  
					<b>Authorized By</b>
				</div>
				<div class="col-sm-4" style="text-align:right;">
					<b>Date</b>
				</div>
			</div>
	</div>';

$dompdf->loadHtml($html);

// /* Render the HTML as PDF */
$dompdf->render();

// /* Output the generated PDF to Browser */
// $dompdf->stream();
$html = $dompdf->output();
file_put_contents("xyz.pdf",$html);
?>