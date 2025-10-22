<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pegawai FMS</title>
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
    <h2>Laporan Pegawai FMS</h2>
    
    <table>
        <thead>
          <tr>
            <th>
              ID
            </th>
            <th>
              Nama
            </th>
            <th>
              No. IC  
            </th>
            <th>
              Kod Cawangan
            </th>
            <th>
              No. Pegawai
            </th>
            <th>
              Status (FMS)
            </th>
            <th>
              Jawatan
            </th>
            <th>
              Penanda Pengurus (HR)
            </th>
            <th>
             Tarikh Berhenti (HR)
            </th>
          </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->officer_id }}</td>
                    <td>{{ $item->officer_name }}</td>
                    <td>{{ $item->nokp }}</td>
                    <td>{{ $item->branch_code }}</td> 
                    <td>{{ $item->staffno }}</td> 
                    <td>{{ $item->fms_userstatus }}</td> 
                    <td>{{ $item->officer_position }}</td>            
                    <td>{{ $item->hr_mgr_flag }}</td>            
                    <td>{{ $item->hr_date_resign }}</td>    
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
