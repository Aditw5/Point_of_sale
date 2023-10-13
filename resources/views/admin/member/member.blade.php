@extends('layouts.admin')
@section('header', 'Member')

@section('css')
<!-- plugins Data Table -->
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
<div id="controller" class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sg-1">
                        <a href="#" @click="addData()" class="btn btn-primary pull-right">Create New Member</a>
                    </div>
                    <div class="col-sg-4">
                        <a onclick="deleteSelected('{{ route('members.delete_selected') }}')"  class="btn btn-danger pull-right">Delete Selected</a>
                    </div>
                    <div class="col-4">
                        <a onclick="printCard('{{ route('members.print_card') }}')"  class="btn btn-secondary pull-right">Print Card</a>
                    </div>
                </div>
            </div>

                <div class="card-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                                <th>
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No</th>
                                <th >Member Code</th>
                                <th >Member Name</th>
                                <th >Address</th>
                                <th >Phone</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
    </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form :action="actionUrl" method="post" autocomplete="off" @submit="submitForm($event, data.id)">
                        <div class="modal-header">
                            <h4 class="modal-title">Member</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="_method" value="PUT" v-if="editStatus">
                            <div class="form-group">
                                <label>Member Name</label>
                                <input name="member_name" :value="data.member_name" type="text" required="" class="form-control" placeholder="Enter Member Name">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address" :value="data.address" rows="3" placeholder="Enter Address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input name="phone" :value="data.phone" type="text" required="" class="form-control" placeholder="Enter Phone">
                            </div>
                            
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
</div>
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
    var actionUrl = '{{url('members')}}'
    var apiUrl = '{{url('api/members')}}'

    console.log(actionUrl)

    var columns = [
        {data: 'select_all', searchable: false, sortable: false},
        {data: 'DT_RowIndex', class: 'text-center', orderable: true},
        {data: 'member_code', class: 'text-center', orderable: true},
        {data: 'member_name', class: 'text-center', orderable: true},
        {data: 'address', class: 'text-center', orderable: true},
        {data: 'phone', class: 'text-center', orderable: true},
        {render: function (data, index, row, meta) {
            return `
                <a class="btn btn-warning" onclick="controller.editData(event, ${meta.row})" href="#">
                    Edit
                </a>
                <a class="btn btn-danger" onclick="controller.deleteData(event, ${row.id})" href="#">
                    Delete
                </a>`;
        }, orderable: false, width: '200px', class: 'text-center'},
    ];
</script>
<script>
     $(document).ready(function () {
        $('#select_all').on('change', function () {
            $(':checkbox').prop('checked', $(this).is(':checked'));
        });
    });
    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-member').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }
    function printCard(url) {
        if ($('input:checked').length < 1) {
            alert('Pilih data yang akan dicetak');
            return;
        } else {
            $('.form-member')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
</script>
<script src="{{ asset('js/data.js') }}"></script>
@endsection