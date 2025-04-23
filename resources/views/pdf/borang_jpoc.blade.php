<!DOCTYPE html>
<html>
<head>
    <title>BORANG JPOC 09</title>
</head>
<body>
    @if($settInfo->pmgi_level != 'PM3')
        @include('pdf.borang_jpoc_pm12')
    @else
        @include('pdf.borang_jpoc_pm3')
    @endif
</body>
</html>
