@if(Session::has('success'))
    <script type="text/javascript">
        var message = "{{Session::get('success') }}"
        toastr.success(message);
    </script>
@endif

@if(Session::has('error'))
    <script type="text/javascript">
        var message = "{{Session::get('error') }}"
        toastr.error(message);
    </script>
@endif

@if(Session::has('warning'))
    <script type="text/javascript">
        var message = "{{Session::get('warning') }}"
        toastr.warning(message);
    </script>
@endif

@if(Session::has('not_permission'))
    <script type="text/javascript">
        var message = "{{Session::get('not_permission') }}"
        toastr.error(message);
    </script>
@endif

<script !src="">
    $('.delete').click(function () {
        $('.form-delete').attr('action', $(this).attr('data-link'));
        $('#deleteModal').modal('show');
    });
    $('.update').click(function () {
        $('.form-update').attr('action', $(this).attr('data-link'));
        $('#updateModal').modal('show');
    });
</script>

<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <form action="" method="post" class="form-delete">
            @csrf
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <i class="fas fa-question-circle" style="font-size: 50px;color: red;"></i>
                    <h3 style="padding-top: 15px; font-size: 17px;"> Bạn có muốn xóa không?</h3>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger btn-sm">Chấp nhận</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateModal">
    <div class="modal-dialog">
        <form action="" method="post" class="form-update">
            @csrf
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <i class="fas fa-question-circle" style="font-size: 50px;color: green;"></i>
                    <h3 style="padding-top: 15px; font-size: 17px;"> Bạn có muốn cập nhật không?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm">Chấp nhận</button>
                </div>
            </div>
        </form>
    </div>
</div>
