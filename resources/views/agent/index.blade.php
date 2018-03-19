@extends('layouts.app') @section('content')
    <div class="static-content">
        <div class="page-content">
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('home')}}">Home</a>
                </li>

                <li class="active">
                    <a href="{{url('/agentMg')}}">Agent Manage</a>
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
                                    <h2>Agent Information</h2>
                                    <div class="panel-ctrls"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-offset-1">
                                        {{--  <button id = "addAgentBtn" class="btn btn-lg btn-primary"><i class="ti ti-plus"></i></button>  --}}
                                        <button id = "editAgentBtn" class="btn btn-lg btn-success" disabled><i class="fa fa-edit"></i></button>
                                        <button id="ResetPassword" class="btn btn-lg btn-danger" disabled><i class="fa fa-key"></i></button>
                                        <button id="removeAdminBtn" class="btn btn-lg btn-inverse" disabled><i class="fa fa-trash"></i></button>
                                    </div>
                                    <table id="agentInfoTable" class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>AgentName</th>
                                            <th>Email</th>
                                            <th>phone number</th>
                                            <th>address</th>
                                            <th>post code</th>
                                            <th>state</th>
                                            <th>deviceName</th>
                                            <th>longitude</th>
                                            <th>latitude</th>
                                            <th>isVerified</th>
                                            <th>isAvailable</th>
                                            <th>Add Time</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- .container-fluid -->
        </div>
        <!-- #page-content -->
    </div>

    <div id="addAgentUser" data-iziModal-title="Add Agent Account"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="addAgentAccount" class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-3 control-label">AgentName</label>
                        <div class="col-md-9">
                            <input type="text" name="agentName" class="form-control">
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
                        <label class="col-md-3 control-label">phone number</label>
                        <div class="col-md-9">
                            <input type="text" name="phoneNum" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Address</label>
                        <div class="col-md-9">
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">AgentName</label>
                        <div class="col-md-9">
                            <input type="text" name="post_Code" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">agent state</label>
                        <div class="col-md-9">
                            <input type="text" name="state" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">country</label>
                        <div class="col-md-9">
                            <input type="text" name="country" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Agent status</label>
                        <div class="col-md-9">
                            <select name = "agent_status" class="form-control">
                                <option value="1">active</option>
                                <option value="2">disactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="addAgentSubmit">Submit</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="tmpAdminId" />

    <div id="editAgentUser" data-iziModal-title="Edit Agent Account"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="editAgentAccount" class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-3 control-label">AgentName</label>
                        <div class="col-md-9">
                            <input type="text" name="agentName" id="agentName" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Email</label>
                        <div class="col-md-9">
                            <input type="email" name="email"  id="email" onchange="Duplication(this.value)" id="newEmail" class="form-control">
                        </div>
                        {{ csrf_field() }}
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">phone number</label>
                        <div class="col-md-9">
                            <input type="text" name="phoneNum" id="phoneNum" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Address</label>
                        <div class="col-md-9">
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Post Code</label>
                        <div class="col-md-9">
                            <input type="text" name="post_Code" id="post_code" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">agent state</label>
                        <div class="col-md-9">
                            <input type="text" name="state" id="state" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">country</label>
                        <div class="col-md-9">
                            <input type="text" name="country" id="country" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Agent status</label>
                        <div class="col-md-9">
                            <select name = "agent_status" id="agent_status" class="form-control">
                                <option value="1">active</option>
                                <option value="0">disactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="editAgentSubmit">Submit</button>
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

    <form id="deleteForm">
        {{ csrf_field() }}
        <input type="hidden" name="adminUpdateID" id="adminDeleteId">
    </form>

