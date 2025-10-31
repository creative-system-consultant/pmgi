<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Deskripsi Keputusan PMGi</title>
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
    <h2>Penyelenggaraan Deskripsi Keputusan PMGi</h2>
    
    <table>
        <thead>
            <tr>
                <th>Tarikh Kuat Kuasa</th>
                <th>Peringkat PMGi</th>
                <th>Tempoh Menunggu (Bulan)</th>
                <th>Dicipta Pada</th>
                <th>Dicipta Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($item->effective_date)) }}</td>
                    <td>{{ $item->wait_period }}</td>
                    <td>{{ $item->pmgi_level }}</td>
                    <td>{{ $item->updated_at ? date('d/m/Y H:i:s', strtotime($item->updated_at)) : '' }}</td>
                    <td>{{ $item->created_by }}</td>
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
