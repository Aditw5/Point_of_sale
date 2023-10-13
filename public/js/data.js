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
                $('#modal-default').modal();
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