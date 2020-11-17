<div class="modal fade" id="modalImportLesson">
    <div class="modal-dialog modal-lg">
        {{ Form::open(['route' => 'learning.student.import','method' => 'POST','enctype' => 'multipart/form-data']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tải bài tập của bạn</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-md-2">Chọn file tải lên
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input type="file" class="form-control" name="file">
                        <span class="text-danger">(*.xlsx, *.xls, *.csv)</span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10 offset-2">
                        @if(session()->has('import_learning'))
                            <p class="text-danger">{{ session('import_learning') }}</p>
                        @endif
                        @include('admin.learning.errors')
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-window-close"></i> Hủy
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-save"></i> Tải lên
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
