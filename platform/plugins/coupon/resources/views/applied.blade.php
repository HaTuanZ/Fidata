@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="widget meta-boxes">
        <div class="widget-title">
            <h4>&nbsp; {{ trans('plugins/coupon::coupon.applied') }}</h4>
        </div>
        <div class="widget-body box-applied">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Code</th>
                        <th>Applied date</th>
                        <th>Learn</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($applied_coupons as $key => $applied_coupon)
                        <tr>
                            <td class="text-start">
                                {!! $applied_coupon->user_id !!}
                            </td>
                            <td class="text-start">
                                {!! $applied_coupon->display_name !!}
                            </td>
                            <td class="text-start">
                                {!! $applied_coupon->email !!}
                            </td>
                            <td class="text-start">
                                {!! $applied_coupon->coupon_code !!}
                            </td>
                            <td class="text-start">
                                {!! $applied_coupon->last_updated !!}
                            </td>
                            <td class="text-start">
                                <a href="https://coingen.net/article/{!! $applied_coupon->slug !!}" target="_blank">{!! $applied_coupon->post_title !!}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@stop
