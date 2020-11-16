<form action="{{ $route }}">
    <div class="row">
        <div class="form-group col-4 mt-0">
            <select name="status" id="" class="form-control">
                <option value="">Tất cả</option>
                <option
                    value="{{ \App\Model\CourseStudent::STATUS_COMPLETE }}" @if((int)$search['status'] === 1) selected @endif>
                    Chưa đánh giá
                </option>
                <option value="{{ \App\Model\CourseStudent::STATUS_CANCEL }}" @if((int)$search['status'] === 2) selected @endif>
                    Đã đánh giá
                </option>
            </select>
        </div>
        <div class="form-group form-inline mt-0 ml-1">
            <button type="submit" class="btn btn-info">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>
