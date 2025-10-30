<!DOCTYPE html>
<html>
<head>
    <title>Penyelenggaraan Pemetaan Cawangan - HR ke FMS</title>
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
    <h2>Penyelenggaraan Pemetaan Cawangan - HR ke FMS</h2>
    
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>ID</th>
                <th>Negeri Dlm FMS</th>
                <th>CAW Dlm FMS</th>
                <th>Kod CAW FMS</th>
                <th>Negeri Dlm Sistem HR</th>
                <th>CAW Dlm Sistem HR</th>
                <th>Kod CAW HR</th>
                <th>Jenis Kemaskini</th>                
                <th>Kemas Kini Pada</th>
                <th>Kemas Kini Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td>{{ $item->seq_no }}</td>
                    <td>{{ $item->fms_state_name }}</td>
                    <td>{{ $item->fms_branch_name }}</td>
                    <td>{{ $item->fms_branch_code }}</td>
                    <td>{{ $item->hr_state_name }}</td>
                    <td>{{ $item->hr_branch_name }}</td>
                    <td>{{ $item->hr_branch_code }}</td>
                    <td>
                        @if($item->update_ind == 'A')
                            Cipta
                        @elseif($item->update_ind == 'U')
                            Pinda
                        @else
                            Hapus
                        @endif                           
                    </td>                    
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
