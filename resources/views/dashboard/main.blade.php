@extends('dashboard.layout')

@section('content')
    <!-- HTML -->

    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">All Carts</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Address(s)</th>
                  <th>Days</th>
                  <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($allDataCart as $cart)
                <tr>
                  <td>{{$cart->name}}</td>
                  <td>{{$cart->phone}}</td>
                  <td>{{$cart->address}}</td>
                  <td>{{$cart->days}}</td>
                  <td>{{$cart->total_price}}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                <th>Name</th>
                  <th>Phone</th>
                  <th>Address(s)</th>
                  <th>Days</th>
                  <th>Total Price</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          
        </div>
        <!-- /.col -->
      </div>

@endsection

@section('other-js')
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
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