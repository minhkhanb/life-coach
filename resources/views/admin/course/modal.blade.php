<div class="modal fade" id="importExercise">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('lesson.import') }}" method="post"
              class="form-delete" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import câu hỏi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div class="form-group row">
                        <label for="" class="col-md-2">
                            Chọn bài giảng
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10">
                            <select name="course" id="" class="form-control">
                                <option value="" selected disabled>---Chọn bài giảng---</option>
                                @php
                                    showDropdown($courseAll, old('course'));
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-2">Chọn file</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control" name="file">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 offset-2 text-left mt-3">
                            <a href="{{ route('lesson.downloadTemplate') }}" target="_blank"
                               style="border-right: 1px solid #000b16;" class="mr-1 pr-1">
                                <i class="fas fa-download"></i> Tải file mẫu excel
                            </a>
                            <a href="{{ route('lesson.downloadTemplateWord') }}" target="_blank" class="ml-2"
                               style="border-right: 1px solid #000b16;" class="mr-1 pr-1">
                                <i class="fas fa-download"></i> Tải file mẫu word tự luận
                            </a>
                            <a href="{{ route('lesson.downloadTemplateWordTwo') }}" target="_blank" class="ml-2">
                                <i class="fas fa-download"></i> Tải file mẫu word trắc nghiệm
                            </a>
                            <p class="text-danger mt-2 font-italic font-weight-bold">
                                Chú ý: Nhập đúng định dạng file mà hệ thống cung cấp!
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10 offset-2">
                            @if ($errors->import_question->any() )
                                <div class="text-danger text-left">
                                    <ul>
                                        @foreach ($errors->import_question->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-window-close"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save"></i> Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalSendToLesson">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('lesson.share') }}" method="post"
              class="form-delete" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chia sẻ bài giảng tới học viên</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div class="form-group row">
                        <label for="" class="col-md-2">Tên bài giảng:</label>
                        <div class="col-md-10">
                            <input type="text" name="link_lesson" id="link_lesson" value="" class="form-control"
                                   readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-2">Danh sách email học viên:</label>
                        <div class="col-md-10">
                            <div class="text-left" style="max-height: 500px; overflow: auto;">
                                @if(!$students->isEmpty())
                                    <input type="checkbox" id="checkAll" checked>Tất cả
                                    <ul class="list-group list-group-flush">
                                    @foreach($students as $item)
                                        <li class="list-group-item">
                                             <input type="checkbox" id="email_{{$item->id}}" name="students[]" value="{{$item->id}}" checked>
                                             <label for="email_{{$item->id}}">{{ $item->email }}</label>
                                        </li>
                                    
                                    @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-window-close"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-share"></i> Gửi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

