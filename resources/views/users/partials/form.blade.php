@if ($message = Session::get('success'))

<div class="alert alert-success alert-block">

    <button type="button" class="close" data-dismiss="alert">×</button>

    <strong>{{ $message }}</strong>

</div>

@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="bg-light p-2">
    <div class="row justify-content-center">

        <div class="profile-header-container">
            <div class="profile-header-img">
                <img class="rounded-circle" src="{{ @$user->profile_image }}" />
            </div>
        </div>

    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <input name="name" type="text" value="{{@$user->name}}" class="form-control ml-1 shadow-none" placeholder="Enter Name"></>
    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <input name="email" type="text" value="{{@$user->email}}" class="form-control ml-1 shadow-none" placeholder="Enter Email"></>
    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <select class="form-control" name="gender">
            @foreach ($genders as $key => $gender)
                <option value="{{$key}}">{{$gender}}</option>
            @endforeach
          </select>
    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <input name="password" type="password" value="" class="form-control ml-1 shadow-none" placeholder="Enter Password"></>
    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <input name="password_confirmation" type="password" value="" class="form-control ml-1 shadow-none" placeholder="Enter Confirm Password"></>
    </div>
    <div class="d-flex flex-row align-items-start mt-2">
        <input type="file" class="form-control-file" name="image" id="imageFile" aria-describedby="fileHelp">
        <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
    </div>
    <div class="mt-2 text-right">
        <button class="btn btn-primary btn-sm shadow-none" type="submit">Update</button>
    </div>
</div>
