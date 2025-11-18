<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Deskripsi Pengurus</title>
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
    <h2>Penyelenggaraan Deskripsi Pengurus</h2>
    
    <table>
        <thead>
            <tr>
                <th>Seq No</th>
                <th>Deskripsi Pengurus</th>
                <th>Dicipta Pada</th>
                <th>Dicipta Oleh</th>
                <th>Dikemaskini Pada</th>
                <th>Dikemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->seq_no }}</td>
                    <td>{{ $item->mgr_desc }}</td>
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
