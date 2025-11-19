<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Pengecualian Cawangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table { 
            width: 100%; border-collapse: collapse; 
        }
        
        th, td { 
            border: 1px solid #333; padding: 6px; font-size: 12px; 
        }

        th { 
            background: #f0f0f0; 
        }

    </style>
</head>
<body>
    <h2>Penyelenggaraan Pengecualian Cawangan</h2>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Negeri</th>
                <th>Cawangan</th>
                <th>Dicipta Pada</th>
                <th>Dicipta Oleh</th>
                <th>Dikemaskini Pada</th>
                <th>Dikemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->state_name }}</td>
                    <td>{{ $item->branch_name }}</td>
                    <td>{{ $item->created_at ? date('d/m/Y H:i:s', strtotime($item->created_at)) : '' }}</td>
                    <td>{{ $item->created_by }}</td>
                    <td>{{ $item->updated_at ? date('d/m/Y H:i:s', strtotime($item->updated_at)) : '' }}</td>
                    <td>{{ $item->updated_by }}</td>
                </tr>
                
            @empty
                <tr>
                    <td colspan="7">
                        Tiada data dijumpai.
                    </td>
                </tr>            
            @endforelse
        </tbody>
    </table>
</body>
</html>
