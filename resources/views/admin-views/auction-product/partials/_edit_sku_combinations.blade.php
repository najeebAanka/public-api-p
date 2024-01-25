@if(count($combinations) > 0)
    <table class="table table-bordered">
        <thead>
        <tr>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('Size')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('US')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('UK')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('EU')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('Variant Price')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('SKU')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('Quantity')}}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{\App\CPU\translate('User')}}</label>
            </td>
        </tr>
        </thead>
        <tbody>
        @endif
        @foreach ($combinations as $key => $combination)
            <tr>
                <td>
                    <label for="" class="control-label">{{ $combination['type'] }}</label>
                    <input value="{{ $combination['type'] }}" name="type[]" style="display: none">
                </td>
                <td>
                    <input type="text" name="us_{{ $combination['type'] }}"
                           value="{{ $combination['us'] }}"
                           class="form-control"
                           required>
                </td>
                <td>
                    <input type="text" name="uk_{{ $combination['type'] }}"
                           value="{{ $combination['uk'] }}"
                           class="form-control"
                           required>
                </td>
                <td>
                    <input type="text" name="eu_{{ $combination['type'] }}"
                           value="{{ $combination['eu'] }}"
                           class="form-control"
                           required>
                </td>
                <td>
                    <input type="number" name="price_{{ $combination['type'] }}"
                           value="{{ \App\CPU\Convert::default($combination['price']) }}" min="0"
                           step="0.01"
                           class="form-control" required>
                </td>
                <td>
                    <input type="text" name="sku_{{ $combination['type'] }}" value="{{ $combination['sku'] }}"
                           class="form-control" required>
                </td>
                <td>
                    <input type="number" onkeyup="update_qty()" name="qty_{{ $combination['type'] }}"
                           value="{{ $combination['qty'] }}" min="1" max="100000" step="1"
                           class="form-control"
                           required>
                </td>
                <td>
                    @php($user = \App\User::where('id',$combination['user'])->first())
                    <input type="hidden" name="user_{{ $combination['type'] }}"
                           value="{{$combination['user'] }}"
                           class="form-control"
                           required>
                    @if($user)
                        {{$user->name}}
                    @else
                        Admin
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

