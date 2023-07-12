<x-print.layout>
    <table class="table">
        <thead>
            <tr>
                @foreach($headers as $header)
                <th>{{$header}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $item)
                    <td>{{$item}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        @if($footer)
            <tfoot>
                <tr>
                    @foreach($footers as $footer)
                    <th>{{$footer}}</th>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
</x-print.layout>