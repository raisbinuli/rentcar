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
                    <h3 class="card-title"></h3>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <ul id="cart-errors" style="padding-left:14px;"></ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul id="list-price" style="padding-left:14px;">
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 border-top pb-2 pt-2">
                            <div id="total-price">
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
                                <label>Name</label><br/>
                                <input type="text" name="name" id="name" class="form-control" />
                                
                            </div>
                            <div class="form-group">
                                <label>Phone</label><br/>
                                <input type="text" name="phone" id="phone" class="form-control" />
                                
                            </div>
                            <div class="form-group">
                                <label>Address</label><br/>
                                <textarea name="address" id="address" class="form-control"></textarea> 
                                
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
    let days = "";
    let total_price = "";
    
    if(localStorage.getItem("carts") && localStorage.getItem("days")){
        
        carts = JSON.parse(localStorage.getItem("carts"));
        days = localStorage.getItem("days");

        ajaxRentCar('{{ url("/all-carts") }}',{carts : carts,days:localStorage.getItem("days")},'GET',function(result){

            let liComponent = "";
            if(result.carts.length > 0){
                $.each(result.carts,function(key,value){
                    liComponent = liComponent + '<li>' + value.brand+ ' year ' +value.built + ' price -> ' + value.price;
                    if(value.price_disc !== 0){
                        liComponent = liComponent + '<br/> price after discount ( built < 2010) -> ' + value.price_disc;
                    }
                    liComponent = liComponent + '</li>'
                });
                
            }
            $("#list-price").html(liComponent);
            let contentTotal = "";
            if(typeof result.totalPriceBeforeDisc !== 'undefined'){

                contentTotal = contentTotal + "Total before discount : " + result.totalPriceBeforeDisc +"<br/>";

                if(typeof result.totalPriceDiscCars !== 'undefined'){
                    contentTotal = contentTotal + "Total discout more than 2 cars ("+result.totalCars+" Cars) 10% : " + result.totalPriceDiscCars +"<br/>";
                }

                if(typeof result.totalPriceDiscDays !== 'undefined'){
                    contentTotal = contentTotal + "Total discout more than 3 days ("+result.days+" Days) 5% : " + result.totalPriceDiscDays +"<br/>";
                }

            }

            contentTotal = contentTotal + "Total price ("+result.days+" Days) : " + result.totalPrice +"<br/>";
            $("#total-price").html(contentTotal);

            total_price = result.totalPrice;

        },"");        
        
    }

    else {
        
        window.location = '{{ url("/") }}'; 
    }
       
    
    $("#next-step").on("click",function(){
        
        let dataSend = {
            name :  $("#name").val(),
            phone : $("#phone").val(),
            carts : carts,
            address : $("#address").val(),
            days : days,
            total_price : total_price
        };

        ajaxRentCar('{{ url("/carts") }}',dataSend,'POST',function(result){

            if(result.status == "Success"){
                localStorage.clear();

                window.location = '{{ url("/finished") }}'; 

            }

        },"cart-errors");
        

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
