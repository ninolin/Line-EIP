@extends('contents.WorkSetting.master')
@section('content2')
<div class="container-fluid pt-lg-4">
  <form>
    <div class="row">
      <div class="col-sm-4 form-row">
        <div class="col-auto">
          <button type="button" class="btn-c" onclick="showTitleModal('add')">新增職等</button>
        </div>
      </div>
    </div>
  </form>
  <div class="row p-lg-3">
    <table class="table table-bordered table-striped">
      <thead class="table-thead">
        <tr>
          <th scope="col">職等</th>
          <th scope="col" class="w-25">操作</th>
        </tr>
      </thead>
      <tbody>
        @if(count($titles) === 0) 
          <tr>
            <td class="text-center"> 目前無資料 </td>
          </tr>
        @else
          @foreach($titles as $title)
            <tr>
              <td> {{$title->name}} </td>
              <td>  
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="showTitleModal('update', '{{$title->id}}', '{{$title->name}}')">修改</button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="showDeleteModal({{$title->id}}, '{{$title->name}}')">刪除</button>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item @if ($page == 1) disabled @endif ">
          <a class="page-link" href="./title?page={{ $page-1 }}">上一頁</a>
        </li>
        @for ($i = 1; $i <= $total_pages; $i++)
          <li class="page-item @if ($i == $page) active @endif"><a class="page-link" href="./title?page={{ $i }}">{{$i}}</a></li>
        @endfor
        <li class="page-item @if ($page == $total_pages) disabled @endif">
          <a class="page-link" href="./title?page={{ $page+1 }}">下一頁</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="titleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">新增職等</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group container-fluid">
            <div class="row">
              <label for="title-name" class="col-form-label col-md-4">職等:</label>
              <input type="text" class="form-control col-md-8 title-name">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary todo" onclick="add_title()">新增</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-leave">刪除職等</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="container-fluid">
            <div class="row form-group delete_msg"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-danger todo" onclick="delete_title()">刪除</button>
      </div>
    </div>
  </div>
</div>

<script>
  const showTitleModal = (type, title_id, title_name) => {
    if(type == 'update') {
        $('#titleModal').modal('toggle');
        $("#titleModal").find(".modal-title").html("修改職等");
        $("#titleModal").find(".title-name").val(title_name);
        $("#titleModal").find(".todo").attr("onclick", "update_title('"+title_id+"')").html("修改");
    } else if(type == 'add') {
        $('#titleModal').modal('toggle');
        $("#titleModal").find(".modal-title").html("新增職等");
        $("#titleModal").find(".title-name").val('');
        $("#titleModal").find(".todo").attr('onclick', "add_title()").html("新增");
    }
  }

  const add_title = () => {
      promise_call({
          url: "/api/title", 
          data: {"name": $("#titleModal").find(".title-name").val()}, 
          method: "post"
      })
      .then(v => {
          if(v.status == "successful") {
              window.location.reload();
          } else {
              //$('#addTitleModal').modal('toggle');
              alert(v.message);
          }
      })
  }

  const update_title = (title_id) => {
      promise_call({
          url: "/api/title/"+title_id, 
          data: {"name": $("#titleModal").find(".title-name").val()}, 
          method: "put"
      })
      .then(v => {
          if(v.status == "successful") {
              window.location.reload();
          } else {
              alert(v.message);
          }
      })
  }

  const showDeleteModal = async (title_id, title_name) => {
      $("#deleteModal").find(".todo").attr("onclick", "delete_title('"+title_id+"')").html("刪除");
      $("#deleteModal").find(".delete_msg").html("確認要刪除「"+title_name+"」該職等嗎?");
      $('#deleteModal').modal('toggle');
  }

  const delete_title = (title_id) => {
      promise_call({
          url: "/api/title/"+title_id,
          method: "delete"
      })
      .then(v => {
          if(v.status == "successful") {
              window.location.reload();
          } else {
              alert(v.message);
          }
      })
  }
</script>
@endsection
