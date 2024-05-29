@extends('layouts.master')

@section('content')
@section('breadcrumb')
    Dashboard
@endsection
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card  card-tasks">
                <div class="card-header ">
                    <h5 class="card-category">Java Residence</h5>
                    <h4 class="card-title">Welcome back, <span style="font-weight: bolder">{{$user->name}}</span>! Here's your dashboard</h4>
                </div>
                <div class="card-body ">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('jquery')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

<script>
    var myModal = document.getElementById('myModal')
var myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus()
})
</script>
<script src="http://127.0.0.1:8000/assets/demo/demo.js"></script>
<script>
    $(document).ready(function() {
  // Javascript method's body can be found in assets/js/demos.js
  demo.initDashboardPageCharts();

});
</script>
@endsection