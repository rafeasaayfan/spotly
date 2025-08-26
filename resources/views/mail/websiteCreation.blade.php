<!DOCTYPE html>
<html>
<head>
    <title>Website Creation</title>
</head>
<body>
    @if($recipientType === 'admin')
        <div class="flex flex-col gap-2">
            <p class="mb-2 pb-2 border-b border-muted">
                A new website creation by 
                <a class="text-blue-600 font-medium cursor-pointer" href="mailto:{{ $ownerEmail }}">
                    {{ $ownerEmail }}
                </a>
            </p>
            <div class="w-full flex items-center justify-center flex-col gap-3">
                <p>Website Name: <strong>{{ $websiteName }}</strong></p>

                <a href="http://127.0.0.1:8000/dashboard/websites?search={{ $websiteName }}" 
                   class="text-sm px-4 py-3 bg-blue-500 text-white transition-all duration-300 ease-in-out hover:bg-blue-600"
                >
                    Go check the dashboard
                </a>
            </div>
        </div>
    @elseif($recipientType === 'owner')
        <div class="flex flex-col gap-2">
            <p>Welcome to <strong>Spotly</strong>, and thank you for trusting our website.</p>

            <p>
                Your website <strong>{{ $websiteName }}</strong>
                has been created successfully. Our admins will review it, and you will receive a new email once it is approved.
            </p>
        </div>
    @endif
</body>
</html>
