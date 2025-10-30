<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Pengencualian Log Masuk Pengguna</title>
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
    <h2>Penyelenggaraan Pengencualian Log Masuk Pengguna</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID Pengguna</th>
                <th>Nama Pengguna</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->userid }}</td>
                    <td>{{ $item->username }}</td>
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
