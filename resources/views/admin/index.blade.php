@extends('layouts.app') @section('content')
<div class="static-content">
    <div class="page-content">
        <ol class="breadcrumb">
            <li>
                <a href="{{route('home')}}">Home</a>
            </li>

            <li class="active">
                <a href="{{url('/adminMg')}}">Admin Manage</a>
            </li>

        </ol>
        <div class="container-fluid">
            <div data-widget-group="group1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-inverse" data-widget='{"id" : "wiget2"}'>
                            <div class="panel-heading">
                                <div class="panel-ctrls button-icon" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'
                                    data-action-colorpicker='' data-action-close=''>
                                </div>
                                <h2>Admin Information</h2>
                                <div class="panel-ctrls"></div>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-offset-1">
                                    <button id = "addAdminBtn" class="btn btn-lg btn-primary"><i class="ti ti-plus"></i></button>
                                    <button id = "editAdminBtn" class="btn btn-lg btn-success" disabled><i class="fa fa-edit"></i></button>
                                    <button id="activeAdminBtn" class="btn btn-lg btn-indigo" disabled><i class="ti ti-control-play"></i></button>
                                    <button id="stopAdminBtn" class="btn btn-lg btn-danger" disabled><i class="ti ti-control-stop"></i></button>
                                    <button id="ResetPassword" class="btn btn-lg btn-danger" disabled><i class="fa fa-key"></i></button>
                                    <button id="removeAdminBtn" class="btn btn-lg btn-inverse" disabled><i class="fa fa-trash"></i></button>
                                </div>
                                <table id="adminInfoTable" class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>UserName</th>
                                            <th>Email</th>
                                            <th>Admin Role</th>
                                            <th>Add Time</th>
                                            <th>Admin Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="panel-footer"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- .container-fluid -->
    </div>
    <!-- #page-content -->
</div>

<div id="addAdminUser" data-iziModal-title="Add Admin Account"  data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <form id="addAdminAccount" class="form-horizontal row-border">
                <div class="form-group">
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" name="email"  onchange="Duplication(this.value)" id="newEmail" class="form-control">
                    </div>
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Password</label>
                    <div class="col-md-9">
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Admin Role</label>
                    <div class="col-md-9">
                        <select name = "admin_level" class="form-control">
                            <option value="1">super admin</option>
                            <option value="2">job manager</option>
                            <option value="3">user manager</option>
                        </select>
                    </div>
                </div>

            </form>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <button class="btn-primary btn" type="button" id="addAdminSubmit">Submit</button>
                    <button class="btn-default btn" data-izimodal-close="">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editAdminUser" data-iziModal-title="Edit Admin Account"  data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <form id="editAdminAccount" class="form-horizontal row-border">
                <div class="form-group">
                    {{ csrf_field() }}
                    <label class="col-md-3 control-label" >Name</label>
                    <div class="col-md-9">
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <input type="hidden" name = "admin_id" id="tmpAdminId">
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" name="email" disabled id="email" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Admin Role</label>
                    <div class="col-md-9">
                        <select name = "admin_level" id="admin_level" class="form-control">
                            <option value="1">Super admin</option>
                            <option value="2">job manager</option>
                            <option value="3">user manager</option>
                        </select>
                    </div>
                </div>

            </form>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <button class="btn-primary btn" type="button" id="editAdminSubmit">Submit</button>
                    <button class="btn-default btn" data-izimodal-close="">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="activateForm">
    {{ csrf_field() }}
    <input type="hidden" name="status" id="activateStatus">
    <input type="hidden" name="adminUpdateID" id="admin_updateID">
</form>



