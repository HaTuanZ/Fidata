@extends('plugins/member::layouts.skeleton')
@section('content')
    <div class="settings crop-avatar">
        <div class="container">
            <div class="row">
                @include('plugins/member::settings.sidebar')
                <div class="col-12 col-md-9">
                    <div class="main-dashboard-form">
                        <!-- Setting Title -->
                        <div class="row">
                            <div class="col-12">
                                <h4 class="with-actions">{{ trans('plugins/member::dashboard.referrer_heading_title') }}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 order-lg-0">
                                <div class="my-affiliate" style="margin-bottom: 30px;"><span id="text"><?php echo route('public.member.register') ?>?ref={{ $user->affiliate_id }}</span> <a href="javascript:;" onclick="copyText()"><i class="fas fa-copy"></i></a></div>
                                <table class="table bordered">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($members as $member): ?>
                                        <tr>
                                            <td><?php echo $member->affiliate_id ?></td>
                                            <td><?php echo $member->first_name ?> <?php echo $member->last_name ?></td>
                                            <td><?php echo $member->email ?></td>
                                            <td><?php echo $member->phone ?></td>
                                            <td><?php echo $member->created_at ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
    {!! JsValidator::formRequest(\Botble\Member\Http\Requests\SettingRequest::class); !!}
    <script type="text/javascript">
        "use strict";
        function copyText() {
            // Get the text field
            var copyText = document.getElementById("text");

            // Select the text field
            //copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.innerText);

            // Alert the copied text
            alert("Copied: " + copyText.innerText);
        }
    </script>
@endpush
