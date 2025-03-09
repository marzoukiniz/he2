@extends('backend.layouts.master')

@section('main-content')
<div class="container-fluid">
 <!-- DataTales Example -->
 <div class="card shaow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Account Statement List</h6>
      <a href="{{route('account-statement.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add Statement"><i class="fas fa-plus"></i> Add Statement</a>
    </div>

    <div class="row">
    <!-- Pie Chart -->
    <div class="col-md-4">
        <canvas id="profitPieChart"></canvas>
    </div>

    <!-- Bar Chart -->
    <div class="col-md-8">
        <canvas id="profitBarChart"></canvas>
    </div>
</div>

<!-- فلترة بالتاريخ -->
<div class="row mt-3">
    <div class="col-md-4">
        <input type="date" id="fromDate" class="form-control" placeholder="From Date">
    </div>
    <div class="col-md-4">
        <input type="date" id="toDate" class="form-control" placeholder="To Date">
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary" onclick="filterStatements()">Filter</button>
    </div>
</div>

    <div class="card-body">
      <div class="table-responsive">
        @if(count($statements)>0)
        <table class="table table-bordered" id="statement-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th> <!-- ✅ إضافة التاريخ -->
              <th>Total Sales</th>
              <th>Total Expense</th>
              <th>Type</th>
              <th>Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Date</th> <!-- ✅ إضافة التاريخ -->
              <th>Total Sales</th>
              <th>Total Expense</th>
              <th>Type</th>
              <th>Notes</th>
              <th>Action</th>
            </tr>
          </tfoot>
          <tbody>
            @foreach($statements as $statement)
                <tr>
                    <td>{{$statement->id}}</td>
                    <td>{{ \Carbon\Carbon::parse($statement->created_at)->format('d M Y, h:i A') }}</td> <!-- ✅ عرض التاريخ -->
                    <td>{{$statement->total_sales}}</td>
                    <td>{{$statement->total_expense}}</td>
                    <td>
                        @if($statement->type == 'in')
                            <span class="badge badge-success">IN</span> <!-- ✅ أخضر لـ in -->
                        @else
                            <span class="badge badge-danger">OUT</span> <!-- ✅ أحمر لـ out -->
                        @endif
                    </td>
                    <td>{{$statement->notes}}</td>
                    <td>
                        <a href="{{route('account-statement.edit', $statement->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{route('account-statement.destroy',[$statement->id])}}">
                      @csrf
                      @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$statement->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$statements->links()}}</span>
        @else
          <h6 class="text-center">No Account Statements found!!! Please create one.</h6>
        @endif
      </div>
    </div>
</div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@endpush

@push('scripts')
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <script>
      $('#statement-dataTable').DataTable({
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [5] // تعديل رقم العمود ليتناسب مع إضافة التاريخ
                }
            ]
        });

      $(document).ready(function(){
        $('.dltBtn').click(function(e){
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                   form.submit();
                } else {
                    swal("Your data is safe!");
                }
            });
        });
      });
  </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let salesData = @json($statements->pluck('total_sales'));  // الدخل (IN)
        let expenseData = @json($statements->pluck('total_expense'));  // المصاريف (OUT)

        // حساب الربح الإجمالي = مجموع (IN - OUT)
        let totalProfit = salesData.reduce((sum, sale, index) => sum + (sale - (expenseData[index] ?? 0)), 0);

        // تسميات المحور X (التواريخ)
        let labels = @json($statements->pluck('created_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')));

        // ✅ 1. الرسم البياني الدائري (Pie Chart) لعرض نسبة الربح والخسارة
        new Chart(document.getElementById("profitPieChart"), {
            type: "doughnut",
            data: {
                labels: ["Profit", "Loss"],
                datasets: [{
                    data: [
                        totalProfit > 0 ? totalProfit : 0,  // الربح الكلي إذا كان موجبًا
                        totalProfit < 0 ? Math.abs(totalProfit) : 0 // الخسارة الكلية إذا كانت سالبة
                    ],
                    backgroundColor: ["#28a745", "#dc3545"] // أخضر للربح - أحمر للخسارة
                }]
            }
        });

        // ✅ 2. الرسم البياني العمودي (Bar Chart) مع خط الربح
        new Chart(document.getElementById("profitBarChart"), {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "IN (الدخل)",
                        data: salesData,
                        backgroundColor: "#28a745", // أخضر
                        type: "bar"
                    },
                    {
                        label: "OUT (المصاريف)",
                        data: expenseData,
                        backgroundColor: "#dc3545", // أحمر
                        type: "bar"
                    },
                    {
                        label: "Profit (إجمالي الربح)",
                        data: labels.map(() => totalProfit), // نفس القيمة لكل الفترات
                        borderColor: "#007bff", // أزرق
                        backgroundColor: "transparent",
                        type: "line",
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // ✅ 3. فلترة حسب التاريخ
        window.filterStatements = function() {
            let from = document.getElementById("fromDate").value;
            let to = document.getElementById("toDate").value;
            if (from && to) {
                window.location.href = `?from=${from}&to=${to}`;
            }
        }
    });
</script>


@endpush



