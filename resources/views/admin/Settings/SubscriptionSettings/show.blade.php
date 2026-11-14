<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
    $bg = 'light';
} else {
    $text = 'light';
    $bg = 'dark';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel ">
        <div class="content ">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 ">MT4 Subscription Settings</h1>
                </div>
                       @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-times-circle"></i> Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Alert!</strong> {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-info-circle"></i> Info!</strong> {{ session('info') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle"></i> Warning!</strong> {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Validation Errors!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="mb-5 row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card p-1 p-md-5 shadow-lg ">
                            <form method="post" action="javascript:void(0)" id="subform">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <h4 class="">Monthly Subscription Fee:</h4>
                                    <input type="text" name="monthlyfee" class="form-control  "
                                        value="{{ $settings->monthlyfee }}">
                                </div>

                                <div class="form-group">
                                    <h4 class="">Quaterly Subscription Fee:</h4>
                                    <input type="text" name="quaterlyfee" class="form-control  "
                                        value="{{ $settings->quarterlyfee }}">
                                </div>

                                <div class="form-group">
                                    <h4 class="">Yearly Subscription Fee:</h4>
                                    <input type="text" name="yearlyfee" class="form-control  "
                                        value="{{ $settings->yearlyfee }}">
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="px-5 btn btn-primary btn-lg" value="Save">
                                    <input type="hidden" name="id" value="1">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($settings->subscription_service == 'on')
            <script>
                document.getElementById("subscripton").checked = true;
            </script>
        @else
            <script>
                document.getElementById("subscriptonoff").checked = true;
            </script>
        @endif
        <script>
            // Submit email preference form
            $('#subform').on('submit', function() {
                //alert('love');
                $.ajax({
                    url: "{{ route('updatesubfee') }}",
                    type: 'POST',
                    data: $('#subform').serialize(),
                    success: function(response) {
                        if (response.status === 200) {
                            $.notify({
                                // options
                                icon: 'flaticon-alarm-1',
                                title: 'Success',
                                message: response.success,
                            }, {
                                // settings
                                type: 'success',
                                allow_dismiss: true,
                                newest_on_top: false,
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                offset: 20,
                                spacing: 10,
                                z_index: 1031,
                                delay: 5000,
                                timer: 1000,
                                animate: {
                                    enter: 'animated fadeInDown',
                                    exit: 'animated fadeOutUp'
                                },

                            });
                        } else {

                        }
                    },
                    error: function(error) {
                        console.log(error);
                    },
                });
            });
        </script>
    @endsection
