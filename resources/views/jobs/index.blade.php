@extends('layouts.app') @section('content')
<div class="static-content">
    <div class="page-content">
        <ol class="breadcrumb">
            <li>
                <a href="{{route('home')}}">Home</a>
            </li>
            <li class="active">
                <a href="{{url('jobs')}}">Job Manage/ Job List</a>
            </li>
        </ol>
        <div class="container-fluid">
            <div data-widget-group="group1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-inverse" data-widget='{"id" : "wiget2"}'>
                            <div class="panel-heading">
                                <div class="panel-ctrls button-icon" data-actions-container="" data-action-collapse='{"target": ".panel-body"}' data-action-colorpicker=''
                                    data-action-close=''>
                                </div>
                                <h2>Jobs List</h2>
                                <div class="panel-ctrls"></div>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-offset-1">
                                    <button id="job_detail_view_btn" disabled class="btn btn-lg btn-primary">
                                        <i class="fa fa-file-text"></i>
                                    </button>

                                    <button id="assigned_agent_list_btn" disabled class="btn btn-lg btn-primary">
                                        <i class="ti ti-user"></i>
                                    </button>
                                    <button id="quotation_view_btn" disabled class="btn btn-lg btn-primary">
                                        <i class="fa  fa-quote-left"></i>
                                    </button>
                                    <button id="delete_job_btn" class="btn btn-lg btn-success" disabled>
                                        <i class="fa  fa-trash-o"></i>
                                    </button>
                                </div>
                                <table id="job_list_talbe" class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jobs Name</th>
                                            <th>Nric</th>
                                            <th>insurance type</th>
                                            <th>Indicative Sum</th>
                                            <th>Address</th>
                                            <th>State</th>
                                            <th>POST Code</th>
                                            <th>Expired Time </th>
                                            <th>Create User Name</th>
                                            <th>Job Status</th>
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

<div id="quotationViewer" data-iziModal-title="View Quotation List" data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <table id="quotations_table" class="table browsers m-n">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Agent Name</th>
                        <th>price</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="documentViewer" data-iziModal-title="Job Document List" data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <table id="document_table" class="table browsers m-n">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User Name</th>
                        <th>Document link</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="assignedJobViewer" data-iziModal-title="View Quotation List" data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <table id="assignedJob_table" class="table browsers m-n">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Agent Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<form id="deleteForm">
    {{ csrf_field() }}
    <input type="hidden" name="job_id" id="tmp_job_id">
</form>

@endsection @section('javascript')
<script>
    let table, quotation_table, document_table, assignedJob_table;
    const tableUrl = "{{url('jobs/getJobListData')}}";
    const quotation_url = '{{url("jobs/getQuotationList")}}';
    const document_url = '{{url("jobs/getDocumentList")}}';
    const assignedJob_url = '{{url("jobs/getAssignedJobList")}}';

    let viewModal, quotationModal, assignedJobModal;
    let quotationHtml, jobDetailViewHtml;
    $(document).ready(function () {

        viewModal = $("#documentViewer").iziModal();

        quotationModal = $('#quotationViewer').iziModal();

        assignedJobModal = $('#assignedJobViewer').iziModal();

        table = $('#job_list_talbe').DataTable({
            "ajax": tableUrl,
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

        quotation_table = $('#quotations_table').DataTable({
            "ajax": quotation_url,
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

        document_table = $('#document_table').DataTable({
            "ajax": document_url,
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

        assignedJob_table = $('#assignedJob_table').DataTable({
            "ajax": assignedJob_url,
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


        $('#job_list_talbe tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected info');
            } else {
                table.$('tr.selected').removeClass('selected info');
                $(this).addClass('selected info');
            }
            let job_id = $(this).find('.job_id').val();
            let getDetailJobInfoUrl = "{{url('jobs/getjobdetail/')}}";
            $.ajax({
                dataType: "json",
                url: getDetailJobInfoUrl,
                data: {
                    job_id: job_id
                },
                success: function (response) {
                    if (response.response_code) {
                        seleteJob(response.data);
                    } else {

                    }
                }
            });
        });

        $('#job_detail_view_btn').click(function () {
            viewModal.iziModal('open');
        });

        $('#assigned_agent_list_btn').click(function () {
            assignedJobModal.iziModal('open');
        });

        $('#quotation_view_btn').click(function () {
            quotationModal.iziModal('open');
            console.log('here is open izi modal')
        });

        $('.panel-info').css('opacity', '80');
        $('#delete_job_btn').click(function(){
          $.confirm({
              title: 'Warring!',
              content: 'Do you want to remove this Job？',
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
                          console.log(formData)
                          let settings = {
                              "url": "{{url('jobs/removeJob')}}",
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
        })

    });

    function resetButton(){
      $('#assigned_agent_list_btn').attr('disabled','true');
      $('#job_detail_view_btn').attr('disabled','true');
      $('#quotation_view_btn').attr('disabled', 'true');
      $('#delete_job_btn').attr('disabled', 'true');
    }

    function seleteJob(params) {
        var job_id = params.id;
        $('#tmp_job_id').val(job_id);
        $('#assigned_agent_list_btn').removeAttr('disabled');
        $('#job_detail_view_btn').removeAttr('disabled');
        $('#quotation_view_btn').removeAttr('disabled');
        $('#delete_job_btn').removeAttr('disabled');
        updateQuotationsList_url = quotation_url + '?job_id=' + job_id;
        updateDocumentList_url = document_url + '?job_id=' + job_id;
        updateAssignedJobList_url = assignedJob_url + '?job_id=' + job_id;
        quotation_table.ajax.url(updateQuotationsList_url).load();
        document_table.ajax.url(updateDocumentList_url).load();
        assignedJob_table.ajax.url(updateAssignedJobList_url).load();
    }
</script>
@endsection
