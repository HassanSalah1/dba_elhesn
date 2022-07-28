<div class="table-responsive">
    <table id="datatable" class="datatable table table-bordered">
        <thead>
        <tr>
            @foreach($debatable_names as $column)

                <th>
                    {{$column}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
