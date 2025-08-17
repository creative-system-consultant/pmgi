<!DOCTYPE html>
<html>
<head>
    <title>BORANG JPOC 09</title>
    <style>
        body {
            font-family: "Trebuchet MS", "Arial Unicode MS", Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .centered {
        position: absolute;
        }

        .page_break {
        page-break-before: always;
        }

        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .input-container {
            position: absolute;
            font-size: 12px;
            background: transparent;
        }

        .checkmark {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 20px;
            background: transparent;
        }

    </style>
</head>
<body>
    <!--------------------------------------------------------------- page 1 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi3-1.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">

        <div class="content-overlay">
            {{-- pmgi level --}}
            <div class="input-container" style="top: 88px; left: 160px; font-size: 16px;">
                <strong class="checkmark">&#x2713;</strong>
            </div>

            {{-- nama --}}
            <div class="input-container" style="top: 159px; left: 190px;">
                <strong>{{ $bankOfficerPyd->officer_name }}</strong>
            </div>

            {{-- ic --}}
            <div class="input-container" style="top: 189px; left: 190px;">
                <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
            </div>

            {{-- staff no --}}
            <div class="input-container" style="top: 189px; left: 550px;">
                <strong>{{ $bankOfficerPyd->staffno }}</strong>
            </div>

            {{-- jawatan --}}
            <div class="input-container" style="top: 217px; left: 190px;">
                <strong>{{ $bankOfficerPyd->officer_position }}</strong>
            </div>

            {{-- tarikh lantikan --}}
            <div class="input-container" style="top: 217px; left: 550px;">
                <strong>30/1/2019</strong>
            </div>

            {{-- negeri --}}
            <div class="input-container" style="top: 245px; left: 190px;">
                <strong>{{ $state }}</strong>
            </div>

            {{-- branch --}}
            <div class="input-container" style="top: 272px; left: 190px;">
                <strong>{{ $branch }}</strong>
            </div>

            {{-- tempoh berkhidmat --}}
            <div class="input-container" style="top: 297px; left: 335px;">
                <strong>{{ $tempohBerkhidmat }}</strong>
            </div>

            {{-- no fon --}}
            <div class="input-container" style="top: 337px; left: 190px;">
                <strong>{{ $bankOfficerPyd->hrData->notel }}</strong>
            </div>

            {{-- emel --}}
            <div class="input-container" style="top: 337px; left: 450px;">
                <strong>{{ $bankOfficerPyd->email }}</strong>
            </div>

            {{-- alamat 1 --}}
            <div class="input-container" style="top: 365px; left: 190px;">
                <strong>{{ $alamat1 }}</strong>
            </div>

            {{-- alamat 2 --}}
            <div class="input-container" style="top: 390px; left: 80px;">
                <strong>{{ $alamat2 }}</strong>
            </div>

            {{-- bil seliaan --}}
            <div class="input-container" style="top: 487px; left: 270px;">
                <strong>{{ $accCount }}</strong>
            </div>

            {{-- npf os % --}}
            <div class="input-container" style="top: 487px; left: 460px;">
                <strong>{{ $npfOs }}</strong>
            </div>

            {{-- b1 --}}
            <div class="input-container" style="top: 515px; left: 290px;">
                <strong>{{ $summMthOfficer->bil_b1 ?? 0 }}</strong>
            </div>

            {{-- b2 --}}
            <div class="input-container" style="top: 515px; left: 350px;">
                <strong>{{ $summMthOfficer->bil_b1 ?? 0 }}</strong>
            </div>

            {{-- c1 --}}
            <div class="input-container" style="top: 515px; left: 408px;">
                <strong>{{ $summMthOfficer->bil_c1 ?? 0 }}</strong>
            </div>

            {{-- c2 --}}
            <div class="input-container" style="top: 515px; left: 465px;">
                <strong>{{ $summMthOfficer->bil_c2 ?? 0 }}</strong>
            </div>

            {{-- d --}}
            <div class="input-container" style="top: 515px; left: 515px;">
                <strong>{{ $summMthOfficer->bil_d ?? 0 }}</strong>
            </div>

            {{-- tarikh --}}
            <div class="input-container" style="top: 661px; left: 270px;">
                <strong>{{ \Carbon\Carbon::parse($sessionInfo->session_date)->format('d/m/Y') }}</strong>
            </div>

            {{-- masa --}}
            <div class="input-container" style="top: 687px; left: 270px;">
                <strong>{{ \Carbon\Carbon::parse($sessionInfo->session_date)->format('h:i:s A') }}</strong>
            </div>

            {{-- tempat --}}
            <div class="input-container" style="top: 715px; left: 270px;">
                <strong>{{ $sessionInfo->venue ?? 'ATAS TALIAN' }}</strong>
            </div>

            {{-- pym --}}
            <div class="input-container" style="top: 785px; left: 270px;">
                <strong>{{ $bankOfficerPym->officer_name . ' (' . $bankOfficerPym->staffno . ')' }}</strong>
            </div>

            {{-- pmc --}}
            <div class="input-container" style="top: 812px; left: 270px;">
                <strong>{{ $bankOfficerPmc->officer_name . ' (' . $bankOfficerPmc->staffno . ')' }}</strong>
            </div>
        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 2 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-2.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- nama --}}
            <div class="input-container" style="top: 129px; left: 190px;">
                <strong>{{ $bankOfficerPyd->officer_name }}</strong>
            </div>

            {{-- staff no --}}
            <div class="input-container" style="top: 149px; left: 190px;">
                <strong>{{ $bankOfficerPyd->staffno }}</strong>
            </div>

            {{-- branch --}}
            <div class="input-container" style="top: 169px; left: 190px;">
                <strong>{{ $branch }}</strong>
            </div>

            {{-- negeri --}}
            <div class="input-container" style="top: 189px; left: 190px;">
                <strong>{{ $state }}</strong>
            </div>
            
            <div class="input-container">
                <img src="{{ $paths['image_path'] }}" alt="Generated Image" style="margin-top: 250px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 3 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-3.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- masalah dihadapi --}}
            <div class="input-container" style="top: 190px; left: 100px;">
                <strong>{{ $pydInfo->problemTable->description }}</strong>
            </div>

            {{-- punca --}}
            <div class="input-container" style="top: 360px; left: 100px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pydInfo->reason }}</strong>
            </div>

            {{-- pelan tindakan --}}
            <div class="input-container" style="top: 550px; left: 100px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pydInfo->action }}</strong>
            </div>

            {{-- ulasan --}}
            {{-- <div class="input-container" style="top: 660px; left: 100px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pydInfo->comments }}</strong>
            </div> --}}
        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 4 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-4.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- ulasan --}}
            {{-- <div class="input-container" style="top: 190px; left: 140px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pymInfo->comments }}</strong>
            </div> --}}

            {{-- pelan tindakan --}}
            <div class="input-container" style="top: 190px; left: 100px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pymInfo->action }}</strong>
            </div>
        </div>
    </div>
    <div class="page_break"></div>

    <!--------------------------------------------------------------- page 5 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi3-5.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- adil flag --}}
            @if($pmcInfo->fair_flag == 1)
                <div class="input-container" style="top: 140px; left: 140px; font-size: 11px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @else
                <div class="input-container" style="top: 175px; left: 140px; font-size: 11px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @endif

            {{-- ulasan --}}
            <div class="input-container" style="top: 260px; left: 140px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pmcInfo->fair_comments }}</strong>
            </div>

            {{-- adil flag --}}
            @if($pmcInfo->undrstd_flag == 1)
                <div class="input-container" style="top: 423px; left: 140px; font-size: 11px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @else
                <div class="input-container" style="top: 460px; left: 140px; font-size: 11px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @endif

            {{-- ulasan --}}
            <div class="input-container" style="top: 510px; left: 140px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pmcInfo->comments }}</strong>
            </div>

            {{-- lain-lain --}}
            <div class="input-container" style="top: 685px; left: 140px; width: 500px; word-wrap: break-word; white-space: normal;">
                <strong>{{ $pmcInfo->others }}</strong>
            </div>
        </div>
    </div>
    <div class="page_break"></div>

    <!--------------------------------------------------------------- page 6 ----------------------------------------------------------------------------->    
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi3-6.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- nama --}}
            <div class="input-container" style="top: 118px; left: 110px; font-size: 11px;">
                <strong>{{ $bankOfficerPyd->officer_name }}</strong>
            </div>

            {{-- ic --}}
            <div class="input-container" style="top: 118px; left: 560px; font-size: 11px;">
                <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
            </div>

            {{-- staff no --}}
            <div class="input-container" style="top: 140px; left: 240px; font-size: 11px;">
                <strong>{{ $bankOfficerPyd->staffno }}</strong>
            </div>

            {{-- branch --}}
            <div class="input-container" style="top: 140px; left: 455px; font-size: 11px;">
                <strong>{{ $branch }}</strong>
            </div>

            {{-- from --}}
            <div class="input-container" style="top: 205px; left: 440px; font-size: 11px;">
                <strong>{{ $from }}</strong>
            </div>

            {{-- to --}}
            <div class="input-container" style="top: 230px; left: 110px; font-size: 11px;">
                <strong>{{ $to }}</strong>
            </div>

            {{-- nama pyd--}}
            @php
                $pydName = $bankOfficerPyd->officer_name;
                if (strlen($pydName) > 25) {
                    $breakPosition = strrpos(substr($pydName, 0, 25), ' ');
                    $breakPosition = $breakPosition !== false ? $breakPosition : 25;
                    $pydName = substr($pydName, 0, $breakPosition) . '<br>' . trim(substr($pydName, $breakPosition));
                }
            @endphp

            <div class="input-container" style="top: 445px; left: 145px; font-size: 11px;">
                <strong>{!! $pydName !!}</strong>
            </div>

            {{-- ic pyd--}}
            <div class="input-container" style="top: 445px; left: 450px; font-size: 11px;">
                <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
            </div>

            {{-- tarikh perakuan pyd--}}
            <div class="input-container" style="top: 473px; left: 145px; font-size: 11px;">
                <strong>{{ $pydInfo->date_signed->format('d/m/Y H:i:s A') }}</strong>
            </div>

            {{-- userid pyd--}}
            <div class="input-container" style="top: 473px; left: 450px; font-size: 11px;">
                <strong>{{ $settInfo->pyd_id }}</strong>
            </div>

            {{-- nama pym--}}
            @php
                $pymName = $bankOfficerPym->officer_name;
                if (strlen($pymName) > 25) {
                    $breakPosition = strrpos(substr($pymName, 0, 25), ' ');
                    $breakPosition = $breakPosition !== false ? $breakPosition : 25;
                    $pymName = substr($pymName, 0, $breakPosition) . '<br>' . trim(substr($pymName, $breakPosition));
                }
            @endphp

            <div class="input-container" style="top: 540px; left: 145px; font-size: 11px;">
                <strong>{!! $pymName !!}</strong>
            </div>

            {{-- ic pym--}}
            <div class="input-container" style="top: 540px; left: 450px; font-size: 11px;">
                <strong>{{ substr($bankOfficerPym->nokp, 0, 6) . '-' . substr($bankOfficerPym->nokp, 6, 2) . '-' . substr($bankOfficerPym->nokp, 8, 4) }}</strong>
            </div>

            {{-- tarikh perakuan pym--}}
            <div class="input-container" style="top: 568px; left: 145px; font-size: 11px;">
                <strong>{{ $pymInfo->date_signed->format('d/m/Y H:i:s A') }}</strong>
            </div>

            {{-- userid pym--}}
            <div class="input-container" style="top: 568px; left: 450px; font-size: 11px;">
                <strong>{{ $settInfo->pym_id }}</strong>
            </div>

            {{-- keputusan PMC--}}
            <div class="input-container" style="top: 633px; left: 170px; font-size: 11px;">
                <strong>{{ $pmcInfo->exit_flag == 1 ? 'PENILAIAN SEMULA' : 'TANPA PENILAIAN SEMULA' }}</strong>
            </div>
            
            {{-- kategori penangguhan--}}
            <div class="input-container" style="top: 690px; left: 83px; font-size: 10px;">
                <strong>{{ 
                    $pmcInfo->exit_type_flag == 1 ? 'Tanpa Syarat' : 
                    ($pmcInfo->exit_type_flag == 2 ? 'Bersyarat (a)' :
                    ($pmcInfo->exit_type_flag == 3 ? 'Bersyarat (b)' : ''))
                }}</strong>
            </div>

            {{-- tindakan sistem--}}
            <div class="input-container" style="top: 690px; left: 247px; font-size: 10px;">
                <strong>{{ 
                    $pmcInfo->exit_type_flag == 1 ? 'Proses penilaian dikembalikan kepada asal.' : 
                    ($pmcInfo->exit_type_flag == 2 ? 'Proses penilaian dikembalikan kepada peringkat PMG-i (2).' :
                    ($pmcInfo->exit_type_flag == 3 ? 'Proses penilaian dikekalkan diperingkat PMG-i (3).' : ''))
                }}</strong>
            </div>

            {{-- nama pmc--}}
            @php
                $pmcName = $bankOfficerPmc->officer_name;
                if (strlen($pmcName) > 25) {
                    $breakPosition = strrpos(substr($pmcName, 0, 25), ' ');
                    $breakPosition = $breakPosition !== false ? $breakPosition : 25;
                    $pmcName = substr($pmcName, 0, $breakPosition) . '<br>' . trim(substr($pmcName, $breakPosition));
                }
            @endphp

            <div class="input-container" style="top: 716px; left: 145px; font-size: 11px;">
                <strong>{!! $pmcName !!}</strong>
            </div>

            {{-- ic pmc--}}
            <div class="input-container" style="top: 716px; left: 450px; font-size: 11px;">
                <strong>{{ substr($bankOfficerPmc->nokp, 0, 6) . '-' . substr($bankOfficerPmc->nokp, 6, 2) . '-' . substr($bankOfficerPmc->nokp, 8, 4) }}</strong>
            </div>

            {{-- tarikh perakuan pmc--}}
            <div class="input-container" style="top: 744px; left: 145px; font-size: 11px;">
                <strong>{{ $pmcInfo->date_signed->format('d/m/Y H:i:s A') }}</strong>
            </div>

            {{-- userid pmc--}}
            <div class="input-container" style="top: 744px; left: 450px; font-size: 11px;">
                <strong>{{ $settInfo->pmc_id }}</strong>
            </div>
        </div>
    </div>

    @if (isset($attachmentPaths['pyd_attachment']))
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 7 ----------------------------------------------------------------------------->    
    <div style="text-align: center; padding: 50px;">
        <h3>PYD Attachment</h3>
        <img src="{{ $attachmentPaths['pyd_attachment'] }}" alt="PYD Attachment" style="width: 90%; max-height: 800px; object-fit: contain; border: 1px solid #ccc;">
    </div>
    @endif

    @if (isset($attachmentPaths['pym_attachment']))
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 8 ----------------------------------------------------------------------------->    
    <div style="text-align: center; padding: 50px;">
        <h3>PYM Attachment</h3>
        <img src="{{ $attachmentPaths['pym_attachment'] }}" alt="PYM Attachment" style="width: 90%; max-height: 800px; object-fit: contain; border: 1px solid #ccc;">
    </div>
    @endif

    @if (isset($attachmentPaths['pmc_attachment']))
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 9 ----------------------------------------------------------------------------->    
    <div style="text-align: center; padding: 50px;">
        <h3>PMC Attachment</h3>
        <img src="{{ $attachmentPaths['pmc_attachment'] }}" alt="PMC Attachment" style="width: 90%; max-height: 800px; object-fit: contain; border: 1px solid #ccc;">
    </div>
    @endif

</body>
</html>