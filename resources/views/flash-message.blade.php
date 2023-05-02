@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>

        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('status'))
    <div class="alert alert-info alert-block alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ $message }}</strong>
    </div>
@endif



@if ($errors->any())
    <div class="alert alert-danger  alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>


    @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif


<style>
    .alert {
        margin: 1rem;
    }
</style>
