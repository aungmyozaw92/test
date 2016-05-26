@section('content')
	<div class="page-content">
		<h3 class="page-title">
			Client <small>management</small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="{{URL::to('admin')}}">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="{{URL::to('admin/client')}}">Client</a>
						<i class="fa fa-angle-right"></i>
				</li>
				<li>
					Client List
				</li>
			</ul>
		</div>
		<!-- BEGIN SAMPLE TABLE PORTLET-->
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-group"></i>Client Management
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse">
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<?php if(User::hasPermTo(MODULE,'create')){ ?>
						<div class="btn-group">
							<a href="{{ URL::to('admin/client/create') }}" > <button id="sample_editable_1_new" class="btn green">
								Add New <i class="fa fa-plus"></i>
							</button></a>
						</div>
					<?php } ?>
				</div>
				<?php if( Session::get('message') ){ ?>
				        <div class="alert alert-success">
					         	<?php echo Session::get('message'); ?>
				        </div>
				<?php } ?>
				<div class="table-responsive">
				<!-- Start Delete confirrm text !-->
				<span class="hide" id="delete_text">
					<h4>Are you sure you want to Delete this Client ?</h4>
			                	<h4>This action can't be undo. </h4>
			           </span>
			           <!-- End Delete confirrm text !-->
					<table class="table table-striped table-bordered table-hover" id="sample_2">
						<thead>
							<tr>
								<th>#</th>
								<th>Company Name</th>
								<th>Contact Person</th>
								<th>Registered Date</th>
								<th>Email</th>
								<th>Address</th>
								<th>Option</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach ($client as $row) {?>
									<td>{{++$i }}</td>
									<td>{{ $row->name }}</td>
									<td>{{ $row->contact }}</td>
									<td>{{ date('d-m-Y',$row->join_date) }}</td>
									<td>{{ $row->email}}</td>
									<td>
										<p>
									                  <strong> Address </strong> - <?= ($row->address)?$row->address:'NIL'; ?> <br/>
									                  <strong> Country </strong> - <?= ($row->country)?$row->country:'NIL'; ?> <br/>
									                  <strong> Mobile </strong> - <?= $row->mobile; ?><br/>
									                  <strong> Fax </strong> - <?= ($row->fax)?$row->fax:'NIL'; ?><br/>
									           </p>	
									</td>
									<td>
										<?php if(User::hasPermTo(MODULE,'view')){?>
											<!-- <a href="{{ URL::to('admin/client/'.$row->id) }}" class="btn green tooltips" data-placement="bottom" data-original-title="View"><i class="fa fa-eye icon-white"></i></a> -->
										<?php } ?>
										<?php  if(User::hasPermTo(MODULE,'edit')){?>
											<a href="{{ URL::to('admin/client/'.$row->id.'/edit/') }}" class="btn blue tooltips" data-placement="bottom" data-original-title="Edit"><i class="fa fa-pencil icon-white"></i></a>
										<?php } ?>
										<?php if(User::hasPermTo(MODULE,'delete')){ ?>
											{{ Form::open(array('method' => 'DELETE', 'route' => array('admin.client.destroy', $row->id), 'style'=>'display: inline')) }}
												<button type="button" class="btn red tooltips delete" value="x" data-placement="bottom" data-original-title="Delete"><i class="fa fa-trash-o icon-white"></i></button>
											{{ Form::close() }}
										<?php } ?>
									</td>
								</tr>	
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop