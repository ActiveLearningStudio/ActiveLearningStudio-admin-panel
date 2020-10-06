<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">CSV BULK IMPORT</h3>
            </div>
            {{ Aire::open()->class('form-horizontal')->post()->id('bulk-users-form') }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        {{ Aire::file('import_file', 'CSV FILE')->id('import_file')->required() }}
                        <p class="text-center">
                            <strong>
                                <a href="{{api_v_url() . '/users/import/sample-file'}}" target="_blank">
                                    Download Users Import Sample File
                                </a>
                            </strong>
                        </p>
                        <p class="text-center report-wrap hide-div d-none">
                            <strong>
                                <a href="javascript:void(0)" id="import-report" onclick="$(this).hide()" class="btn btn-danger" target="_blank">
                                    Download Import Report
                                </a>
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{Aire::submit('Import Users')->addClass('btn btn-info')}}
            </div>
            {{ Aire::close() }}
        </div>
    </div>
</div>
<script>
    // form submit
    var url = api_url + api_v + "/admin/users/bulk/import";
    multiPartFormSubmission("#bulk-users-form", url, function (response) {
        $('#import_file').val(null);
        // if file URL is present then set URL and show errors messages
        if (response.report) {
            $("#import-report").attr("href", response.report).show();
            $(".report-wrap").removeClass("d-none");
            showErrors(response.errors);
        }
    });
</script>
