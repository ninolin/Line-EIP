<!doctype html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EIP</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/solid.css" integrity="sha384-+0VIRx+yz1WBcCTXBkVQYIBVNEFH1eP6Zknm16roZCyeNg2maWEpk/l/KsyFKs7G" crossorigin="anonymous">
        <link href="{{ asset('js/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="text-center">
            <div class="main-section">
                <div>
                    <table class="table table-bordered table-striped">
                        <thead class="table-thead">
                            <tr>
                                <th scope="col">申</th>
                                <th scope="col">代</th>
                                <th scope="col">假</th>
                                <th scope="col">起</th>
                                <th scope="col">迄</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="leave_data">
                           <tr><td colspan="6">無資料</td></tr>
                        </tbody>
                    </table>
                </div>
                <div id="useridfield"></div>
                <div id="result"></div>
            </div>
        </div>
    </body>
    <!-- Modal -->
    <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-leave">新增假別</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
            <div class="form-group container-fluid">
                <div class="row">
                <label for="leave-name" class="col-form-label w-25">假別:</label>
                <input type="text" class="form-control w-75 leave-name">
                </div>
                <div class="row">
                <label for="title-name" class="col-form-label w-25">簽核職等:</label>
                <div class="col-form-label w-75">
                    <select id="title_set_select"></select>
                </div>
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary todo" onclick="add_leave()">新增</button>
        </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/line/validateleave.js') }}"></script>
    <script src="{{ asset('js/restcall.js') }}"></script>
    <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
</html>