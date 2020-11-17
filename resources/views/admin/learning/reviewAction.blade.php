@extends('admin.master')
@section('title','Quản lý khóa học')
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
                                <h5 class="m-0 font-weight-bold text-center">Đánh giá khóa học:{{ $courseInfo->name }} </h5>
                                <div class="text-center">
                                    <p>Học viên: {{ $courseInfo->student_name }}</p>
                                </div>
                            </div>
                            <form
                                class="setting-form"
                                action="{{ route('learning.storeCompleteCourse',$courseInfo->course_student_id) }}"
                                method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if(!$questions->isEmpty())
                                                <?php
                                                $total_tn = 0;
                                                $total_tl = 0;
                                                ?>
                                                @foreach($questions as $index => $item)
                                                    <?php $checked = false; ?>
                                                    <div class="form-group">
                                                        <h4 class="font-weight-bold">
                                                            Câu {{ ++$index }}
                                                        </h4>
<div  style="padding-left: 20px;">
{!! $item->title !!}
</div>
                                                        <div>
                                                            @if($item->type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                                                @if(!empty($details))
                                                                    @foreach($details as $dt)
                                                                        @if($dt['answer'] === $item->answer_correct && $item->id === $dt['question_id'])
                                                                            <?php $checked = true; ?>
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <p>
                                                            @if($item->type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                                                <?php
                                                                $total_tn += 1;
                                                                ?>
                                                                @if($checked)
                                                                    <span class="badge badge-success">Đúng</span>
                                                                    <input type="hidden" name="right[{{$item->id}}][]"
                                                                           value="{{$item->id}}">
                                                                @else
                                                                    <span class="badge badge-danger">Sai</span>
                                                                    <input type="hidden" name="wrong[{{$item->id}}][]"
                                                                           value="{{$item->id}}">
                                                                @endif
                                                            @else
                                                                <?php
                                                                $total_tl += 1;
                                                                ?>

                                                                <div class="mt-2 mb-5">
                                                                <span class="badge badge-success">
                                                                    <label for="accept_{{ $item->id }}">Chấp nhận đáp án
                                                                        <input type="checkbox"
                                                                               value="{{$item->id}}"
                                                                               name="accept_answer[{{$item->id}}][]"
                                                                               id="accept_{{ $item->id }}">
                                                                    </label>
                                                                </span>
                                                                </div>
                                                            @endif
                                                        </p>

                                                        @if($item->type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                                            <ul style="padding: 0; list-style-type: none;">
                                                                @foreach(json_decode($item->answers) as $key => $val)
                                                                    <li class="mb-1 text-muted">
                                                                        <?php $checked = ''; ?>
                                                                        @if(!empty($details))
                                                                            @foreach($details as $dt)
                                                                                @if($dt->answer === $key && $item->id === $dt->question_id)
                                                                                    <?php $checked = $key;  ?>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                        <input type="radio"
                                                                               @if($checked === $key) checked
                                                                               @else disabled @endif
                                                                               name="answer[{{$item->id}}][]"
                                                                               id="" value="{{ $key }}">
                                                                        <span
                                                                            class="mr-1 ml-2">{{$key}}.</span>{{ $val }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
<div class="panel panel-primary col-md-12">
<div class="panel-body">
<h4 class="text-on-pannel text-primary">Câu trả lời của học viên</h4>
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
                                                            <div class="border-success">
                                                                {!! $correct !!}
                                                            </div>                                                            
</div>
</div>
</div>
</div>
                                                            
                                                        @endif
                                                    </div>
                                                   <hr>
                                                @endforeach
                                                    <input type="hidden" name="total_tn" value="{{ $total_tn }}">
                                                    <input type="hidden" name="total_tl" value="{{ $total_tl }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="">Đánh giá chung:</label>
                                            <textarea name="review_note" id="review_note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Hoàn thành đánh giá
                                    </button>
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
    @if(!$questions->isEmpty())
        @foreach($questions as $index => $item)
            @if($item->type === \App\Model\Questions::TYPE_ONE_CHOICE)
                <script type="text/javascript">
                    $(document).ready(function () {
                        var idQuestion = {{ $item->id }}
                        console.log(idQuestion)
                        $(function () {
                            CKEDITOR.replace('answer_' + idQuestion);
                        });
                    })
                </script>
            @endif
        @endforeach
    @endif
    <script type="text/javascript">
        $(document).ready(function () {
            $(function () {
                CKEDITOR.replace('review_note');
            });
        })
    </script>
@endsection

