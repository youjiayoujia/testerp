@extends('common.detail')
@section('detailBody')

            <table class="table table-bordered table-striped table-hover sortable">
                <theah>
                    <tr>
                        <th>时间</th>
                        <th>好评</th>
                        <th>中评</th>
                        <th>差评</th>
                        <th>中差评比例</th>
                    </tr>
                    <tbody>
                    @foreach($data as $key => $item)
                        <tr>
                        <td>{{$key}}</td>
                        <td>{{$item['Positive']}}</td>
                        <td>{{$item['Neutral']}}</td>
                        <td>{{$item['Negative']}}</td>
                        <td>{{$item['Percentage']}}%</td>
                        </tr>
                    @endforeach
                    </tbody>
                </theah>

            </table>

@stop