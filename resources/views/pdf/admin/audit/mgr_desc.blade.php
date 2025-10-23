<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit Deskripsi Pengurus</title>
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
    <h2>Laporan Audit Deskripsi Pengurus</h2>
    
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>ID</th>
                <th>Deskripsi Pengurus</th>
                <th>Jenis Kemaskini</th>
                <th>Kemaskini Pada</th>
                <th>Kemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td>{{ $item->seq_no }}</td>
                    <td>{{ $item->mgr_desc }}</td>
                    <td>
                        @if($item->update_ind == 'A')
                            Cipta
                        @elseif($item->update_ind == 'U')
                            Pinda
                        @else
                            Hapus
                        @endif                           
                    </td>
                    <td>{{ $item->updated_at ? date('d/m/Y H:i:s', strtotime($item->updated_at)) : ''  }}</td>
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
