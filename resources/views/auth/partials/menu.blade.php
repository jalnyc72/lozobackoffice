@inject('security', 'Digbang\Security\Contracts\SecurityApi')

@if($security->getUser())
<ul class="headermenu">
	<li>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{{ trim(trans('backoffice::auth.user_name', ['name' => $security->getUser()->getName()->getFirstName(), 'lastname' => $security->getUser()->getName()->getLastName()])) ?: $security->getUser()->getEmail() }}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-usermenu pull-right">
				<li>
					<a href="{!! route("$contextKey.auth.logout") !!}">
						<i class="fa fa-sign-out"></i> {!! Lang::get('backoffice::auth.sign_out') !!}
					</a>
				</li>
			</ul>
		</div>
	</li>
</ul>
@endif
