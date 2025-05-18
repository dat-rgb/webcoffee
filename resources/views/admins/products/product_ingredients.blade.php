<div id="ingredient-select-template" style="display: none;">
    <select class="form-select mb-2 INGREDIENT_SELECT_REPLACE">
        <option value="">Chọn nguyên liệu</option>
        @foreach ($ingredients as $ing)
            <option value="{{ $ing->ma_nguyen_lieu }}">{{ $ing->ten_nguyen_lieu }}</option>
        @endforeach
    </select>
</div>
