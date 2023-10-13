@extends('layouts.admin')
@section('header', 'Sale')

@section('css')
<!-- plugins Data Table -->
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<style>
.modal-title-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.modal-title {
    margin: 0;
}
</style>
@endsection



@section('content')
<div id="controller" class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <div class="card-body table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th >No</th>
                                <th >Date</th>
                                <th >Member Name</th>
                                <th >Total Item</th>
                                <th >Total Price</th>
                                <th >Discont</th>
                                <th >Total Pay</th>
                                <th >Accepted</th>
                                <th >Casier</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                    </table>
            </div>
    </div>
@includeIf('admin.sale.detail')
@endsection

@section('js')
<!-- Plugins Data Table -->
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script type="text/javascript">
    var actionUrl = '{{url('sale')}}'
    var apiUrl = '{{url('api/sales')}}'

    console.log(actionUrl)

    var columns = [
        {data: 'DT_RowIndex', class: 'text-center', orderable: false},
        {data: 'created_at', class: 'text-center', orderable: true},
        {data: 'member_name', class: 'text-center', orderable: false},
        {data: 'total_item', class: 'text-center', orderable: false},
        {data: 'total_price', class: 'text-center', orderable: false},
        {data: 'discont', class: 'text-center', orderable: false},
        {data: 'pay', class: 'text-center', orderable: false},
        {data: 'accepted', class: 'text-center', orderable: false},
        {data: 'name', class: 'text-center', orderable: false},
        {data: 'action', class: 'text-center', orderable: false},
    ];
</script>
<script>
    let table, table1;

    $(function () {
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'product_code'},
                {data: 'product_name'},
                {data: 'sell_price'},
                {data: 'amount'},
                {data: 'subtotal'},
            ]
        })
    });
    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }
</script>
<script>
    var controller = new Vue ({
    el: '#controller',
    data: {
        datas: [],
        data: {},
        actionUrl,
        apiUrl,
        editStatus: false,
    },
    mounted: function () {
        this.datatable();
    },
    methods: {
        datatable(){
            const _this = this;
            _this.table = $('#datatable').DataTable({
                ajax: {
                    url: _this.apiUrl,
                    type: 'GET',
                },
                columns
            }).on('xhr', function (){
                _this.datas = _this.table.ajax.json().data;
            });
        },
            addData() {
                this.data = {};
                this.editStatus = false
                $('#modal-supplier').modal();
                // console.log('add data');
            },
            editData(event, row) {
                this.data = this.datas[row];
                // console.log(data)
                // this.data = data;
                this.editStatus = true
                $('#modal-default').modal();
            },
            deleteData(event, id) {
                // console.log(id)
                // this.actionUrl = '{{ url('authors') }}'+'/'+id;
                if (confirm("Are You Sure ?")) {
                    $(event.target).parents('tr').remove();
                    axios.post(this.actionUrl+'/'+id, {_method: 'DELETE'}).then(response => {
                        // location.reload();
                        alert('Data Has Been Remove');
                    });
                }
            },
            submitForm(event, id) {
                event.preventDefault();
                const _this = this;
                console.log(this.actionUrl);
                var actionUrl = ! this.editStatus ? this.actionUrl : this.actionUrl+'/'+id;
                axios.post(actionUrl, new FormData($(event.target)[0])).then(response =>{
                    $('#modal-default').modal('hide');
                    _this.table.ajax.reload();
                });
            },
    }
});
</script>
@endsection