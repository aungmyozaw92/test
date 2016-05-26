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
					<i class="fa fa-sitemap"></i>Client Management
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse">
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<?php $i=0; ?>
						<tr>
							<td>{{ ++$i }}</td>
							<th>Name</th>
							<td>{{ $client->name}}</td>
						</tr>	
						<tr>
							<td>{{ ++$i }}</td>
							<th>Email</th>
							<td>{{ $client->email}}</td>
						</tr>	
						<tr>
							<td>{{ ++$i }}</td>
							<th>Mobile No.</th>
							<td>{{ $client->phone}}</td>
						</tr>	
						<tr>
							<td>{{ ++$i }}</td>
							<th>Address</th>
							<td>{{ $client->address}}</td>
						</tr>
						<tr>
							<td>{{ ++$i }}</td>
							<th>Status</th>
							<td>
								{{ ($client->is_banned)?'Banned':'Active' }}
								{{ ($client->is_active)?"<span class='label label-success'>verified</span>":"<span class='label label-default'>pending</span>" }}	
							</td>
						</tr>	
						<tr>
							<td>{{ ++$i }}</td>
							<th>Created At</th>
							<td>{{ $client->created_at}}</td>
						</tr>	
						<tr>
							<td>{{ ++$i }}</td>
							<th>Updated At</th>
							<td>{{ $client->updated_at}}</td>
						</tr>	
					</table>

				</div>
			</div>
		</div>
	</div>
@stop