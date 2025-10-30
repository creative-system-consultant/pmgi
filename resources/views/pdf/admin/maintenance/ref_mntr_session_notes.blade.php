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
                <th>Kod Nota Sesi</th>
                <th>Deskripsi Nota Sesi(Pengguna)</th>
                <th>Deskripsi Nota Sesi (Sistem)</th>
                <th>Dikemaskini Pada</th>
                <th>Dikemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->sesn_note_code }}</td>
                    <td>{{ $item->sesn_note_desc }}</td>
                    <td>{{ $item->sesn_note_sys_desc }}</td>
                    <td>{{  $item->updated_at ? date('d/m/Y H:i:s', strtotime($item->updated_at)) : '' }}</td>
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
