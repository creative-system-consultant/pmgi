<!DOCTYPE html>
<html>
<head>
    <title>BORANG LO PERIBADI</title>
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
        @if($settInfo->pmgi_level != 'PM3')
            <img src="image/borang/jpoc/BorangJpocPmgi12-1.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        @else
            <img src="image/borang/jpoc/BorangJpocPmgi3-1.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        @endif

        <div class="content-overlay">
            {{-- pmgi level --}}
            @if($settInfo->pmgi_level == 'PM1')
                <div class="input-container" style="top: 96px; left: 111px; font-size: 16px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @elseif ($settInfo->pmgi_level == 'PM2')
                <div class="input-container" style="top: 96px; left: 132px; font-size: 16px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @elseif ($settInfo->pmgi_level == 'PM3')
                <div class="input-container" style="top: 96px; left: 156px; font-size: 16px;">
                    <strong class="checkmark">&#x2713;</strong>
                </div>
            @endif

            {{-- nama --}}
            <div class="input-container" style="top: 179px; left: 200px;">
                <strong>{{ $bankOfficerPyd->officer_name }}</strong>
            </div>

            {{-- ic --}}
            <div class="input-container" style="top: 219px; left: 200px;">
                <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
            </div>

            {{-- staff no --}}
            <div class="input-container" style="top: 219px; left: 550px;">
                <strong>{{ $bankOfficerPyd->staffno }}</strong>
            </div>

            {{-- jawatan --}}
            <div class="input-container" style="top: 247px; left: 200px;">
                <strong>{{ $bankOfficerPyd->officer_position }}</strong>
            </div>

            {{-- tarikh lantikan --}}
            <div class="input-container" style="top: 247px; left: 550px;">
                <strong>30/1/2019</strong>
            </div>

            {{-- negeri --}}
            <div class="input-container" style="top: 277px; left: 200px;">
                <strong>{{ $state }}</strong>
            </div>

            {{-- branch --}}
            <div class="input-container" style="top: 305px; left: 200px;">
                <strong>{{ $branch }}</strong>
            </div>

            {{-- tempoh berkhidmat --}}
            <div class="input-container" style="top: 333px; left: 350px;">
                <strong>5 TAHUN 2 BULAN</strong>
            </div>

            {{-- no fon --}}
            <div class="input-container" style="top: 360px; left: 200px;">
                <strong>019-9445211</strong>
            </div>

            {{-- emel --}}
            <div class="input-container" style="top: 360px; left: 450px;">
                <strong>{{ $bankOfficerPyd->email }}</strong>
            </div>

            {{-- alamat 1 --}}
            <div class="input-container" style="top: 390px; left: 150px;">
                <strong>NO. 11, JALAN 9/6, TAMAN IKS, SEKSYEN 9, BANDAR BARU BANGI</strong>
            </div>

            {{-- alamat 2 --}}
            <div class="input-container" style="top: 420px; left: 150px;">
                <strong>43650, SELANGOR</strong>
            </div>

            @php
                $accCount = ($settInfo->mntrSession->BIL_A1 ?? 0) + ($settInfo->mntrSession->BIL_A2 ?? 0) + ($settInfo->mntrSession->BIL_A3 ?? 0) + ($settInfo->mntrSession->BIL_B1 ?? 0) + ($settInfo->mntrSession->BIL_B2 ?? 0) + ($settInfo->mntrSession->BIL_C1 ?? 0) + ($settInfo->mntrSession->BIL_C2 ?? 0) + ($settInfo->mntrSession->BIL_D ?? 0);

                $osB1D = ($settInfo->mntrSession->RM_B1 ?? 0) + ($settInfo->mntrSession->RM_B2 ?? 0) + ($settInfo->mntrSession->RM_C1 ?? 0) + ($settInfo->mntrSession->RM_C2 ?? 0) + ($settInfo->mntrSession->RM_D ?? 0);

                $osAll = ($settInfo->mntrSession->RM_A1 ?? 0) + ($settInfo->mntrSession->RM_A2 ?? 0) + ($settInfo->mntrSession->RM_A3 ?? 0) + ($settInfo->mntrSession->RM_B1 ?? 0) + ($settInfo->mntrSession->RM_B2 ?? 0) + ($settInfo->mntrSession->RM_C1 ?? 0) + ($settInfo->mntrSession->RM_C2 ?? 0) + ($settInfo->mntrSession->RM_D ?? 0);

                $npfOs = $osAll > 0 ? round(($osB1D / $osAll) * 100, 2) : 0;
            @endphp

            {{-- bil seliaan --}}
            <div class="input-container" style="top: 493px; left: 270px;">
                <strong>{{ $accCount }}</strong>
            </div>

            {{-- npf os % --}}
            <div class="input-container" style="top: 493px; left: 600px;">
                <strong>{{ $npfOs }}</strong>
            </div>

            {{-- b1 --}}
            <div class="input-container" style="top: 520px; left: 285px;">
                <strong>{{ $settInfo->mntrSession->BIL_B1 ?? 0 }}</strong>
            </div>

            {{-- b2 --}}
            <div class="input-container" style="top: 520px; left: 325px;">
                <strong>{{ $settInfo->mntrSession->BIL_B2 ?? 0 }}</strong>
            </div>

            {{-- c1 --}}
            <div class="input-container" style="top: 520px; left: 367px;">
                <strong>{{ $settInfo->mntrSession->BIL_C1 ?? 0 }}</strong>
            </div>

            {{-- c2 --}}
            <div class="input-container" style="top: 520px; left: 408px;">
                <strong>{{ $settInfo->mntrSession->BIL_C2 ?? 0 }}</strong>
            </div>

            {{-- d --}}
            <div class="input-container" style="top: 520px; left: 445px;">
                <strong>{{ $settInfo->mntrSession->BIL_D ?? 0 }}</strong>
            </div>

            {{-- tarikh --}}
            <div class="input-container" style="top: 593px; left: 270px;">
                <strong>{{ \Carbon\Carbon::parse($sessionInfo->session_date)->format('d/m/Y') }}</strong>
            </div>

            {{-- masa --}}
            <div class="input-container" style="top: 622px; left: 270px;">
                <strong>{{ \Carbon\Carbon::parse($sessionInfo->session_date)->format('h:i:s A') }}</strong>
            </div>

            {{-- tempat --}}
            <div class="input-container" style="top: 651px; left: 270px;">
                <strong>{{ $sessionInfo->venue }}</strong>
            </div>

            {{-- pym --}}
            <div class="input-container" style="top: 722px; left: 270px;">
                <strong>{{ $bankOfficerPym->officer_name . ' (' . $bankOfficerPym->staffno . ')' }}</strong>
            </div>

            @if ($settInfo->pmgi_level == 'PM3')
                {{-- pmc --}}
                <div class="input-container" style="top: 750px; left: 270px;">
                    <strong>{{ $bankOfficerPmc->officer_name . ' (' . $bankOfficerPmc->staffno . ')' }}</strong>
                </div>
            @endif

        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 2 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-2.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            <div class="input-container">
                <img src="{{ $paths['image'] }}" alt="Generated Image" style="margin-top: 150px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 3 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-3.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- masalah dihadapi --}}
            <div class="input-container" style="top: 200px; left: 140px;">
                <strong>{{ $pydInfo->problemTable->description }}</strong>
            </div>

            {{-- punca --}}
            <div class="input-container" style="top: 350px; left: 140px;">
                <strong>{{ $pydInfo->reason }}</strong>
            </div>

            {{-- pelan tindakan --}}
            <div class="input-container" style="top: 500px; left: 140px;">
                <strong>{{ $pydInfo->action }}</strong>
            </div>

            {{-- ulasan --}}
            <div class="input-container" style="top: 660px; left: 140px;">
                <strong>{{ $pydInfo->comments }}</strong>
            </div>
        </div>
    </div>
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 4 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-4.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            {{-- ulasan --}}
            <div class="input-container" style="top: 190px; left: 140px;">
                <strong>{{ $pymInfo->comments }}</strong>
            </div>

            {{-- pelan tindakan --}}
            <div class="input-container" style="top: 360px; left: 140px;">
                <strong>{{ $pymInfo->action }}</strong>
            </div>
        </div>
    </div>
    <div class="page_break"></div>

    <!--------------------------------------------------------------- page 5 ----------------------------------------------------------------------------->
    @if ($settInfo->pmgi_level != 'PM3')
        <div class="centered">
            <img src="image/borang/jpoc/BorangJpocPmgi12-5.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
            <div class="content-overlay">
                {{-- nama --}}
                <div class="input-container" style="top: 128px; left: 110px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->officer_name }}</strong>
                </div>

                {{-- ic --}}
                <div class="input-container" style="top: 128px; left: 540px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
                </div>

                {{-- staff no --}}
                <div class="input-container" style="top: 150px; left: 170px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->staffno }}</strong>
                </div>

                {{-- branch --}}
                <div class="input-container" style="top: 150px; left: 330px; font-size: 11px;">
                    <strong>{{ $branch }}</strong>
                </div>

                {{-- from --}}
                <div class="input-container" style="top: 198px; left: 490px; font-size: 11px;">
                    <strong>{{ $from }}</strong>
                </div>

                {{-- to --}}
                <div class="input-container" style="top: 221px; left: 90px; font-size: 11px;">
                    <strong>{{ $to }}</strong>
                </div>

                {{-- nama pyd--}}
                <div class="input-container" style="top: 358px; left: 145px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->officer_name }}</strong>
                </div>

                {{-- ic pyd--}}
                <div class="input-container" style="top: 358px; left: 400px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
                </div>

                {{-- tarikh perakuan pyd--}}
                <div class="input-container" style="top: 386px; left: 145px; font-size: 11px;">
                    <strong>{{ $pydInfo->date_signed }}</strong>
                </div>

                {{-- userid pyd--}}
                <div class="input-container" style="top: 386px; left: 400px; font-size: 11px;">
                    <strong>{{ $settInfo->pyd_id }}</strong>
                </div>

                {{-- nama pym--}}
                <div class="input-container" style="top: 472px; left: 145px; font-size: 11px;">
                    <strong>{{ $bankOfficerPym->officer_name }}</strong>
                </div>

                {{-- ic pym--}}
                <div class="input-container" style="top: 472px; left: 400px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPym->nokp, 0, 6) . '-' . substr($bankOfficerPym->nokp, 6, 2) . '-' . substr($bankOfficerPym->nokp, 8, 4) }}</strong>
                </div>

                {{-- tarikh perakuan pym--}}
                <div class="input-container" style="top: 501px; left: 145px; font-size: 11px;">
                    <strong>{{ $pymInfo->date_signed }}</strong>
                </div>

                {{-- userid pym--}}
                <div class="input-container" style="top: 501px; left: 400px; font-size: 11px;">
                    <strong>{{ $settInfo->pym_id }}</strong>
                </div>

            </div>
        </div>
    @else
        <div class="centered">
            <img src="image/borang/jpoc/BorangJpocPmgi3-5.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
            <div class="content-overlay">
                {{-- adil flag --}}
                @if($pmcInfo->fair_flag == 1)
                    <div class="input-container" style="top: 150px; left: 130px; font-size: 11px;">
                        <strong class="checkmark">&#x2713;</strong>
                    </div>
                @else
                    <div class="input-container" style="top: 190px; left: 130px; font-size: 11px;">
                        <strong class="checkmark">&#x2713;</strong>
                    </div>
                @endif

                {{-- ulasan --}}
                <div class="input-container" style="top: 260px; left: 140px;">
                    <strong>{{ $pmcInfo->fair_comments }}</strong>
                </div>

                {{-- adil flag --}}
                @if($pmcInfo->undrstd_flag == 1)
                    <div class="input-container" style="top: 423px; left: 130px; font-size: 11px;">
                        <strong class="checkmark">&#x2713;</strong>
                    </div>
                @else
                    <div class="input-container" style="top: 463px; left: 130px; font-size: 11px;">
                        <strong class="checkmark">&#x2713;</strong>
                    </div>
                @endif

                {{-- ulasan --}}
                <div class="input-container" style="top: 530px; left: 140px;">
                    <strong>{{ $pmcInfo->comments }}</strong>
                </div>

                {{-- lain-lain --}}
                <div class="input-container" style="top: 685px; left: 140px;">
                    <strong>{{ $pmcInfo->others }}</strong>
                </div>
            </div>
        </div>
        <div class="page_break"></div>
    @endif

    @if($settInfo->pmgi_level == 'PM3')
        <div class="centered">
            <img src="image/borang/jpoc/BorangJpocPmgi3-6.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
            <div class="content-overlay">
                {{-- nama --}}
                <div class="input-container" style="top: 143px; left: 110px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->officer_name }}</strong>
                </div>

                {{-- ic --}}
                <div class="input-container" style="top: 143px; left: 540px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
                </div>

                {{-- staff no --}}
                <div class="input-container" style="top: 165px; left: 190px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->staffno }}</strong>
                </div>

                {{-- branch --}}
                <div class="input-container" style="top: 165px; left: 380px; font-size: 11px;">
                    <strong>{{ $branch }}</strong>
                </div>

                {{-- from --}}
                <div class="input-container" style="top: 213px; left: 490px; font-size: 11px;">
                    <strong>{{ $from }}</strong>
                </div>

                {{-- to --}}
                <div class="input-container" style="top: 236px; left: 90px; font-size: 11px;">
                    <strong>{{ $to }}</strong>
                </div>

                {{-- nama pyd--}}
                <div class="input-container" style="top: 373px; left: 145px; font-size: 11px;">
                    <strong>{{ $bankOfficerPyd->officer_name }}</strong>
                </div>

                {{-- ic pyd--}}
                <div class="input-container" style="top: 373px; left: 400px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4) }}</strong>
                </div>

                {{-- tarikh perakuan pyd--}}
                <div class="input-container" style="top: 401px; left: 145px; font-size: 11px;">
                    <strong>{{ $pydInfo->date_signed }}</strong>
                </div>

                {{-- userid pyd--}}
                <div class="input-container" style="top: 401px; left: 400px; font-size: 11px;">
                    <strong>{{ $settInfo->pyd_id }}</strong>
                </div>

                {{-- nama pym--}}
                <div class="input-container" style="top: 487px; left: 145px; font-size: 11px;">
                    <strong>{{ $bankOfficerPym->officer_name }}</strong>
                </div>

                {{-- ic pym--}}
                <div class="input-container" style="top: 487px; left: 400px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPym->nokp, 0, 6) . '-' . substr($bankOfficerPym->nokp, 6, 2) . '-' . substr($bankOfficerPym->nokp, 8, 4) }}</strong>
                </div>

                {{-- tarikh perakuan pym--}}
                <div class="input-container" style="top: 516px; left: 145px; font-size: 11px;">
                    <strong>{{ $pymInfo->date_signed }}</strong>
                </div>

                {{-- userid pym--}}
                <div class="input-container" style="top: 516px; left: 400px; font-size: 11px;">
                    <strong>{{ $settInfo->pym_id }}</strong>
                </div>

                {{-- nama pmc--}}
                <div class="input-container" style="top: 601px; left: 145px; font-size: 11px;">
                    <strong>{{ $bankOfficerPmc->officer_name }}</strong>
                </div>

                {{-- ic pmc--}}
                <div class="input-container" style="top: 601px; left: 400px; font-size: 11px;">
                    <strong>{{ substr($bankOfficerPmc->nokp, 0, 6) . '-' . substr($bankOfficerPmc->nokp, 6, 2) . '-' . substr($bankOfficerPmc->nokp, 8, 4) }}</strong>
                </div>

                {{-- tarikh perakuan pmc--}}
                <div class="input-container" style="top: 630px; left: 145px; font-size: 11px;">
                    <strong>{{ $pmcInfo->date_signed }}</strong>
                </div>

                {{-- userid pmc--}}
                <div class="input-container" style="top: 630px; left: 400px; font-size: 11px;">
                    <strong>{{ $settInfo->pmc_id }}</strong>
                </div>

            </div>
        </div>
    @endif

    @if ($pydInfo->attachment)
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 6 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-6.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            <div class="input-container">
                <img src="{{ storage_path('app/public/' . $pydInfo->attachment) }}" alt="Generated Image" style="margin-top: 150px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
    @endif

    @if ($pymInfo->attachment)
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 7 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi12-7.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            <div class="input-container">
                <img src="{{ storage_path('app/public/' . $pymInfo->attachment) }}" alt="Generated Image" style="margin-top: 150px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
    @endif

    @if ($settInfo->pmgi_level == 'PM3' && $pmcInfo->attachment)
    <div class="page_break"></div>
    <!--------------------------------------------------------------- page 8 ----------------------------------------------------------------------------->
    <div class="centered">
        <img src="image/borang/jpoc/BorangJpocPmgi3-7.jpg" alt="BORANG JPoc PMGi 12" style="margin-top: 200px" width="700" height="900">
        <div class="content-overlay">
            <div class="input-container">
                <img src="{{ storage_path('app/public/' . $pmcInfo->attachment) }}" alt="Generated Image" style="margin-top: 150px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
    @endif

</body>
</html>
