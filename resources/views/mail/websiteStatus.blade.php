<!DOCTYPE html>
<html>
<head>
    <title>Website Status</title>
</head>
<body>
    <p>Hello {{ $ownerName }},</p>
    
    <!-- For Statuts -->
    @if($key === 'status')
        @if($status === 1)
            <p>Your website <span class="text-blue-600">{{ $websiteName }}</span> has been <span class="text-green-600">approved</span> successfully. Welcome to the <strong>Spotly</strong> platform!</p>
            <p class="text-yellow-600">You now have a 3-day free trial. If no payment is made within this period, your website will automatically become inactive.</p>

            <div class="flex items-center gap-2 justify-center">
                <a href="http://127.0.0.1:8000/dashboard/my-websites/payment" class="px-4 py-3 bg-zinc-700 text-white transition-all duration-300 ease-in-out hover:bg-zinc-800">
                    Make a Payment
                </a>
                <a href="{{ $websiteSubdomain }}.spotly.com" class="px-4 py-3 bg-blue-500 text-white transition-all duration-300 ease-in-out hover:bg-blue-600">
                    Visit My Website
                </a>
            </div>
        @elseif($status === 0)
            <p>Your website <strong>{{ $websiteName }}</strong> has been <span class="text-red-600">denied</span>.</p>

            <p class="text-yellow-600">This decision may be due to one of the following reasons:</p>
            <div class="flex flex-col gap-1">
                <p><strong>1.</strong> Submission of false or misleading data.</p>
                <p><strong>2.</strong> Content that does not comply with our platform policies.</p>
            </div>

            <div class="flex items-center gap-2 justify-center">
                <a href="http://127.0.0.1:8000/website-builder" class="px-4 py-3 bg-zinc-700 text-white transition-all duration-300 ease-in-out hover:bg-zinc-800">
                    Create a New Website
                </a>
            </div>
        @endif

    <!-- For the Active status -->
    @elseif($key === 'is_active')
        @if($status === 1)
            <p>Your website <span class="text-blue-600">{{ $websiteName }}</span> has been <span class="text-green-600">activated</span> successfully. Welcome again to the <strong>Spotly</strong> platform!</p>

            <div class="flex items-center gap-2 justify-center">
                <a href="https://{{ $websiteSubdomain }}.spotly.com" class="px-4 py-3 bg-blue-500 text-white transition-all duration-300 ease-in-out hover:bg-blue-600">
                    Visit My Website
                </a>
            </div>
        @elseif($status === 0)
            <p>Your website <strong>{{ $websiteName }}</strong> is currently <span class="text-red-600">inactive</span>.</p>

            <p class="text-yellow-600">This may have occurred for one of the following reasons:</p>
            <div class="flex flex-col gap-1">
                <p><strong>1.</strong> Payment has not been completed.</p>
                <p><strong>2.</strong> Content violates our platform guidelines.</p>
                <p><strong>3.</strong> Misleading or fraudulent activity detected.</p>
            </div>

            <div class="flex items-center gap-2 justify-center">
                <a href="http://127.0.0.1:8000/dashboard/my-websites" class="px-4 py-3 bg-zinc-700 text-white transition-all duration-300 ease-in-out hover:bg-zinc-800">
                    Review My Website
                </a>
            </div>
        @endif

    <!-- For the verification status -->
    @elseif($key === 'is_verified')
        @if($status === 1)
            <p>Your website <span class="text-blue-600">{{ $websiteName }}</span> has been <span class="text-green-600">verified</span> successfully. Welcome to the <strong>Spotly</strong> community!</p>

            <div class="flex items-center gap-2 justify-center">
                <a href="https://{{ $websiteSubdomain }}.spotly.com" class="px-4 py-3 bg-blue-500 text-white transition-all duration-300 ease-in-out hover:bg-blue-600">
                    Visit My Website
                </a>
            </div>
        @elseif($status === 0)
            <p>Your website <strong>{{ $websiteName }}</strong> could not be <span class="text-red-600">verified</span>.</p>

            <p class="text-yellow-600">This may be due to one of the following issues:</p>
            <div class="flex flex-col gap-1">
                <p><strong>1.</strong> Misleading or fraudulent activity detected.</p>
            </div>

            <div class="flex items-center gap-2 justify-center">
                <a href="http://127.0.0.1:8000/dashboard/my-websites" class="px-4 py-3 bg-zinc-700 text-white transition-all duration-300 ease-in-out hover:bg-zinc-800">
                    Review My Website
                </a>
            </div>
        @endif
    @endif
</body>
</html>