@endsection @section('javascript')
<script>
    let table = [];
    const strUrl = "{{url('/adminMg/getAdminTableInfo')}}";
    let addModel, editModal;
    $(document).ready(function () {
        addModel = $("#addAdminUser").iziModal();

        editModal = $('#editAdminUser').iziModal();

        table = $('#adminInfoTable').DataTable({
            "ajax": strUrl,
            bStateSave: !0,
            columnDefs: [{
                orderable: !1,
                targets: [0]
            }, {
                searchable: !0,
                targets: [0]
            }],
            order: [
                [0, "asc"]
            ]
        });

        $('#adminInfoTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected info');
            } else {
                table.$('tr.selected').removeClass('selected info');
                $(this).addClass('selected info');
            }
            let adminId = $(this).find('.adminId').val();
            let getAdminUrl = "{{url('adminMg/getAdminInfo/')}}" + '/' + adminId;
            $.ajax({
                dataType: "json",
                url: getAdminUrl,
                success: function (response) {
                    selectAdmin(response);
                }
            });
        });

        $('#addAdminBtn').click(function () {
            $('#addAdminAccount')[0].reset();
            addModel.iziModal('open');
        });

        $('#addAdminSubmit').click(function () {

            $('#addAdminAccount').submit();

        });

        $('#editAdminBtn').click(function () {
            editModal.iziModal('open');
        });

        $('#editAdminSubmit').click(function () {
            $('#editAdminAccount').submit();
        });

        $('#activeAdminBtn').click(function(){

            $('#activateStatus').val(1);

            $.confirm({
                title: 'Alert',
                content: 'Do you want to active this Account?',
                icon: 'fa fa-warning',
                theme: 'material',
                autoClose: 'chancel|10000',
                animation: 'zoom',
                closeAnimation: 'scale',
                draggable: true,
                buttons: {
                    confirm: {
                        text: 'ok',
                        keys: ['shift', 'alt'],
                        action: function () {
                            activate();
                        }
                    },
                    chancel: {
                        text: 'cancel',
                        action: function () {
                            $(this).remove();
                        }

                    }
                }
        });

        });

        $('#stopAdminBtn').click(function(){

            $('#activateStatus').val(0);

            $.confirm({
                title: 'Alert',
                content: 'Do you want to Stop this Account?',
                icon: 'fa fa-warning',
                theme: 'material',
                autoClose: 'chancel|10000',
                animation: 'zoom',
                closeAnimation: 'scale',
                draggable: true,
                buttons: {
                    confirm: {
                        text: 'ok',
                        keys: ['shift', 'alt'],
                        action: function () {
                            activate();
                        }
                    },
                    chancel: {
                        text: 'cancel',
                        action: function () {
                            $(this).remove();
                        }

                    }
                }
            });

        });

        $('#removeAdminBtn').click(function(){

            $.confirm({
                title: 'Warring',
                content: 'Do you want this Account？',
                theme: 'material',
                columnClass: 'small',
                autoClose: 'chancel|10000',
                closeIcon: true,
                typeAnimated: true,
                draggable: true,
                icon: 'fa fa-trash',
                buttons: {
                    formSubmit: {
                        text: 'ok',
                        btnClass: 'btn-blue',
                        action: function () {

                            let formData = $('#deleteForm').serialize();
                            let settings = {
                                "url": "{{url('adminMg/removeAdmin')}}",
                                "dataType": "json",
                                "data": formData,
                                "method": "POST"
                            };
                            $.ajax(settings).done(function (response) {
                                if (response.status == 'success') {
                                    $.alert({
                                        title: 'Ok!',
                                        content: 'Update successfully!',
                                        columnClass: 'small',
                                        buttons: {
                                            ok: function () {
                                                editModal.iziModal('close');
                                                table.ajax.reload();
                                                resetButton();
                                                return true;
                                            }
                                        }
                                    });
                                }
                            });



                        }

                    },
                    chancel: {
                        text: 'chancel',
                        btnClass: 'btn-info',
                        action: function () {

                        }
                    }

                }
            });

        });

        $('#ResetPassword').click(function(){
            $.confirm({
                title: 'Reset Password',
                icon: 'fa fa-edit',
                theme: 'material',
                columnClass: 'col-md-4 col-md-offset-4',
                content: '' +
                '<form id = "resetPasswordForm">\n' +
                '    <div class="col-sm-12">\n' +
                '     <label class="form-label" for="password">Password</label>\n' +
                '        <div class="form-group form-float">\n' +
                '            <div class="form-line">\n' +
                '                <input type="password" name="password" class="form-control">\n' +
                '            </div>\n' +
                '        </div>\n' +
                ' {{ csrf_field() }} <input type="hidden" name="admin_id" id="admin_idTmp"> </div>\n' +
                '</form>',
                onContentReady: function () {
                    $('#admin_idTmp').val($('#tmpAdminId').val());
                    $('#resetPasswordForm').validate({
                        rules: {
                            'password': {
                                required: !0,
                                minlength: 6
                            }
                        },
                        highlight: function (input) {
                            $(input).parents('.form-line').addClass('error');
                        },
                        unhighlight: function (input) {
                            $(input).parents('.form-line').removeClass('error');
                        },
                        errorPlacement: function (error, element) {
                            $(element).parents('.form-group').append(error);
                        },
                        success: function (e) {
                            e.closest(".form-group").removeClass("has-error")
                        },
                        submitHandler: function (e) {

                        }
                    });
                },
                buttons: {
                    formSubmit: {
                        text: 'Reset',
                        btnClass: 'btn-info',
                        action: function () {
                            if($('#updatePassword').val()=='') {
                                $.alert('Please insert Password');
                                return true;
                            }
                            let formData = $('#resetPasswordForm').serialize();
                            let strURL = '{{url('/adminMg/resetPassword')}}';
                            $.ajax({
                                dataType: "json",
                                url: strURL,
                                type: 'POST',
                                data: formData,
                                success: function () {
                                    $.alert('Reset Password Successfully!');
                                    resetButton();
                                }
                            });


                        }
                    },
                    Chancel: {
                        text: 'Chancel',
                        btnClass: 'btn-warning',
                        action: function () {

                        }
                    }
                }
            });

        });

        $("#addAdminAccount").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                name: "required",
                admin_level:"required",
                email: {
                    required: true,
                    // Specify that email should be validated
                    // by the built-in "email" rule
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            // Specify validation error messages
            messages: {
                name: "Please enter account name",
                admin_level: "Please enter level",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                email: "Please enter a valid email address"
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (form) {
                addAdminSubmit();
            }
        });

        $("#editAdminAccount").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                name: "required",
                admin_level:"required",
                email: {
                    required: true,
                    // Specify that email should be validated
                    // by the built-in "email" rule
                    email: true
                }
            },
            // Specify validation error messages
            messages: {
                name: "Please enter account name",
                admin_level: "Please enter level",
                email: "Please enter a valid email address"
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function () {
                editAdminSubmit();
            }
        });

        $('.panel-info').css('opacity', '80')
    });

    function selectAdmin(params) {
        $('#tmpAdminId').val(params.id);
        $('#adminDeleteId').val(params.id);
        $('#admin_updateID').val(params.id);
        $('#name').val(params.name);
        $('#email').val(params.email);
        $('#admin_level').val(params.admin_level).change();
        $("#editAdminBtn").removeAttr("disabled");
        $("#activeAdminBtn").removeAttr("disabled");
        $("#stopAdminBtn").removeAttr("disabled");
        $("#ResetPassword").removeAttr("disabled");
        $("#removeAdminBtn").removeAttr("disabled");
        if(params.usr_status ==1){
            $("#stopAdminBtn").removeAttr("disabled");
            $("#activeAdminBtn").attr("disabled", true);
        } else {
            $("#activeAdminBtn").removeAttr("disabled");
            $("#stopAdminBtn").attr("disabled", true);
        }
    }

    function addAdminSubmit(){
        let formData = $('#addAdminAccount').serialize();
        let settings = {
            "url": "{{url('adminMg/create')}}",
            "dataType": "json",
            "data": formData,
            "method": "POST"
        };
        $.ajax(settings).done(function (response) {
            console.log(response);

                $.alert({
                    title: 'Ok!',
                    content: response.message,
                    columnClass: 'small',
                    buttons: {
                        ok: function () {
                            addModel.iziModal('close');
                            table.ajax.reload();
                            resetButton();
                            return true;
                        }
                    }
                });

        });
    }

    function editAdminSubmit() {

        let formData = $('#editAdminAccount').serialize();
        let settings = {
            "url": "{{url('adminMg/update')}}",
            "dataType": "json",
            "data": formData,
            "method": "POST"
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $.alert({
                    title: 'Ok!',
                    content: 'Update successfully!',
                    columnClass: 'small',
                    buttons: {
                        ok: function () {
                            editModal.iziModal('close');
                            table.ajax.reload();
                            resetButton();
                            return true;
                        }
                    }
                });
            }
        });


    }

    function activate(){
        let sendData = $('#activateForm').serialize();
        let settings = {
            "url": "{{url('adminMg/activate')}}",
            "dataType": "json",
            "data": sendData,
            "method": "POST"
        };

        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $.alert({
                    title: 'Ok!',
                    content: 'Update successfully!',
                    columnClass: 'small',
                    buttons: {
                        ok: function () {
                            editModal.iziModal('close');
                            table.ajax.reload();
                            resetButton();
                            return true;
                        }
                    }
                });
            }
        });

    }

    function Duplication(params) {
        let sendData = {email : params};
        let settings = {
            "url": "{{url('adminMg/duplicationEmail')}}",
            "dataType": "json",
            "data": sendData,
            "method": "get"
        };

        $.ajax(settings).done(function (response) {
            if (response.status) {
                $.alert({
                    title: 'Ok!',
                    content: 'Email is duplicated!',
                    columnClass: 'small',
                    buttons: {
                        ok: function () {
                            $('#newEmail').val('');
                            return true;
                        }
                    }
                });
            }else{

            }
        });


    }

    function resetButton(){

        $("#editAdminBtn").attr("disabled", "true");
        $("#activeAdminBtn").attr("disabled", "true");
        $("#stopAdminBtn").attr("disabled", "true");
        $("#ResetPassword").attr("disabled", "true");
        $("#removeAdminBtn").attr("disabled", "true");

    }

</script>
@endsection
