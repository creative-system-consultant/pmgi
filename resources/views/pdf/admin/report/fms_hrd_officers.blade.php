<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pegawai Pembangunan Sumber Manusia</title>
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
            background: #f0f0f0; white-space: nowrap; 
        }
 
    </style>
</head>
<body>
    <h2>Laporan Pegawai Pembangunan Sumber Manusia</h2>
    
    <table>
        <thead>
          <tr>
            <th>
              Negeri
            </th>
            <th>
              Cawangan
            </th>
            <th>
              No. Pekerja
            </th>
            <th>
              Nama
            </th>
            <th>
              No. IC
            </th>
            <th>
              Jawatan
            </th>
            <th>
              Status
            </th>
            <th>
              Tarikh Berhenti
            </th>
            <th>
                Tarikh Cuti Hingga
            </th>
            <th>
                Tarikh Cuti Dari
            </th>
            <th>
                Kod Cuti
            </th>
          </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
              <tr>
                <td>{{ $item->negeri }}</td>
                <td>{{ $item->cawangan }}</td>
                <td>{{ $item->no_pekerja }}</td>
                <td>{{ $item->nama }}</td> 
                <td>{{ $item->no_kp }}</td> 
                <td>{{ $item->jawatan }}</td> 
                <td>{{ $item->status }}</td>            
                <td>{{ $item->resign_date ?  date('d/m/Y', strtotime($item->resign_date)) : ''}}</td>            
                <td>{{ $item->tarikh_cuti_dari ? date('d/m/Y', strtotime($item->tarikh_cuti_dari)) : ''}}</td>            
                <td>{{ $item->tarikh_cuti_hingga ? date('d/m/Y', strtotime($item->tarikh_cuti_hingga)) : '' }}</td>            
                <td>{{ $item->kod_cuti }}</td>            
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
