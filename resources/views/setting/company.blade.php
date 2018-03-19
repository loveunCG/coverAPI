@extends('layouts.app') @section('content')
    <div class="static-content">
        <div class="page-content">
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('home')}}">Home</a>
                </li>
                <li class="active">
                    <a href="{{url('setting/Company')}}">Setting/ Company Info</a>
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
                                    <h2>Company Information</h2>
                                    <div class="panel-ctrls"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-offset-1">
                                        <button id = "addCompanyBtn" class="btn btn-lg btn-primary"><i class="ti ti-plus"></i></button>
                                        <button id = "editompanyBtn" class="btn btn-lg btn-success" disabled><i class="fa fa-edit"></i></button>
                                    </div>
                                    <table id="CompanyInfoTable" class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company Name</th>
                                            <th>Company Description</th>
                                            <th>Created Time</th>
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

    <div id="addCompanyInfo" data-iziModal-title="Add Company Data"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="addCompanyInfoForm" class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Company Name</label>
                        <div class="col-md-8">
                            <input type="text" name="company_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Company Comment</label>
                        <div class="col-md-8">
                            <input type="text" name="company_conment" class="form-control">
                        </div>
                        {{ csrf_field() }}
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="addCompanyInfoSubmit">Submit</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editCompany" data-iziModal-title="Edit Company Data"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="editCompanyForm" class="form-horizontal row-border">
                    <div class="form-group">
                        {{ csrf_field() }}
                        <label class="col-md-4 control-label" >Company Name</label>
                        <div class="col-md-8">
                            <input type="text" name="company_name" id="company_name" class="form-control">
                        </div>
                        <input type="hidden" name = "company_id" id="company_id">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Company Comment</label>
                        <div class="col-md-8">
                            <input type="text" name="company_conment" id="company_conment" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="editCompanySubmit">Submit</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection @section('javascript')
    <script>
        let table = [];
        const strUrl = "{{url('setting/getCompanyTableInfo')}}";
        let addModel, editModal;

        $(document).ready(function () {
            addModel = $("#addCompanyInfo").iziModal();
            editModal = $('#editCompany').iziModal();
            table = $('#CompanyInfoTable').DataTable({
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

            $('#CompanyInfoTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected info');
                } else {
                    table.$('tr.selected').removeClass('selected info');
                    $(this).addClass('selected info');
                }
                let company_id = $(this).find('.company_id').val();
                let getCompanyUri = "{{url('setting/getCompany/')}}" + '/' + company_id;
                $.ajax({
                    dataType: "json",
                    url: getCompanyUri,
                    success: function (response) {
                        selectCompany(response);
                    }
                });
            });

            $('#addCompanyBtn').click(function () {
                $('#addCompanyInfoForm')[0].reset();
                addModel.iziModal('open');
            });

            $('#addCompanyInfoSubmit').click(function () {

                $('#addCompanyInfoForm').submit();

            });

            $('#editompanyBtn').click(function () {
                editModal.iziModal('open');
            });

            $('#editCompanySubmit').click(function () {
                $('#editCompanyForm').submit();
            });

            $("#addCompanyInfoForm").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    company_name: "required",
                    company_conment:"required"
                },
                // Specify validation error messages
                messages: {

                },
                submitHandler: function () {
                    addCompanyInfoSubmit();
                }
            });

            $("#editCompanyForm").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    company_name: "required",
                    company_conment:"required"
                },
                // Specify validation error messages
                messages: {

                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function () {
                    editCompanySubmit();
                }
            });

            $('.panel-info').css('opacity', '80')
        });

        function selectCompany(params) {
            $('#editCompanyForm')[0].reset();
            $('#company_id').val(params.id);
            $('#company_name').val(params.company_name);
            $('#company_conment').val(params.company_conment);
            $('#insurance_id').val(params.insurance_id);
            $('#editompanyBtn').removeAttr('disabled');
        }

        function addCompanyInfoSubmit(){
            let formData = $('#addCompanyInfoForm').serialize();
            let settings = {
                "url": "{{url('setting/addCompanyInfo')}}",
                "dataType": "json",
                "data": formData,
                "method": "POST"
            };
            $.ajax(settings).done(function (response) {
                if (response.response_code) {
                    $.alert({
                        title: 'Ok!',
                        content: response.message,
                        columnClass: 'small',
                        buttons: {
                            ok: function () {
                                addModel.iziModal('close');
                                table.ajax.reload();
                                return true;
                            }
                        }
                    });
                }else{

                  $.alert({
                      title: 'warning!',
                      content: response.message,
                      columnClass: 'small',
                      buttons: {
                          ok: function () {
                              addModel.iziModal('close');
                              table.ajax.reload();
                              return true;
                          }
                      }
                  });

                }
            });
        }

        function editCompanySubmit() {

            let formData = $('#editCompanyForm').serialize();

            let settings = {
                "url": "{{url('setting/addCompanyInfo')}}",
                "dataType": "json",
                "data": formData,
                "method": "POST"
            };
            $.ajax(settings).done(function (response) {
              if (response.response_code) {
                  $.alert({
                      title: 'Ok!',
                      content: response.message,
                      columnClass: 'small',
                      buttons: {
                          ok: function () {
                              editModal.iziModal('close');
                              table.ajax.reload();
                              return true;
                          }
                      }
                  });
              }else{

                $.alert({
                    title: 'warning!',
                    content: response.message,
                    columnClass: 'small',
                    buttons: {
                        ok: function () {
                            editModal.iziModal('close');
                            table.ajax.reload();
                            return true;
                        }
                    }
                });

              }
            });


        }

    </script>
@endsection
