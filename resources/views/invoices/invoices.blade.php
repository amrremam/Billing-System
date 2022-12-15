@extends('layouts.master')
@section('title')
Invoices Menu
@stop
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
	<!-- breadcrumb -->
					<div class="breadcrumb-header justify-content-between">
						<div class="my-auto">
							<div class="d-flex">
								<h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Paid-invoices</span>
							</div>
						</div>
					</div>
	<!-- breadcrumb -->
@endsection
@section('content')
		@if (session()->has('delete_invoice'))
		<script>
			window.onload = function() {
				notif({
					msg: "invoice Deleted Succefully",
					type: "success"
				})
			}
		</script>
		@endif


		@if (session()->has('Status_Update'))
		<script>
			window.onload = function() {
				notif({
					msg: "Payment status updated succefully",
					type: "success"
				})
			}
		</script>
		@endif

		@if (session()->has('restore_invoice'))
		<script>
			window.onload = function() {
				notif({
					msg: "Invoice has been restored successfully",
					type: "success"
				})
			}
		</script>
		@endif

    <!-- row -->
    <div class="row">
        <!--div-->
		<div class="col-xl-12">
			<div class="card mg-b-20">
				<div class="card-header pb-0">
					<div class="d-flex justify-content-between">
						<h4 class="card-title mg-b-0">Invoices Menu</h4>
						<i class="mdi mdi-dots-horizontal text-gray"></i>
					</div>
				</div>
				<div class="card-header pb-0">
                    {{-- Add invoice --}}
                        <a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                                class="fas fa-plus"></i>&nbsp;Add Invoice</a>
					
                </div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="example1" class="table key-buttons text-md-nowrap">
							<thead>
								<tr>
									<th class="border-bottom-0">Number</th>
									<th class="border-bottom-0">Date</th>
									<th class="border-bottom-0">Invoice Date</th>
									<th class="border-bottom-0">Section</th>
									<th class="border-bottom-0">Discount</th>
									<th class="border-bottom-0">Rate_VAT</th>
									<th class="border-bottom-0">Value_VAT</th>
									<th class="border-bottom-0">note</th>
									<th class="border-bottom-0">Total</th>
									<th class="border-bottom-0">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0; ?>
								@foreach ($invoice as $invo)
								<?php $i++; ?>
								<tr>
									<td>{{$invo -> invoice_number}}</td>
									<td>{{$invo -> invoice_Date}}</td>
									<td>{{$invo -> Due_date}}</td>
									{{-- <td>{{$invo -> product}}</td> --}}
									<td><a href="{{ url('InvoicesDetails') }}/{{ $invo->id }}">{{ $invo->section->section_name }}</a></td>
									<td>{{$invo -> Discount}}</td>
									<td>{{$invo -> Rate_VAT}}</td>
									<td>{{$invo -> Value_VAT}}</td>
									<td>{{$invo -> note}}</td>
									<td>{{$invo -> Total}}</td>
									<td>
										@if ($invo->Value_Status == 1)
											<span class="text-success">{{ $invo->Status }}</span>
										@elseif($invo->Value_Status == 2)
											<span class="text-danger">{{ $invo->Status }}</span>
										@else
											<span class="text-warning">{{ $invo->Status }}</span>
										@endif
									</td>
									<td>
										<div class="dropdown">
											<button aria-expanded="false" aria-haspopup="true"
												class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
												type="button">Operation<i class="fas fa-caret-down ml-1"></i></button>
											<div class="dropdown-menu tx-13">
												{{-- Edit Invoice --}}
												<a class="dropdown-item" href=" {{ url('edit_invoice') }}/{{ $invo->id }}">Edit Invoice</a>

												{{-- Change Payment Status --}}
												<a class="dropdown-item"
												href="{{ URL::route('Status_show', [$invo->id]) }}"><i
													class=" text-success fas
												fa-money-bill"></i>&nbsp;&nbsp;
												Change payment status
											</a>

											{{-- Print invoice --}}
											<a class="dropdown-item" href="Print_invoice/{{ $invo->id }}"><i
													class="text-success fas fa-print"></i>&nbsp;&nbsp;Print
												invoice
											</a>

												{{-- Delete Invoice --}}
													<a class="dropdown-item" href="#" data-invoice_id="{{ $invo->id }}"
														data-toggle="modal" data-target="#delete_invoice"><i
															class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete Invoice</a>

											</div>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

<!--          Delete-invoice                   -->
			<div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Delete Invoice</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<form action="{{ url('invoice/destroy') }}" method="post">
								{{ method_field('delete') }}
								{{ csrf_field() }}
						</div>
						<div class="modal-body">
							{{-- !Delete Sure --}}
							<input type="hidden" name="invoice_id" id="invoice_id" value="">
						</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-danger">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- row closed -->
	</div>
</div>
	<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection




@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<script>
	$('#delete_invoice').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
		var invo_var = button.data('invoice_id')
		var modal = $(this)
		modal.find('.modal-body #invoice_id').val(invo_var);
	})
</script>

@endsection