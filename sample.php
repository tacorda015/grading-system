<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Example</title>
    <style>
        *{
            font-size: 16px;
            padding: 5px !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();

            $('#example tbody').on('click', 'td', function() {
                $(this).attr('contenteditable', true).focus();
            });

            $('#example tbody').on('blur', 'td', function() {
                var newValue = $(this).text();
                var rowIndex = table.cell(this).index().row;
                var columnIndex = table.cell(this).index().column;

                table.cell(rowIndex, columnIndex).data(newValue);
                $(this).removeAttr('contenteditable');
            });
        });
    </script>
</head>
<body>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th rowspan="3">Name</th>
                <th colspan="8">Regular Quizez</th>
                <th colspan="8">Attendace</th>
                <th colspan="3">Requirements</th>
                <th colspan="3">Project</th>
                <th rowspan="2">grade</th>
                <th rowspan="2">percent</th>
                <th rowspan="2">remark</th>
            </tr>
            <tr>
                <th>R1</th>
                <th>R2</th>
                <th>R3</th>
                <th>R4</th>
                <th>R5</th>
                <th>RTotal</th>
                <th>RGrade</th>
                <th>RPercent</th>
                <th>A1</th>
                <th>A2</th>
                <th>A3</th>
                <th>A4</th>
                <th>A5</th>
                <th>ATotal</th>
                <th>AGrade</th>
                <th>APercent</th>
                <th>R1</th>
                <th>RTotal</th>
                <th>RGrade</th>
                <th>RPercent</th>
                <th>P1</th>
                <th>PTotal</th>
                <th>PGrade</th>
                <th>PPercent</th>
            </tr>
            <tr>
                <th>R1</th>
                <th>R2</th>
                <th>R3</th>
                <th>R4</th>
                <th>R5</th>
                <th>RTotal</th>
                <th>RGrade</th>
                <th>RPercent</th>
                <th>A1</th>
                <th>A2</th>
                <th>A3</th>
                <th>A4</th>
                <th>A5</th>
                <th>ATotal</th>
                <th>AGrade</th>
                <th>APercent</th>
                <th>R1</th>
                <th>RTotal</th>
                <th>RGrade</th>
                <th>RPercent</th>
                <th>P1</th>
                <th>PTotal</th>
                <th>PGrade</th>
                <th>PPercent</th>
                <th>PPercent</th>
                <th>PPercent</th>
                <th>PPercent</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>USA</td>
                <td>John Doe</td>
                <td>30</td>
                <td>30</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>30</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
                <td>USA</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
