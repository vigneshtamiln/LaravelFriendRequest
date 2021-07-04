@extends('layouts.app')
   
@section('content')
    <!-- Team -->
    <section id="" class="pb-5">
        <div class="container">
            <h5 class="section-title h1">OUR TEAM</h5>
            <div class="row">
                @foreach ($users as $user)
                    @if (@$user->followers->where('id', auth()->user()->id)->first()->pivot->status != 3)
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="image-flip" >
                                <div class="mainflip flip-0">
                                    <div class="frontside">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <p><img class=" img-fluid" src="{{ $user->profile_image }}" alt="card image"></p>
                                                <h4 class="card-title">{{$user->name}}</h4>
                                                <h5 class="card-title">{{$user->email}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="backside">
                                        <div class="card">
                                            <div class="card-body text-center mt-4">
                                                <h4 class="card-title">{{$user->name}}</h4>
                                                <h5 class="card-title">{{$user->email}}</h5>
                                                <h5 class="card-title">{{$user->gender ? $user::$genders[$user->gender] : ''}}</h5>
                                                <hr>
                                                @php
                                                    $btnTxt = "Add Friend";
                                                    $btnText = "Declined"
                                                @endphp
                                                @if ($user->followers->count() > 0)
                                                    @php
                                                        $status =  @$user->followers->first()->pivot->status;
                                                        $btnTxt = $status == 0 ? 'Pending' : ($status == 1 ? 'Approved' :'Add Friend');
                                                    @endphp

                                                @endif
                                                @if ($user->followings->count() > 0)
                                                    @php
                                                        $status =$user->followings->first()->pivot->status;
                                                        $btnText = $status == 0 ? 'Approve' : 'Declined';
                                                    @endphp
                                                    <div class = "btn btn-primary btn-sm changeStatus" data-userId = {{$user->id}} data-friendId = {{auth()->user()->id}} data-status = 1 > {{$btnText}} </div>
                                                @endif
                                                @if($user->followers->count() <= 0 && $user->followings->count() <= 0 && @$user->followers->first()->pivot->status == 0 && @$user->followers->first()->pivot->status == 0)
                                                    <div class = "btn btn-primary btn-sm changeStatus" data-friendId = {{$user->id}} data-userId = {{auth()->user()->id}} data-status = 0 ><i class="fa fa-plus"> Add Friend </i></div>
                                                @else

                                                    @if(@$user->followers->first()->pivot->status == 1 || @$user->followers->first()->pivot->status == 1)
                                                        <div class = "btn btn-primary btn-sm changeStatus" data-friendId = {{$user->id}} data-userId = {{auth()->user()->id}} data-status = 0 ><i class="fa fa-times"> Declined</i></div>
                                                        <a href="{{route('users.myfriends', ['id' => $user->id])}}"> 
                                                            <div class = "btn btn-primary btn-sm changeStatus"><i class="fa fa-eye"> View Friends </i>  </div>
                                                        </a>
                                                        @else
                                                        <div class = "btn btn-primary btn-sm changeStatus" data-friendId = {{$user->id}} data-userId = {{auth()->user()->id}} data-status = 3 ><i class="fa fa-clock-o"> Pending</i></div>
                                                        <div class = "btn btn-primary btn-sm changeStatus" data-friendId = {{$user->id}} data-userId = {{auth()->user()->id}} data-status = 0 ><i class="fa fa-times"> Declined</i></div>

                                                    @endif
                                                @endif
                                                <div class = "btn btn-primary btn-sm changeStatus" data-friendId = {{$user->id}} data-userId = {{auth()->user()->id}} data-status = 3 ><i class="fa fa-trash"> Delete</i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if ($users->count() <= 0)
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <h6 class=" section-title h6">Friends List is Empty</h6>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.changeStatus', function(){
                $.ajax({
                    url:"{{route('users.addfriends')}}",
                    method:'POST',
                    data:{
                        friend_id : $(this).data('friendid'),
                        user_id : $(this).data('userid'),
                        status : $(this).data('status'),
                    },
                    success:function(res){
                        toastr.success(res);
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                })
            });
        });
    </script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endpush

@push('styles')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
@endpush



