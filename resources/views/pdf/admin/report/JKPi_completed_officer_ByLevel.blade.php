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
              Nama Pegawai
            </th>
            <th>
              Negeri
            </th>
            <th>
              Cawangan
            </th>
            <th>
              Peringkat PMGi
            </th>
            <th>
              Tarikh Laporan
            </th>
            <th>
              Jawatan
            </th>
          </tr>
        </thead>
        <tbody>
            @forelse ($report as $item)
                <tr>
                    <td>{{ $item->bankOfficer->officer_name }}</td>
                    <td>{{ $item->state->description }}</td>
                    <td>{{ $item->branch->branch_name }}</td>
                    <td>{{ $item->level->pmgi_level_desc}}</td>
                    <td>{{ $item->report_date ? date('d/m/Y', strtotime($item->report_date)) : '' }}</td> 
                    <td>{{ $item->result->pmgi_result_desc }}</td>  
                </tr>            
            @empty
                <tr>
                    <td colspan="6">
                        Tiada data dijumpai.
                    </td>
                </tr>              
            @endforelse
        </tbody>
    </table>
</body>
</html>
