@props(['attachmentKey', 'title', 'attachmentPaths', 'attachmentExtension', 'imageExtensions'])

@if (isset($attachmentPaths[$attachmentKey]))
    <div class="page_break"></div>
    <div style="text-align: center; padding: 50px;">
        <h3>{{ $title }}</h3>
        
        @if(in_array($attachmentExtension[$attachmentKey], $imageExtensions))
            {{-- Display single image --}}
            <img src="{{ $attachmentPaths[$attachmentKey] }}" 
                alt="{{ $title }}" 
                style="width: 90%; max-height: 800px; object-fit: contain; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                
        @elseif($attachmentExtension[$attachmentKey] === 'pdf')
            {{-- Display converted PDF pages --}}
            @foreach($attachmentPaths[$attachmentKey] as $index => $imagePath)
                <div style="margin-bottom: 30px;">
                    <p style="font-size: 14px; color: #666; margin-bottom: 10px; font-weight: 600;">
                        Halaman {{ $index + 1 }} daripada {{ count($attachmentPaths[$attachmentKey]) }}
                    </p>
                    <img src="{{ $imagePath }}" 
                        alt="PDF Halaman {{ $index + 1 }}" 
                        style="width: 90%; border: 1px solid #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: white;">
                </div>
                
                @if(!$loop->last)
                    <div class="page_break"></div>
                @endif
            @endforeach
        @endif
    </div>
@endif