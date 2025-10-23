<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit Peringkat PMGi</title>
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
    <h2>Laporan Audit Peringkat PMGi</h2>
    
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Tarikh Kuat Kuasa</th>
                <th>Peringkat PMGi</th>
                <th>Tempoh Menunggu (Bulan)</th>
                <th>Jenis Kemaskini</th>
                <th>Kemaskini Pada</th>
                <th>Kemaskini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ $item->effective_date ? date('d/m/Y', strtotime($item->effective_date)) : '' }}</td>
                    <td>{{ $item->pmgi_level }}</td>
                    <td>{{ $item->wait_period }}</td>
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
