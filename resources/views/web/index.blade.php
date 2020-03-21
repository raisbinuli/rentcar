@extends('web.layout')
@section('other-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
    padding: .46875rem .75rem;
    height: calc(2.25rem + 2px);
}
.delete-cart{
    transition:0.3s;
    cursor:pointer;
}
.delete-cart:hover{
    color:#dc3545;
}
</style>
@endsection
@section('content')
    <!-- HTML -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Cars</label>
                                <select name="car" class="form-control select2" style="width: 100%;">
                                    <option value="" selected="selected">Please select a car</option>
                                    @foreach($allCars as $car)
                                    
                                    <option value="{{ $car->id }}"> {{ $car->brand }}</option>
                                    @endforeach
                                </select>
                                <button id="add-more-car" class="btn btn-primary mt-2"> Add</button> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 border-top">
                            <div class="card-body table-responsive p-0">
                                <table id="table-cart" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Disc</th>
                                        <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 border-top pt-2">
                            <div class="form-group">
                                <label>Days</label><br/>
                                <input type="number" name="days" id="days" class="form-control" />
                                
                            </div>
                            <div class="form-group">
                                <button id="next-step" class="btn btn-primary mt-2 float-right"> Next</button>                                 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
        </div>
    </div>


@endsection

@section('other-js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
  $(function () {
    let carts = [];
    
    if(localStorage.getItem("carts")){

        carts = JSON.parse(localStorage.getItem("carts"))

        ajaxRentCar('{{ url("/carts") }}',{data : carts},'GET',function(result){

            if(typeof result.carts !== 'undefined'){

                let contentTd = "";

                $.each(result.carts,function(key,value){

                    contentTd = contentTd + funcContentTd(value);

                });

                $('#table-cart').find('tbody').append(contentTd);
                
            }

        },"");

        
        
    }
    if(localStorage.getItem("days")){
        $("#days").val(localStorage.getItem("days"));
    }
    
    $('.select2').select2();

    $(document).on('click','span.delete-cart', function(){
        for (var i = 0; i < carts.length; i++){
            
            if (carts[i] == $(this).attr("data-id")) { 
                
                // delete carts[i];
                carts.splice(i, 1);

                localStorage.setItem("carts", JSON.stringify(carts));
                break;
            }
        }
        $(this).parents('tr').remove();
    });

    $("#add-more-car").on('click',function(){

        if($(`select[name="car"]`).val() != ""){

            ajaxRentCar('{{ url("/getcar") }}/'+$(`select[name="car"]`).val(),{},'GET',function(result){

                if(result.length > 0){
                    let contentTd = funcContentTd(result[0]);
                    
                    $('#table-cart').find('tbody').append(contentTd);
                    carts.push(result[0].id);
                    localStorage.setItem("carts", JSON.stringify(carts));

                }
                
            },"");
        }
    });

    $("#next-step").on("click",function(){
        $("#days").removeClass('is-invalid');

        if(!$("#days").val() || $("#days").val() == ""){
            
            $("#days").addClass('is-invalid');
        }
        if($("#days").val() > 0 && $('#table-cart').find('tbody').find('tr').length > 0){
            
            localStorage.setItem("days", $("#days").val());
            window.location = '{{ url("/next") }}';            
        }

    });

    
  });

  function funcContentTd(objectTd){
        let contentTd = `<tr>
                        <td>`+objectTd.brand+`</td>
                        <td>`+objectTd.price+`</td>
                        <td>`+objectTd.disc+`</td>
                        <td><span data-id="`+objectTd.id+`" class="delete-cart fas fa-times"></span></td>
                        
                    </tr>`;
        return contentTd;
    }
</script>
@endsection
