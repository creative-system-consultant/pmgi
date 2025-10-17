<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Peringkat PMGi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table { 
            width: 100%; border-collapse: collapse; 
        }
        
        th, td { 
            border: 1px solid #333; padding: 6px; font-size: 12px; text-align: left; 
        }

        th { 
            background: #f0f0f0; 
        }

    </style>
</head>
<body>
    <h2>Penyelenggaraan Peringkat PMGi</h2>
    
    <table>
        <thead>
            <tr>
                <th>Seq No</th>
                <th>Peringkat PMGi</th>
                <th>Deskripsi Peringkat PMGi (Pengguna)</th>
                <th>Deskripsi Peringkat PMGi (Sistem)</th>
                <th>Dikemaskini Pada</th>
                <th>Dikemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->seq_no }}</td>
                    <td>{{ $item->pmgi_level }}</td>
                    <td>{{ $item->pmgi_level_desc }}</td>
                    <td>{{ $item->pmgi_sys_level_desc }}</td>
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
