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
                                    <button id="job_detail_view_btn" class="btn btn-lg btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button id="quotation_view_btn" class="btn btn-lg btn-primary">
                                        <i class="ti ti-comment-alt"></i>
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

<div id="jobDetailViewer" data-iziModal-title="View job detail" data-iziModal-icon="icon-home">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-ctrls" data-actions-container=""></div>
        </div>
        <div class="panel-body">
            <table class="table browsers m-n">
                <tbody id='job_detail_view_html'>

                </tbody>
            </table>
        </div>
        <div class="panel-footer">
        </div>
    </div>
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
@endsection @section('javascript')
<script>
    let table, quotation_table;
    const tableUrl = "{{url('jobs/getJobListData')}}";
    const quotation_url = '{{url("jobs/getQuotationList")}}'
    let viewModal, quotationModal;
    let quotationHtml, jobDetailViewHtml;
    $(document).ready(function () {
        viewModal = $("#jobDetailViewer").iziModal();
        quotationModal = $('#quotationViewer').iziModal();
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

        $('#quotation_view_btn').click(function () {
            quotationModal.iziModal('open');
            console.log('here is open izi modal')
        });

        $('.panel-info').css('opacity', '80')
    });

    function seleteJob(params) {
        var job_id = params.id;
        updateQuotationsList_url = quotation_url + '?job_id=' + job_id;
        quotation_table.ajax.url(updateQuotationsList_url).load();
    }
</script>
@endsection
