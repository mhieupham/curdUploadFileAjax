@extends('layout.app')
@section('content')
    <h3>Index</h3>
    <br>
    <span id="output-cms"></span>
    <br>
    <button type="button" data-id="add" id="form-add-student" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Add
    </button>
    <table class="table" id="table-student">
        <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
        </thead>
        <tbody id="getajax">


        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul id="pageAjax" class="pagination">
        </ul>
    </nav>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="form-result"></span>
                    <form id="form-input-student" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" id="first_name" class="form-control" name="first_name">
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" id="last_name" class="form-control" name="last_name">
                        </div>
                        <div class="form-group">
                            <label>Add Image</label>
                            <input id="image_name" type="file" name="image">
                        </div>
                        <span id="student-image"></span>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id">
                    <input type="hidden" id="form-set-action" name="button_action" value="insert">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" id="form-submit-student" class="btn btn-primary" value="Add">
                </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
@section('myJquery')
    <script>
        $(document).ready(function () {
            var page =1;
            $(function () {
                getAjaxIndex(page);
            });
            function getAjaxIndex(page) {

                html ='';
                pageHtml ='';
                $.ajax({
                    url:'http://localhost/new%20code/curd-with-upload-ajax/public/getcurd?page='+page,
                    type:'get',
                    success:function (data) {
                        $.each(data['data'],function (index,value) {
                            html+='<tr>\n' +
                                '<th scope="row"><img style="width:50px;" src="{{\URL::to('images')}}/'+value.image+'" class=""></th>' +
                                '<td>'+value.first_name+'</td>' +
                                '<td>'+value.last_name+'</td>' +
                                '<td>' +
                                '<a href="#" class="btn btn-primary form-edit-student" data-id="'+value.id+'">Edit</a>' +
                                '<a href="#" class="btn btn-danger form-delete-student" data-id="'+value.id+'">Delete</a>'+
                                '</td>' +
                                '</tr>';
                        });
                        if(data['last_page']>1){
                            for(var i =1;i<=data['last_page'];i++){
                                pageHtml +='<li class="page-item"><a class="page-link" href="">'+i+'</a></li>';
                            }
                        }

                        $('#pageAjax').html(pageHtml);
                        $('#getajax').html(html);
                    }
                })
            }
            function getfetchdata(id) {
                $.ajax({
                    url:'{{route('getfetchdata')}}',
                    data:{id:id},
                    dataType:'json',
                    success:function (value) {
                        console.log(value);
                        $('#first_name').val(value['first_name']);
                        $('#last_name').val(value['last_name']);
                        $('#student-image').html('<img style="width:100px" src="{{\URL::to('images')}}/'+value['image']+'">');
                    }
                })
            }
            function insertdata(formdata) {
                $.ajax({
                    url:'{{route('insertdata')}}',
                    data:formdata,
                    dataType: 'json',
                    type:'post',
                    processData: false,
                    contentType: false,
                    success:function (data) {
                        html ='';
                        if(data.success != ''){
                            $('#form-result').html(data.success);
                        }else if(data.error.length >0){
                            for(var i=0;i<data.error.length;i++){
                                html+='<div class="alert alert-danger">'+data.error[i]+'</div>';
                            }
                            $('#form-result').html(html);
                        }
                        console.log(data.error);
                        getAjaxIndex(page);
                    }
                })
            }
            function deletedata(id){
                $.ajax({
                    url:'{{route('destroydata')}}',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id:id
                    },
                    dataType:'json',
                    method:'post',
                    success:function (data) {
                        console.log(data);
                        getAjaxIndex(page);
                    }
                })
            }
            // getAjaxIndex(page);
            $('form').on('submit',function (e) {
                e.preventDefault();
                var formdata = new FormData($(this)[0]);
                insertdata(formdata);
            });
            $(document).on('click','.pagination a',function (e) {
                e.preventDefault();
                page = $(this).text();
                getAjaxIndex(page);
            });
            $('#form-add-student').on('click',function () {
                $('.modal-title').html('Add Student');
                $('#form-submit-student').val('Add');
                $('#form-set-action').val('insert');
                $('#first_name').val('');
                $('#last_name').val('');
                $('#student-image').html('');
                $('#student_id').val('');
                $('#form-result').html('');
            });
            $('#table-student').on('click','.form-edit-student',function (e) {
                e.preventDefault();
                id = $(this).data('id');
                $('.modal').modal('show');
                $('#student_id').val(id);
                $('.modal-title').html('Edit Student');
                $('#form-submit-student').val('Edit');
                $('#form-set-action').val('edit');
                $('#form-result').html('');
                getfetchdata(id);
            });
            $('#table-student').on('click','.form-delete-student',function (e) {
                e.preventDefault();
                id = $(this).data('id');
                deletedata(id);

            })
        })

    </script>


    @endsection
