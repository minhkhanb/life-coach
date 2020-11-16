@extends('admin.master')
@section('title','Làm bài tập')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="">Học viên</a></li>
                        <li class="breadcrumb-item">Tình trạng học</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-lg-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="m-0 text-center font-weight-bold">{{ $courseInfo->name }} </h5>
                        </div>
                        <form class="setting-form" action="{{ route('learning.student.saveLearnCourse', $courseInfo->course_student_id) }}"
                          method="post">
                          @csrf
                          <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(!$questions->isEmpty())
                                    @foreach($questions as $index => $item)
                                    <div class="form-group">
                                        <h4 class="mr-1 font-weight-bold">Câu {{ ++$index }}.</h4>
                                        <p>Nội dung câu hỏi:</>
                                            <div style="padding-left: 20px;">{!! $item->title  !!}</div>
                                            <div class="text-danger pb-2">{!!  $item->answer_correct  !!}</div>
                                            @if($item->type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                            <div class="panel panel-primary col-md-12">
                                                <div class="panel-body">
                                                    <h4 class="text-on-pannel text-primary">Câu trả lời của bạn</h4>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @error("answer.$item->id")
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                            <ul style="padding: 0; list-style-type: none;">
                                                                @foreach(json_decode($item->answers) as $key => $val)
                                                                <li class="mb-1 text-muted">
                                                                    <?php $checked = ''; ?>
                                                                    @if(!empty($details))
                                                                    @foreach($details as $dt)
                                                                    @if($dt['answer'] === $key && $item->id === $dt['question_id'])
                                                                    <?php $checked = $key; ?>
                                                                    @endif
                                                                    @endforeach
                                                                    @endif
                                                                    <input type="radio"
                                                                    @if($checked) checked @endif
                                                                    name="answer[{{$item->id}}][]"
                                                                    id="" value="{{ $key }}">
                                                                    <span
                                                                    class="mr-1 ml-2">{{$key}}.</span>{{ $val }}
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="panel panel-primary col-md-12">
                                                <div class="panel-body">
                                                    <h4 class="text-on-pannel text-primary">Câu trả lời của bạn</h4>
                                                    <div class="row">
                                                        <div class="col-12">

                                                            <?php $correct = '' ?>
                                                            @if(!empty($details))
                                                            @foreach($details as $dt)
                                                            @if($item->id === $dt['question_id'])
                                                            <?php $correct = $dt['answer']; ?>
                                                            @endif
                                                            @endforeach
                                                            @endif
                                                            @error("answer.$item->id")
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                            <textarea name="answer[{{$item->id}}]"
                                                              id="answer_{{$item->id}}">{{ $correct }}</textarea>                                                          
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              @endif
                                          </div>
                                          @endforeach
                                          @endif
                                      </div>
                                  </div>
                                  @if($courseInfo->status === \App\Model\CourseStudent::STATUS_CANCEL)
                                  <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Đánh giá của giáo viên:</label>
                                            <textarea name="" id="review_text"
                                            rows="20">{{ $courseInfo->review }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                @if($courseInfo->status === \App\Model\CourseStudent::STATUS_INPROGRESS)
                                <button type="submit" class="btn btn-success float-right">
                                    <i class="fas fa-save"></i> Hoàn thành
                                </button>
                                @endif
                                <a href="{{ route('learning.student.inProgress') }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-alt-circle-left"></i>
                                Trở về
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
</div>
@endsection
@section('css')
<style>
    .setting-form .panel-primary {
        border-color: #00b5b8;
    }
    .setting-form .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid #00b5b8;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        padding: 20px 10px;
        display: inline-block;
    }
    .setting-form .text-on-pannel {
        background: #fff none repeat scroll 0 0;
        height: auto;
        margin-left: 20px;
        padding: 3px 5px;
        position: absolute;
        margin-top: -40px;
        border: 1px solid #337ab7;
        border-radius: 8px;
        font-size: 15px;
        text-transform: uppercase;
        font-weight: 500;
    }
    @endsection
    @section('js')
    <script src="/public/theme_admin/vendor/ckeditor/ckeditor.js"></script>
    <script !src="">
    $(document).ready(function () {
        $(function () {
            CKEDITOR.replace('review_text');
            });
        })
    </script>
    @if(!$questions->isEmpty())
    @foreach($questions as $index => $item)
    @if($item->type === \App\Model\Questions::TYPE_ONE_CHOICE)
    <script type="text/javascript">
    $(document).ready(function () {
        var idQuestion = {{ $item->id }}
        $(function () {
            CKEDITOR.replace('answer_' + idQuestion);
            });
        })
    </script>
    @endif
    @endforeach
    @endif
    @endsection

