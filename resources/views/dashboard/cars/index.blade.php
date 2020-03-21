@extends('dashboard.layout')
@section('other-css')
<style>
.invalid-feedback{
    display:list-item;
}
</style>
@endsection
@section('content')
    <!-- HTML -->

    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">All Cars</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <button id="btn-add" class="btn btn-primary mb-4">Add</button>
            <table id="cars-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Build</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $number = 1;
                    @endphp
                    @foreach($allCars as $car)
                    
                    <tr>
                        <td>{{$number}}</td>
                        <td>{{$car->brand}}</td>
                        <td>{{$car->price}}</td>
                        <td>{{$car->built}}</td>
                        <td>
                            <button id="btn-edit" class="btn btn-primary" data-id="{{$car->id}}">Edit</button>
                            <button id="btn-delete" class="btn btn-danger"  data-id="{{$car->id}}">Delete</button>
                        </td>
                    </tr>
                    @php
                        $number++;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Number</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Build</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

<div class="modal" tabindex="-1" role="dialog" id="modal-add">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Car</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul id="add-errors"></ul>
        <form role="form" id="form-add">
            <div class="card-body">
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" class="form-control" id="brand" placeholder="Enter Brand" name="brand">
                </div>
                <div class="form-group">
                    <label>Built</label>
                    <input type="text" class="form-control" id="built" placeholder="Enter Built" name="built">
                </div>                
                <div class="form-group">
                    <label >Price</label>
                    <input type="text" class="form-control" id="price" placeholder="Enter Price" name="price">
                </div>  
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="button" id="submit-add" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>      
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-edit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Car</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul id="edit-errors"></ul>
        <form role="form" id="form-add">
            <div class="card-body">
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" class="form-control" id="brand" placeholder="Enter Brand" name="brand">
                </div>
                <div class="form-group">
                    <label>Built</label>
                    <input type="text" class="form-control" id="built" placeholder="Enter Built" name="built">
                </div>                
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" class="form-control" id="price" placeholder="Enter Price" name="price">
                </div>  
                <input type="hidden" id="id" name="id" />
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="button" id="submit-edit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>      
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-delete">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Car</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are You sure want to delete this data
        <input type="hidden" id="id" name="id" />
      </div>     
      <div class="modal-footer">
        <button id="submit-delete" type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div> 
    </div>
  </div>
</div>
@endsection

@section('other-js')
<script>
  $(function () {
    $("#btn-add").on('click',function(){
        $("#modal-add").modal('show');
    });
    $("#submit-add").on('click',function(){
        ajaxRentCar('{{ url("dashboard/cars") }}',{built :$("#modal-add").find("#built").val(),price :$("#modal-add").find("#price").val(),brand :$("#modal-add").find("#brand").val()},'POST',function(result){
            if(result.status == "Success"){
                location.reload();
            }
        },"add-errors");
    });
    $("#submit-edit").on('click',function(){
        ajaxRentCar('{{ url("dashboard/cars") }}',{built :$("#modal-edit").find("#built").val(),price :$("#modal-edit").find("#price").val(),brand :$("#modal-edit").find("#brand").val(),id :$("#modal-edit").find("#id").val()},'PUT',function(result){
            if(result.status == "Success"){
                location.reload();
            }
        },"edit-errors");
    });
    $("#submit-delete").on('click',function(){
        ajaxRentCar('{{ url("dashboard/cars") }}/'+$("#modal-delete").find("#id").val(),{},'DELETE',function(result){
            if(result.status == "Success"){
                location.reload();
            }
        },"");
    });
    $("button#btn-delete").on('click',function(){
        $("#modal-delete").find('#id').val($(this).attr("data-id"));
        $("#modal-delete").modal('show');
        
    });
    $("button#btn-edit").on('click',function(){
        
        ajaxRentCar('{{ url("dashboard/cars") }}/'+$(this).attr("data-id"),"","GET",function(result){
            if(result.length > 0){
                
                $.each(result[0],function(key,value){                    
                    $("#modal-edit").find(`input[name="`+key+`"]`).val(value);    
                });
                $("#modal-edit").modal('show');
            }
        },"");
    });
    
    $('#cars-table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

  });
</script>
@endsection
