@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center"style="margin-top: 36px;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile Update') }}</div>
                <form action="{{route('users.update', $id)}}" method="POST" enctype="multipart/form-data" files="true">
                    @csrf
                    @method('PUT')
                    @include('users.partials.form', $model)
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
