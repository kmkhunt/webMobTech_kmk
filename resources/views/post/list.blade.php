@extends('layouts.app')
@section("content")
<section id="content">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper">
    <!-- Search for small screen -->
    </div>
    <!--breadcrumbs end-->
    <!--start container-->
        <div class="container">
            <div class="row" style="display:block">
            @if(Session::has('success'))
               <div class="alert alert-success" alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" ><span aria-hidden="true">×</span></button>
               <strong>Success</strong> {{ Session::get('success') }}
               </div>
            @endif
            <div class="row" style="display:block;text-align:center">
             <b> Filter By Created Date </b> {!! Form::date("created_at","",["id"=>"created_at","onChange"=>"setDate(this.value)"]) !!}
            </div>
               <div style="float:left">
                  <h2>Post List</h2>
               </div>
               <div style="float:right;font-weight:bold;font-size:20px">
                  <a href="{{ route('post_add') }}">Add Post</a>
                </div>  
            </div>
            <table class="table table-bordered" id="table">
               <thead>
                  <tr>
                     <th>Post Name</th>
                     <th>Post Picutre</th>
                     <th>Created At</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
            <div style="float:right;font-weight:bold;font-size:20px">
                  <a href="{{ route('post_add') }}">Add Post</a>
                </div>
         </div>
       <script>
       var created_date = '';
       var obj_datatable
         $(function() {
            created_date = '';
            obj_datatable = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
               url: '{{ url('posts/index') }}',
               type: "get", // method  , by default get
               data: function (d) {
                        d.created_date = created_date;
               }
            },
            columns: [
                  { data: 'post_name', name: 'post_name' },
                  { data: 'post_image', name: 'post_image' },
                  { data: 'created_at', name: 'created_at' },
                  { data: 'action', name: 'action', orderable: false, searchable: false},
               ]
            });
         });

        function setDate(dt_current_date){
          created_date = dt_current_date;
          obj_datatable.draw();
        }
         </script>
    <!--end container-->
</section>
@endsection