@endsection @section('javascript')
    <script>
        let table = [];
        const strUrl = "{{url('/agentMg/getAgentTableInfo')}}";
        let addModel, editModal;
        $(document).ready(function () {
            addModel = $("#addAgentUser").iziModal();

            editModal = $('#editAgentUser').iziModal();

            table = $('#agentInfoTable').DataTable({
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

            $('#agentInfoTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected info');
                } else {
                    table.$('tr.selected').removeClass('selected info');
                    $(this).addClass('selected info');
                }
                let adminId = $(this).find('.agent_id').val();
                let getAdminUrl = "{{url('agentMg/getAgentDetailInfo/')}}" + '/' + adminId;
                $.ajax({
                    dataType: "json",
                    url: getAdminUrl,
                    success: function (response) {
                        selectAdmin(response);
                    }
                });
            });

            $('#addAgentBtn').click(function () {
                $('#addAgentAccount')[0].reset();
                addModel.iziModal('open');
            });

            $('#addAgentSubmit').click(function () {

                $('#addAgentAccount').submit();

            });

            $('#editAgentBtn').click(function () {
                editModal.iziModal('open');
            });

            $('#editAgentSubmit').click(function () {
                $('#editAgentAccount').submit();
            });

            $('#activeAdminBtn').click(function(){

                $('#activateStatus').val(1);

                $.confirm({
                    title: 'Alert',
                    content: 'Do you want to active this Account?',
                    icon: 'fa fa-warning',
                    theme: 'bootstrap',
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
                    theme: 'bootstrap',
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
                    theme: 'bootstrap',
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
                                    "url": "{{url('agentMg/removeAgent')}}",
                                    "dataType": "json",
                                    "data": formData,
                                    "method": "POST"
                                };
                                $.ajax(settings).done(function (response) {
                                    if (response.status) {
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
                    theme: 'bootstrap',
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
                    ' {{ csrf_field() }} <input type="hidden" name="agent_id" id="admin_idTmp"> </div>\n' +
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
                                $('#admin_idTmp').val($('#tmpAdminId').val());

                                let formData = $('#resetPasswordForm').serialize();
                                let strURL = '{{url('agentMg/resetPassword')}}';
                                $.ajax({
                                    dataType: "json",
                                    url: strURL,
                                    method: "POST",
                                    data: formData,
                                    success: function (response)
                                    {
                                      console.log(response);
                                      if(response.response_code){
                                        $.alert('Reset Password Successfully!');
                                        resetButton();

                                      }else{

                                      }
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

            $("#addAgentAccount").validate({
                // Specify validation rules
                rules: {
                    agentName: "required",
                    phoneNum: "required",
                    address: "required",
                    post_Code: "required",
                    country: "required",
                    state: "required",
                    agent_status: "required",
                    email: {
                        required: true,
                        email: true
                    }

                },
                // Specify validation error messages
                // in the "action" attribute of the form when valid
                submitHandler: function () {
                    addAgentSubmit();
                }
            });

            $("#editAgentAccount").validate({
                // Specify validation rules
                rules: {
                    agentName: "required",
                    phoneNum: "required",
                    address: "required",
                    post_Code: "required",
                    country: "required",
                    state: "required",
                    agent_status: "required",
                    email: {
                        required: true,
                        email: true
                    }
                },
                submitHandler: function () {
                    editAgentSubmit();
                }
            });

            $('.panel-info').css('opacity', '80');
        });

        function selectAdmin(params) {
            $('#tmpAdminId').val(params.id);
            $('#adminDeleteId').val(params.id);
            $('#admin_updateID').val(params.id);
            $('#agentName').val(params.username);
            $('#phoneNum').val(params.phoneno);
            $('#post_code').val(params.postcode);
            $('#address').val(params.address);
            $('#country').val(params.country);
            $('#email').val(params.email);
            $('#state').val(params.state);
            $('#agent_status').val(params.status).change();
            $("#editAgentBtn").removeAttr("disabled");
            $("#ResetPassword").removeAttr("disabled");
            $("#removeAdminBtn").removeAttr("disabled");
        }

        function addAgentSubmit(){
            let formData = $('#addAgentAccount').serialize();
            let settings = {
                "url": "{{url('agentMg/create')}}",
                "dataType": "json",
                "data": formData,
                "method": "POST"
            };
            $.ajax(settings).done(function (response) {
                if (response.status) {
                    $.alert({
                        title: 'Ok!',
                        content: 'Create successfully!',
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
                }
            });
        }

        function editAgentSubmit() {

            let formData = $('#editAgentAccount').serialize();
            let settings = {
                "url": "{{url('adminMg/update')}}",
                "dataType": "json",
                "data": formData,
                "method": "POST"
            };
            $.ajax(settings).done(function (response) {
                if (response.status) {
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
            alert(params);
            let sendData = {email : params};
            let settings = {
                "url": "{{url('adminMg/duplicationEmail')}}",
                "dataType": "json",
                "data": sendData,
                "method": "POST"
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

            $("#editAgentBtn").removeAttr("disabled");
            $("#activeAdminBtn").removeAttr("disabled");
            $("#stopAdminBtn").removeAttr("disabled");
            $("#ResetPassword").removeAttr("disabled");
            $("#removeAdminBtn").removeAttr("disabled");

        }

    </script>
@endsection
