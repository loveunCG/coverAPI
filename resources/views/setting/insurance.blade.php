@extends('layouts.app') @section('content')
    <div class="static-content">
        <div class="page-content">
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('home')}}">Home</a>
                </li>
                <li class="active">
                    <a href="{{url('setting/insurance')}}">Setting/ Add Insurance</a>
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
                                    <h2>Insurance Information</h2>
                                    <div class="panel-ctrls"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-offset-1">
                                        <button id = "addInsuranceBtn" class="btn btn-lg btn-primary"><i class="ti ti-plus"></i></button>
                                        <button id = "editInsuranceBtn" class="btn btn-lg btn-success" disabled><i class="fa fa-edit"></i></button>
                                    </div>
                                    <table id="InsuranceInfoTable" class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Insurance Name</th>
                                            <th>Insurance Comment</th>
                                            <th>Add Time</th>
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

    <div id="addInsurance" data-iziModal-title="Add Insurance Data"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="addInsuranceForm" class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Insurance Name</label>
                        <div class="col-md-8">
                            <input type="text" name="insuranceName" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Insurance Comment</label>
                        <div class="col-md-8">
                            <input type="text" name="insuranceComment" class="form-control">
                        </div>
                        {{ csrf_field() }}
                    </div>

                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="addInsuranceSubmit">Submit</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editInsurance" data-iziModal-title="Edit Insurance Data"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="editInsuranceForm" class="form-horizontal row-border">
                    <div class="form-group">
                        {{ csrf_field() }}
                        <label class="col-md-4 control-label" >Insurance Name</label>
                        <div class="col-md-8">
                            <input type="text" name="insuranceName" id="insuranceName" class="form-control">
                        </div>
                        <input type="hidden" name = "insurance_id" id="insurance_id">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">insurance Comment</label>
                        <div class="col-md-8">
                            <input type="text" name="insuranceComment" id="insuranceComment" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <button class="btn-primary btn" type="button" id="editInsuranceSubmit">Submit</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection @section('javascript')
    <script>
        let table = [];
        const strUrl = "{{url('setting/getInsuranceTableInfo')}}";
        let addModel, editModal;

        $(document).ready(function () {
            addModel = $("#addInsurance").iziModal();
            editModal = $('#editInsurance').iziModal();
            table = $('#InsuranceInfoTable').DataTable({
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

            $('#InsuranceInfoTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected info');
                } else {
                    table.$('tr.selected').removeClass('selected info');
                    $(this).addClass('selected info');
                }
                let insurance_id = $(this).find('.insurance_id').val();
                let getInsuranceUri = "{{url('setting/getInsurance/')}}" + '/' + insurance_id;
                $.ajax({
                    dataType: "json",
                    url: getInsuranceUri,
                    success: function (response) {
                        selectInsurance(response);
                    }
                });
            });

            $('#addInsuranceBtn').click(function () {
                $('#addInsuranceForm')[0].reset();
                addModel.iziModal('open');
            });

            $('#addInsuranceSubmit').click(function () {

                $('#addInsuranceForm').submit();

            });

            $('#editInsuranceBtn').click(function () {
                editModal.iziModal('open');
            });

            $('#editInsuranceSubmit').click(function () {
                $('#editInsuranceForm').submit();
            });

            $("#addInsuranceForm").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    insuranceName: "required",
                    insuranceComment:"required"
                },
                // Specify validation error messages
                messages: {
                    insuranceName: "Please enter name",
                    insuranceComment: "Please enter Comment"

                },
                submitHandler: function () {
                    addInsuranceSubmit();
                }
            });

            $("#editInsuranceForm").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    insuranceName: "required",
                    insuranceComment:"required"
                },
                // Specify validation error messages
                messages: {
                    insuranceName: "Please enter name",
                    insuranceComment: "Please enter Comment"

                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function () {
                    editInsuranceSubmit();
                }
            });

            $('.panel-info').css('opacity', '80')
        });

        function selectInsurance(params) {
            $('#editInsuranceForm')[0].reset();
            $('#insurance_id').val(params.id);
            $('#insuranceName').val(params.insurance_name);
            $('#insuranceComment').val(params.insur_comment);
            $('#editInsuranceBtn').removeAttr('disabled');
        }

        function addInsuranceSubmit(){
            let formData = $('#addInsuranceForm').serialize();
            let settings = {
                "url": "{{url('setting/addInsurance')}}",
                "dataType": "json",
                "data": formData,
                "method": "POST"
            };
            $.ajax(settings).done(function (response) {
                if (response.status) {
                    $.alert({
                        title: 'Ok!',
                        content: 'Add successfully!',
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

        function editInsuranceSubmit() {

            let formData = $('#editInsuranceForm').serialize();

            let settings = {
                "url": "{{url('setting/addInsurance')}}",
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
                                return true;
                            }
                        }
                    });
                }
            });


        }

    </script>
@endsection
