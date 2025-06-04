<div class="total-section">
    <table class="total-table">
        <thead class="total-table-head">
            <tr class="table-total-row">
                <th>Tổng cộng</th>
                <th>Giá</th>
            </tr>
        </thead>
        <tbody>
            <tr class="total-data">
                <td><strong class="label">Tạm tính: </strong></td>
                <td>
                    <span class="value subtotal" id="subtotal">
                        {{ number_format($total, 0, ',', '.') }} đ
                    </span>
                </td>
            </tr>
            <tr class="total-data total-final">
                <td><strong class="label">Thành tiền:</strong></td>
                <td>
                    <span class="value total" id="total">
                        {{ number_format($total, 0, ',', '.') }} đ
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="cart-buttons">
        <a href="{{ route('product') }}" class="boxed-btn"><i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
        <a href="{{ route('cart.check-out') }}" class="boxed-btn black btn-check-out"><i class="fas fa-credit-card"></i>  Đặt hàng</a>
    </div>
</div>